<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

En este documento se presenta el proceso básico para utilizar la API de Gmail en una aplicación de Laravel y leer correos electrónicos, incluyendo la lectura de archivos PDF adjuntos. En primer lugar, se explica cómo configurar la API de Gmail y autenticar la aplicación de Laravel para acceder a la cuenta de Gmail. Luego, se describe cómo acceder a la bandeja de entrada y leer los correos electrónicos. Además, se proporciona información sobre cómo implementar la funcionalidad para leer archivos PDF adjuntos en los correos electrónicos. Al final del documento, los lectores tendrán una comprensión sólida de los pasos necesarios para utilizar la API de Gmail en una aplicación de Laravel para leer correos electrónicos y archivos adjuntos de PDF.

### ¿Cómo se usa?

* Configurar el entorno de Google, para obtener la API Key y el Client ID, necesarias para poder utilizar la aplicación
https://developers.google.com/gmail/api/quickstart/js

* Clonar el repositorio con el proyecto
https://github.com/kuronneko/gmail_api

* Establecer la API key y el Client ID en las líneas 55 y 56 del archivo "emails.php"

### Demostración

Es importante que la URL para obtener el token, sea la misma ingresada en el entorno de configuración de Google
http://localhost/gmail

La cuenta tiene que ser o la del usuario dueño de la aplicación de Google, o la de algún usuario invitado para acceder a esta.

Al momento de obtener el token de autorización se cargaran los últimos 10 correos electrónicos del buzón de entrada, y al momento de dar click en “Show Email” se mostrará el detalle de este, como también se renderiza el pdf (en caso de haber) en la columna derecha. En el console.log se muestra como se extrae el texto del pdf.

### Importante

El código de demostración para el proyecto fue escrito en su totalidad en javascript, utilizando como base el mostrado en la documentación de google (JavaScript quickstart). Es necesario señalar que también existen dependencias para hacer el mismo proceso con PHP.

### Documentación útil

https://stackoverflow.com/questions/11485271/google-oauth-2-authorization-error-redirect-uri-mismatch
https://developers.google.com/gmail/api/reference/rest/v1/users.messages.attachments/get
https://base64.guru/converter/decode/image
https://code.tutsplus.com/tutorials/how-to-create-a-pdf-viewer-in-javascript--cms-32505


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
