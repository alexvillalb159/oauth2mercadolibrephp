# oauth2mercadolibrephp

Un ejemplo sencillo en php de cómo loguearse en la API de mercado libre, por medio de OAuth2, y hacer la consulta de los 20 primeros productos más vendidos en distintos países y para la categoría de Computación. Para esto previamente se debería haberse registrado una app en la sección de desarrolladores de Mercado Libre. Estos scripts en php constiturían el código de dicha app. 

Debe instalarse previamente la extensión curl para php y activarse en los archivos de configuración respectivos. 

El programa presume que la URL con la cual se le invoca es la misma URL de la APP que se registro en mercado libre. Esa URL debe finalizar con el script de PHP de arranquey ejecución principal: mercadolibre.php. La ruta se muestra al arrancar el probrama en la pantalla de autentificación. Si no se dispone de un servidor DNS recomiendo usar la página de NOIP para hacer eso: https://www.noip.com. 

Para la fecha (12-12-2022) las consultas avanzadas, como la de los los 20 primeros productos más vendidos de la API de mercado libre, no esta disponible en todos los países, además de eso tampoco es posible consultar los datos de cada producto, a pesar de que el ID del mismo si pueda ser visible. La razón para esto, entre otras, quizás se deba a las políticas de privacidad de cada vendedor. Pero es sorprendente que para el caso de Perú si sea posible consultar la información de todos los productos de la categoría de Computacón. Desconozco si la información a la que es posible acceder de manera pública sea consistente con la información relativa al contrato pago de la API. 
