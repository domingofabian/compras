# Guía de Despliegue en la Nube

Alojar una aplicación que usa múltiples servicios (`docker-compose` con PHP, MySQL y phpMyAdmin) en plataformas PaaS gratuitas (como Heroku o Render) es complicado debido a las limitaciones para mantener bases de datos activas o múltiples contenedores.

La ruta más profesional y robusta es obtener un **VPS (Máquina Virtual Virtual Private Server)** con capa gratuita, instalar Docker y ejecutar el archivo `docker-compose.yml`.

## Opciones de Alojamiento Gratuito

### 1. Oracle Cloud "Always Free" (Recomendada)
Es actualmente la capa gratuita más generosa del mercado.
* **Características:** Permite crear máquinas virtuales ARM de alto rendimiento (hasta 24GB de RAM) o AMD, con 50 GB de almacenamiento.
* **Ventaja:** Gratis de por vida y recursos de sobra para la aplicación.
* **Desventaja:** El registro inicial a veces rechaza ciertas tarjetas de crédito/débito de prueba.

### 2. Google Cloud Platform (GCP)
* **Características:** Google regala una instancia `e2-micro` (1 GB de RAM, 30 GB de disco).
* **Ventaja:** Muy confiable y verdaderamente gratis de por vida. Suficiente para correr la aplicación, MySQL y phpMyAdmin.
* **Desventaja:** Requiere configurar facturación al registrarse.

### 3. Amazon Web Services (AWS) - EC2 Free Tier
* **Características:** Instancia `t2.micro` (1 GB RAM).
* **Ventaja:** Excelente infraestructura. Ideal si ya se tiene una cuenta de AWS o conocimientos en dicho entorno.
* **Desventaja:** La capa gratuita dura solamente **1 año (12 meses)** desde la creación de la cuenta.

---

## Instrucciones para el Despliegue

Una vez que tengas acceso a la consola de tu servidor virtual (mediante SSH o la terminal web del proveedor), sigue estos pasos:

### Paso 1: Conectarte al Servidor
Abre una terminal y conéctate a tu VPS.
```bash
ssh usuario@tu-direccion-ip
```

### Paso 2: Actualizar e Instalar Docker y Git
En servidores basados en Ubuntu/Debian:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install docker.io docker-compose git -y
```

### Paso 3: Clonar el Proyecto
Descarga el código del repositorio a tu servidor:
```bash
git clone https://tu-link-del-repositorio.git
cd nombre-de-la-carpeta
```
*(Si no usas Git, puedes subir los archivos usando herramientas como FileZilla/SFTP).*

### Paso 4: Levantar los Contenedores
Inicia los servicios en segundo plano usando Docker Compose:
```bash
sudo docker-compose up -d
```

### Paso 5: Configurar Google Login (Importante)
Como tu aplicación ahora se está ejecutando en un servidor real con una IP y puerto distinto al de tu computadora:
1. Entra a [Google Cloud Console (Credenciales)](https://console.cloud.google.com/apis/credentials).
2. Selecciona las credenciales OAuth de tu proyecto.
3. Añade la nueva URL como *URI de redireccionamiento autorizada*, por ejemplo:
   `http://TU-IP-DEL-SERVIDOR:8080/google-login/Login-google/index.php`
4. Guarda los cambios.

### Mantenimiento Básico
- **Ver logs de la aplicación:** `docker-compose logs -f app`
- **Ver logs de la base de datos:** `docker-compose logs -f db`
- **Reinciar los servicios:** `docker-compose restart`
- **Apagar y eliminar la red:** `docker-compose down`
- **Destruir la base de datos para resetearla:** `docker-compose down -v`
