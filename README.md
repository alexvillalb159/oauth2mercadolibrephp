# oauth2mercadolibrephp

Un ejemplo sencillo en php de cómo loguearse en la API de mercado libre, por medio de OAuth2, y hacer la consulta de los 20 primeros productos más vendidos en distintos países y para la categoría de computación. Para esto previamente se debería haberse registrado una app en la sección de desarrolladores de mercado libre. Estos scripts en php constituría el código de dicha app. 

Debe instalarse previamente la extensión curl para php y activarse en los archivos de configuración respectivos. 

También debe inicialiarse la variable $URLAPP con el nombre dns en la cual será posible consultar la app. Si no se dispone de un servidor DNS recomiendo usar la página de NOIP para hacer eso: https://www.noip.com. La ruta a configurar en la dirección de la aplicación en mercadolbre será $URLAPP/mercadolibre.php

Para la fecha (12-12-2022) las consultas avanzadas, como la de los los 20 primeros productos más vendidos de la API de mercado libre, no esta disponible en todos los países, además de eso tampoco es posible consultar los datos de cada producto, a pesar de que el ID del mismo si pueda ser visible. La razón para esto, entre otras, quizás se deba a las políticas de privacidad de cada vendedor. Pero es sorprendente que para el caso de Perú si sea posible consultar la información de todos los productos. Desconozco si la información a la que es posible acceder de manera pública sea consistente con la información relativa al contrato pago de la API. 
