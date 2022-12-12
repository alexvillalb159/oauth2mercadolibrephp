<?php
/**
 * @author      Alexander Villalba <alexvillalb159@gmail.com>
 * @copyright   Alexander Villalba
 * @license     GNU
 * 
 * 
 */

// Documentación para autenticar en la API de mercado libre.
// https://developers.mercadolibre.com.ve/es_ar/autenticacion-y-autorizacion/

// Requisitos del programa para correr php-curl

?>

<html>
<head>

<?php

$URLAPP="";

$options = [
	'authSite' => '',
	'clientId' => '',
	'clientSecret' => '',
	'redirectUri' => ''
];

$authSites = array(
	"Argentina" =>			array( "code" =>"MLA", "url" =>"https://auth.mercadolibre.com.ar" ),
	"Brasil" => 			array( "code" =>"MLB", "url" =>"https://auth.mercadolivre.com.br" ),
	"Colombia" =>			array( "code" =>"MCB", "url" =>"https://auth.mercadolibre.com.co" ),
	"Costa Rica" =>			array( "code" =>"MCR", "url" =>"https://auth.mercadolibre.com.cr" ),
	"Ecuador" =>			array( "code" =>"MEC", "url" =>"https://auth.mercadolibre.com.ec" ), 
	"Chile" =>			array( "code" =>"MLC", "url" =>"https://auth.mercadolibre.cl" ), 
	"México" =>			array( "code" =>"MLM", "url" =>"https://auth.mercadolibre.com.mx" ), 
	"Panamá" =>			array( "code" =>"MPA", "url" =>"https://auth.mercadolibre.com.pa" ),
	"Perú" =>			array( "code" =>"MPE", "url" =>"https://auth.mercadolibre.com.pe" ),
	"Portugal" =>			array( "code" =>"MPT", "url" =>"https://auth.mercadolibre.com.pt" ), 
	"República Dominicana" =>	array( "code" =>"MRD", "url" =>"https://auth.mercadolibre.com.do" ),
	"Uruguay" =>			array( "code" =>"MLU", "url" =>"https://auth.mercadolibre.com.uy" ), 
	"Venezuela" => 			array( "code" =>"MLV", "url" =>"https://auth.mercadolibre.com.ve" )

);

/*
$paises = array_keys($authSites );
echo "País: " . $paises[0]. "<br>";
echo "Argentina: " . $authSites['Argentina']['code'] . "<br>"; */


function printJSON($result) {
	//header('Content-type: application/json; charset=UTF-8');
	$resultjson = json_encode(json_decode($result), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	echo "<label for='lview'>Título:</label>";
	echo "<textarea id='view' name='view' rows='12' cols='70'>";
	echo $resultjson ;
	echo "</textarea>";

}

function getCategories($access_token , $country_code) {

	$ch = curl_init();

	// Toma todas las categorias
	// curl -X GET -H 'Authorization: Bearer $ACCESS_TOKEN'   https://api.mercadolibre.com/sites/MLA/categories

	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/sites/' . $country_code . '/categories');
	$headers = array(
		    "Authorization: Bearer " . $access_token
	);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch); 
	
	$resultjson = json_decode($result);
	return $resultjson;
}



if(isset($_GET['allright'])) {

	setcookie("client_id", $_POST['client_id'], time() + (86400 * 30), "/");
	setcookie("client_secret", $_POST['client_secret'], time() + (86400 * 30), "/");
	setcookie("country", $_POST['country'], time() + (86400 * 30), "/");


	echo "<meta http-equiv='Refresh' content='0; url=\"" . $authSites[$_POST['country']]['url'] . "/authorization?response_type=code&client_id=" ;
	echo  $_POST['client_id'] . "&redirect_uri=" . $URLAPP . "/mercadolibre.php\"' />";

	echo "</head>"; 
	echo "</html>";
	
	exit("");
	
}
?>

<!--
<script type="text/javascript">
	var i = 10;


</script>
-->

<style>

	h2 {
		text-align: center;
	}

	body {
		
		margin-top:20px;
		margin-left: 100px;
		background-color: aquamarine;
	}

	form {
		
		display: grid;
		grid-template-columns: 200px 400px;
		margin-left: inherit;

	} 
	div {
	}	
	
	label {
		
		grid-column-start: 1;
  		grid-column-end: 2;
  		text-align: end;
  		margin-right: 5px;
	}	
	select {
		width:200px;
		margin-bottom: 6px;	
		grid-column-start: 2;
  		grid-column-end: 3;
		
	}
	input[type=text] {

		width: 300px;
		margin-bottom: 6px;
		grid-column-start: 2;
  		grid-column-end: 3;
		
	}
	.post {
		grid-column-start: 1;
  		grid-column-end: 3;
  		width: 150px;
  		margin: auto;
	
	}
	
</style>

</head>

<body>



<?php

if(isset($_GET['code'])) {
	
	$country_code =  $authSites[$_COOKIE['country']]['code'];

	$ch = curl_init();
	#$fp = fopen("/tmp/example_homepage.txt", "w");

	$data = array(	'grant_type' => 'authorization_code', 
			'client_id' => $_COOKIE['client_id'], 
			'client_secret' => $_COOKIE['client_secret'],
			'code' => ''.$_GET["code"] .'',
			'redirect_uri' => $URLAPP . '/mercadolibre.php' 
	//		'code_verifier' => '$CODE_VERIFIER' 
	);


	curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/oauth/token");
	curl_setopt($ch, CURLOPT_POST, TRUE);
	#curl_setopt($ch, CURLOPT_FILE, $fp);
	#curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	#curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	//echo "<br>Length: ". strlen(implode($data)) . "<br>";


	/*
	curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array(	"accept: application/json",
				"content-type: multipart/form-data",
				"content-length: " . strlen(implode($data))
			)
				
		); 
	*/
			

	$result = curl_exec($ch);

	if(curl_error($ch)) {
		#fwrite($fp, curl_error($ch));
		echo  curl_error($ch);
	}
	else {
		$resultjson = json_decode($result);
		echo "APP token: " . $resultjson->access_token . "<br>";  
	}


	echo "20 productos más vendidos de Computación en Argentina:<br>";


	// 20 productos más vendidos
	// curl -X GET -H 'Authorization: Bearer $ACCESS_TOKEN' https://api.mercadolibre.com/highlights/$SITE_ID/category/$CATEGORY_ID


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/highlights/MLA/category/MLA1648");

	$headers = array(
		    "Authorization: Bearer " . $resultjson->access_token 
	);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


	$result = json_decode(curl_exec($ch)); 


	if(isset($result->error)) {
		echo "<br>Error: " . $result->message . "<br>";
		//echo  curl_error($ch);
	} else {
		printJSON(json_encode($result));
	}

	curl_close($ch);


	
	echo "</body></html>";
	exit("");

}


?>



<h2>Mercado Libre API</h2>

<form action="mercadolibre.php?allright" method="post">
	<label for="fname">País:</label>
	<select name="country" id="country" required>
		<option value='' selected></option>
		<?php
			foreach( $authSites as $key => $val ) {
				echo "<option value='" .$key . "'>" .$key . "</option>"; 
			} 
		
		?>
	
	</select>

	<label for="fname">Client ID:</label>
	<input type="text" id="client_id" name="client_id" required><br>
	<label for="client_secret">Client Secret:</label>
	<input type="text" id="client_secret" name="client_secret" required>
	<input type='submit' class="post" name="post" value="Enviar">

</form> 

</body>
</html>
