# Sistema de Confirmación de Asistencia por WhatsApp para Hospital

Este proyecto permite enviar mensajes de confirmación de asistencia a través de WhatsApp utilizando una combinación de tecnologías y librerías. 

## Tecnologías Utilizadas

- **Node.js**: Entorno de ejecución para JavaScript.
- **[whatsapp-web.js](https://github.com/pedroslopez/whatsapp-web.js.git)**: Librería para interactuar con WhatsApp Web. Versión `2.2408.1`.
- **qrcode-terminal**: Librería para generar códigos QR en la terminal.
- **fs**: Módulo de Node.js para manejar archivos del sistema.
- **mysql**: Librería para interactuar con bases de datos MySQL.
- **Bootstrap SB Admin 2 v4.0.3**: Plantilla de administración para el diseño.

## Interfaz de Ingreso de Excel

![](https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital/blob/master/dashboard/img/cargartabla.png)

## Pacientes Mal Ingresados

![](https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital/blob/master/dashboard/img/erroneo.png)

## QR para Iniciar WhatsApp Web

![](https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital/blob/master/dashboard/img/qr.png)

## Mensaje de Confirmación

![](https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital/blob/master/dashboard/img/Mensaje.png)

## Estructura del Archivo Excel

![](https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital/blob/master/dashboard/img/excel.png)

## Instalación

### Requisitos Previos

Asegúrate de tener **Node.js** instalado. Se recomienda usar **Node.js v18+**.

### Instalación de Dependencias

1. Clona el repositorio:

    ```bash
    git clone https://github.com/MarcoFloresCa/Sistema-de-confirmaci-n-de-asistencia-por-Whatsapp-para-hospital.git
    ```

2. Navega a la carpeta `receptor`:

    ```bash
    cd receptor
    ```

3. Instala las dependencias:

    ```bash
    npm install
    ```

4. Asegúrate de tener el archivo `somecloud.sql` cargado en tu base de datos MySQL.

## Ejecución

1. Ejecuta el script `receptor.js`:

    ```bash
    node receptor.js
    ```

2. Escanea el código QR generado en la terminal para conectar tu cuenta de WhatsApp.

3. Espera a que se envíen los mensajes de confirmación.

> [!IMPORTANT] **Ten en cuenta:**
> **No enviar muchos mensajes sin tomar medidas apropiadas, ya que podrías ser bloqueado por WhatsApp.**

## Recursos Adicionales

- [Documentación de whatsapp-web.js](https://github.com/pedroslopez/whatsapp-web.js)
- [Documentación de Node.js](https://nodejs.org/)
