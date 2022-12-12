# oauth2mercadolibrephp

Un ejemplo sencillo en php de cómo loguearse en la API de mercado libre, por medio de OAuth2, y hacer la consulta de los 20 primeros productos más vendidos. Para esto previamente se debería haberse registrado una app en la sección de desarrolladores de mercado libre. Este script en php constituría el código de dicha app. 

Debe instalarse previamente la extensión curl para php y activarse en los archivos de configuración respectivos. 

También debe inicialiarse la variable $URLAPP con el nombre dns en la cual será posible consultar la app. Si no se dispone de un servidor DNS recomiendo usar la página de NOIP para hacer eso: https://www.noip.com. La ruta a configurar en la dirección de la aplicación en mercadolbre será $URLAPP/mercadolibre.php
