# Guía Paso a Paso: Convertir URL Pública en APK (WebView)

Dado que tu aplicación está programada en PHP (el cual necesita un servidor web Apache/Nginx para funcionar y no se puede ejecutar de forma nativa en un móvil), la mejor alternativa para tener tu aplicación instalada en un celular Android es usar un **WebView**.

Un WebView es básicamente un navegador de internet invisible integrado dentro de una aplicación móvil que se encarga exclusivamente de mostrar e interactuar con la página web de tu sistema (tu URL pública). 

Aquí tienes **tres métodos distintos** para lograrlo, ordenados desde el más fácil (sin saber programar) hasta el más profesional.

---

## Método 1: La forma más fácil (Usando Servicios Online Gratuitos)

Existen diversas plataformas en la web que empaquetan tu URL automáticamente en un `.apk`. No necesitas instalar programas en tu computadora.

**Recomendados:** `AppCreator24.com` o `WebIntoApp.com`

**Pasos con WebIntoApp.com:**
1. Ingresa a [WebIntoApp.com](https://www.webintoapp.com/).
2. Completa los datos en la pantalla principal:
   * **URL:** Coloca la URL pública exacta de tu sistema de compras (ej. `https://tudominio.com/google-login/`).
   * **App Name:** Ponle un nombre, por ejemplo: "Sistema Compras".
   * **Icon:** Sube la imagen del icono que quieres que se vea en el celular (tienes un `2.jpeg`, puedes usar ese, aunque es ideal usar un PNG transparente).
3. Selecciona el sistema operativo: Presiona **Android**.
4. Dale clic al botón de **"Make App"** o **"Next"**.
5. Te pedirá registrarte (es gratis), completa el registro rápido.
6. Espera un par de minutos a que los servidores generen y compilen tu código.
7. Te darán un botón de descarga para bajar tu archivo **`.apk`**. ¡Y listo! Ya puedes enviarlo por WhatsApp o cable a tu Samsung A12 e instalarlo.

---

## Método 2: Empezando a Profesionalizar (Usando MIT App Inventor)

Es un servicio de educación del MIT, es totalmente gratuito, no añade marca de agua (como a veces los gratuitos online) y es muy rápido.

1. Ingresa a [MIT App Inventor](http://ai2.appinventor.mit.edu/) con tu cuenta de Google.
2. Inicia un nuevo proyecto y ponle nombre (ej. `AppCompras`).
3. Verás una pantalla simulando un celular. A la izquierda, en el panel **"User Interface"**, busca el componente **"WebViewer"** y arrástralo hacia el dibujo del celular.
4. Con el componente `WebViewer1` seleccionado, mira el panel de la derecha (**Properties**):
   * Busca la propiedad **"HomeUrl"**.
   * Pega ahí la URL pública de tu aplicación.
5. Para que la pantalla oculte el título feo que dice "Screen1": Selecciona la `Screen1` en la lista de componentes, busca a la derecha la propiedad **"TitleVisible"** y desmárcala.
6. Para poner tu icono: En las propiedades de `Screen1` busca **"Icon"** y sube tu logo (`2.jpeg`).
7. Arriba dclic en el menú **"Build" -> "Android App (.apk)"**.
8. Espera que compile. Aparecerá un Código QR. Escanéalo con la cámara de tu Samsung A12 y te descargará y ejecutará el APK automáticamente.

---

## Método 3: La forma Profesional (Usando Android Studio puro)

Si tienes algo de conocimientos de código y tu PC lo aguanta, usar código Java o Kotlin puro en Android Studio te da control al 100%.

1. **Descarga e instala [Android Studio](https://developer.android.com/studio)**.
2. Abre Android Studio y elige **"New Project"**.
3. Selecciona la opción **"Empty Views Activity"** y presiona Next.
4. Llámala "App de Compras", elige **Java** como lenguaje y SDK Mínimo (API 21 / Android 5.0).
5. En el panel izquierdo, abre la carpeta `AndroidManifest.xml` (en `app/src/main`) y agrega el permiso de internet justo arriba de `<application>`:
   ```xml
   <uses-permission android:name="android.permission.INTERNET" />
   ```
6. Abre la vista frontal del celular en `app/src/main/res/layout/activity_main.xml`. Elimina el código que viene y pon este:
   ```xml
   <?xml version="1.0" encoding="utf-8"?>
   <RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
       android:layout_width="match_parent"
       android:layout_height="match_parent">
   
       <WebView
           android:id="@+id/miWebView"
           android:layout_width="match_parent"
           android:layout_height="match_parent" />
   </RelativeLayout>
   ```
7. Ve al archivo principal de lógica `MainActivity.java` (en `app/src/main/java/.../MainActivity.java`) y pon el siguiente código dentro del método `onCreate()`:
   ```java
   // Vinculamos la vista
   WebView miWebView = findViewById(R.id.miWebView);
   
   // Habilitamos Javascript obligatoriamente
   miWebView.getSettings().setJavaScriptEnabled(true);
   
   // Para que cargue dentro de nuestra app y NO abriendo Google Chrome
   miWebView.setWebViewClient(new WebViewClient());    
   
   // URL a nuestro servidor PHP público
   miWebView.loadUrl("https://tu-dominio.com/google-login/index.php"); 
   ```
8. Una vez termines, vas al menú superior **Build -> Build Bundle(s) / APK(s) -> Build APK(s)**.
9. Sonará una alerta abajo a la derecha diciendo que "Generó el APK". Haz clic en "Locate" y tendrás el `.apk` nativo generado al 100% por ti. 

---

### Un Detalle Importante a Considerar

Al instalar un APK directamente (por fuera del Play Store), tu celular Samsung intentará bloquear la instalación por motivos de seguridad diciendo "Aplicación de orígenes desconocidos". 
Deberás darle a **Ajustes** dentro del cuadro de diálogo y **Habilitar orígenes desconocidos** (Confiar de esta fuente). ¡Y listo, tu sistema funcionará nativamente como un acceso directo pesado!
