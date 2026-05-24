# Guía de Migración a Supabase, Android APK y Google Login

Esta guía detalla los pasos para migrar la base de datos MySQL de la aplicación "Compras" a Supabase, convertir la aplicación PHP en un APK para Android que se conecte a Supabase, e integrar autenticación de Google validando contra una tabla `autorizados`.

---

## 1. Migración de la Base de Datos a Supabase (PostgreSQL)

Supabase utiliza PostgreSQL, por lo que es necesario migrar tu esquema actual de MySQL.

### Paso A: Exportar desde MySQL
1. Usa phpMyAdmin o la consola de MySQL para exportar la estructura y datos de tus tablas (`capturas`, `creditos`, `familias_tipos`, `formas_pago`, `usuarios`, `usuario_tarjetas`, `torneos`, `auto`, etc.) a un archivo SQL.

### Paso B: Crear Proyecto en Supabase
1. Ingresa a [Supabase.com](https://supabase.com) y crea una cuenta.
2. Crea un nuevo **Proyecto** (elige una contraseña fuerte para la base de datos y selecciona la región más cercana a ti, como "South America - São Paulo").
3. Espera a que el proyecto se aprovisione (toma un par de minutos).

### Paso C: Adaptar el SQL a PostgreSQL e Importar
El SQL de MySQL suele tener diferencias sintácticas con PostgreSQL.
1. Ve al **SQL Editor** en Supabase.
2. Crea las tablas de nuevo usando sintaxis compatible con PostgreSQL. Por ejemplo, en lugar de `INT AUTO_INCREMENT`, usa `SERIAL` o `BIGINT GENERATED ALWAY AS IDENTITY`.
3. Crea la tabla `autorizados` para el login:
   ```sql
   CREATE TABLE autorizados (
       id SERIAL PRIMARY KEY,
       email VARCHAR(255) UNIQUE NOT NULL,
       nombre VARCHAR(255)
   );
   ```
4. Inserta los datos importados o usa alguna herramienta como `pgloader` si la base de datos es muy grande.

---

## 2. Autenticación con Google Login y Validación de Usuarios

Supabase maneja la autenticación fácilmente, pero ya que tienes código PHP y quieres usar una carpeta `google-login`, deberás combinar el frontend con la validación en tu backend PHP o directamente usando el SDK de Supabase si migras el backend.

### Opción Recomendada (Manteniendo tu backend PHP actual)
Dado que tu app es PHP, la forma más sencilla es usar la librería de Google API para PHP.

#### Paso A: Configurar el OAuth en Google Cloud
1. Ve a la [Google Cloud Console](https://console.cloud.google.com).
2. Crea un proyecto y ve a **APIs & Services > Credentials**.
3. Configura la **OAuth consent screen**.
4. Crea unas credenciales de tipo **OAuth client ID** (tipo Web application).
5. Configura los **Authorized redirect URIs** (ej: `https://tu-dominio.com/google-login/callback.php`).
6. Copia el **Client ID** y el **Client Secret**.

#### Paso B: Integración en tu carpeta `google-login`
1. Instala la librería de Google usando Composer en tu proyecto: `composer require google/apiclient:^2.15.0`
2. En tu archivo `google-login/index.php` (o similar), crea el botón de Login:
   ```php
   require_once '../vendor/autoload.php';
   $client = new Google_Client();
   $client->setClientId('TU_CLIENT_ID');
   $client->setClientSecret('TU_CLIENT_SECRET');
   $client->setRedirectUri('https://tu-dominio.com/google-login/callback.php');
   $client->addScope("email");
   $client->addScope("profile");
   
   $loginUrl = $client->createAuthUrl();
   echo "<a href='$loginUrl'>Iniciar sesión con Google</a>";
   ```

#### Paso C: Validar con la tabla `autorizados`
En tu archivo `callback.php`:
1. Obtén el token y el perfil:
2. Extrae el `$email = $google_account_info->email;`
3. Consulta la base de datos (tu nueva conexión a Supabase):
   ```php
   // Usando PDO para conectarte a Supabase PostgreSQL
   $dsn = "pgsql:host=aws-0-sa-east-1.pooler.supabase.com;port=5432;dbname=postgres";
   $pdo = new PDO($dsn, "postgres.TUNOMBRE", "TUPASSWORD");
   
   $stmt = $pdo->prepare("SELECT * FROM autorizados WHERE email = ?");
   $stmt->execute([$email]);
   $usuario = $stmt->fetch();
   
   if ($usuario) {
       // Acceso permitido
       $_SESSION['usuario_logueado'] = $email;
       header("Location: ../COMPRAS/todos.php"); // Redirige a la app
   } else {
       // Acceso denegado
       echo "No estás autorizado para usar esta aplicación.";
   }
   ```

*(Nota: Si decides hacer el login directamente en el cliente (Android/JS) en lugar de PHP, usarás [Supabase Auth](https://supabase.com/docs/guides/auth/social-login/auth-google)).*

---

## 3. Convertir la App PHP en un APK para Android conectada a Supabase

Las aplicaciones PHP se ejecutan en un servidor web, **no pueden correr nativamente dentro de un APK de Android** sin un servidor incrustado. Por lo tanto, tu APK en realidad será una aplicación "Wrapper" (un WebView) que cargará tu aplicación web alojada en internet.

### Requisito Previo
Tu aplicación PHP **debe estar subida a un servidor público** en internet (un hosting compartido, VPS, Render, etc.) y no en `localhost/xamp`. Y tu backend de PHP en ese hosting ahora debe conectarse a las credenciales de base de datos que te dio Supabase en **Settings > Database > Connection Parameters**.

### Opción 1: Capacitor o Cordova (Web View moderno) - Recomendado
Estos frameworks toman tu URL web y te generan un APK profesional. Sin embargo, lo más fácil si no tienes código fuente (HTML/JS/CSS separados de PHP) es simplemente envolver la URL.

1. Instala Node.js y ejecuta:
   ```bash
   npm install -g @capacitor/cli
   ```
2. Inicia un proyecto vacío o usa un servicio que empaquete URLs.

### Opción 2: Usar PWA Builder o AppGyver/Convertidor Web-to-APK (La forma más fácil y rápida)

Dado que todo el código fuente es PHP (renderizado en el servidor web), el enfoque más pragmático es:

1. **Asegúrate de que la app sea Responsiva:** Ya adaptaste `modal.php` y `todos.php`.
2. **Usa un servicio Web-to-APK gratuio** (ej. WebViewGold, appcreator24, o PWA Builder).
   * Ingresas la URL pública de tu aplicación (ej: `https://midominio.com/google-login/index.php`).
   * Subes tu ícono (por ejemplo tu `2.jpeg`).
   * El servicio genera un archivo `.apk`.
3. **Instala el APK en el celular:** Al abrirla, la app en realidad es un navegador invisible que ingresa directamente a tu sistema. Si el usuario no ha iniciado sesión, verá el login con Google. Como se conecta por internet a tu servidor PHP, tu servidor PHP automáticamente sigue leyendo la base de datos alojada en **Supabase**.

### Opción 3: Reescribir el Frontend (Camino Difícil pero Nativo)
Si deseas una App **nativa**, donde el APK se conecta *directamente* a Supabase (sin pasar por PHP):
1. Debes abandonar PHP para las pantallas del celular.
2. Programar las vistas en Flutter o React Native.
3. Usar los SDKs de Supabase (ej. `supabase-flutter`) en el celular para hacer las consultas a la base de datos `capturas`, `creditos`, etc.
4. Usar el plugin de Google Sign-In de Flutter, enviar el token a Supabase Auth y restringir los permisos en la base de datos usando RLS (Row Level Security) simulando tu tabla `autorizados`.

**Se recomienda empezar por la Opción 2** instalando la App en un hosting y usando un wrapper (WebView) para probar el MVP rápidamente.
