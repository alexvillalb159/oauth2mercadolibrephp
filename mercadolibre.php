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

<!DOCTYPE html>
<html>
<head>
<title>API Mercado Libre</title>

<style>
	body {
		
		margin-top:20px;
		margin-left: 20px;
		background-color: aquamarine;
		display: grid;
		grid-template-columns: 900px 200px;
		justify-content: center;
		grid-auto-rows: 35px;
	}


	div.line {
		grid-column-start: 1;
		grid-column-end: 3;
		display: flex;
		align-items: center;
	}

	div.titule2 {
		grid-column-start: 1;
		grid-column-end: 2;
		text-align: center;
		font-weight: bold;
	}

	h4 {
		text-align: center;
		grid-column-start: 1;
		grid-column-end: 2;
	}


	form {
		
		display: grid;
		grid-template-columns: 320px 320px;
		grid-column-start: 1;
		grid-column-end: 2;
		grid-auto-rows: 30px;
		grid-row-start: 3

	} 
	
	
	label {

  		text-align: end;
  		margin-right: 5px;
	}	
	select {
		width:200px;
		margin-bottom: 6px;	
		
	}
	input[type=text] {

		width: 300px;
		margin-bottom: 6px;
		
	}
	input.post {
		grid-column-start: 1;
  		grid-column-end: 2;
  		width: 150px;
  		margin: auto;
	
	}
	
	table.products {
		display: table;
		width: 700px;
		border: 1px solid black;
		border-radius: 10px;
		grid-column-start: 1;
  		grid-column-end: 2;

	}
	table.products th {
		border: 1px solid black;
		/* border-radius: 10px; */

	}
	
	table.products td {
		border: 1px solid black;
		/* border-radius: 10px; */

	}
	
</style>

<script>
	var urlroot = window.location.origin; 
	var country_codes = {
		"Argentina": "MLA", 
		"Brasil": "MLB", 
		"Colombia": "MCB", 
		"Costa Rica": "MCR", 
		"Ecuador": "MEC",
		"Chile": "MLC", 
		"México": "MLM", 
		"Panamá": "MPA", 
		"Perú": "MPE", 
		"Portugal": "MPT", 
		"República Dominicana": "MRD",
		"Uruguay": "MLU", 
		"Venezuela": "MLV"
	};

	function countryOnChangeEvent()
	{
		window.esperar.showModal();

		var selectedValue = document.getElementById('country').value;
		// Hace una solicitud sincrona
		let xhr = new XMLHttpRequest();
		try {
			xhr.open("GET", urlroot + "/20masvendidos.php?" + "country_id=" + country_codes[selectedValue] + "&category_id=" + 
				country_codes[selectedValue] + "1648", false);
 



			xhr.send(); 

			if (xhr.readyState === 4) {
	     			console.log(xhr.responseText);
				products = JSON.parse(xhr.responseText);
				printProducts(products);

			}
		} catch (err ) {
			console.log("Exceción: " + err);
		}
		
		window.esperar.close();
		// Quita el foco de la lista de países 
		document.getElementById("country").blur();
		//document.getElementById("products").focus();;

		
	}
	
	function printProducts(products) {
		var table = document.getElementById("products");
		deleteTable(table);

		for(i=0; i < products.length; i++) {
			// Create an empty <tr> element and add it to the 1st position of the table:
			var row = table.insertRow(i+1);

			if( typeof(products[i].error) === "undefined") {
				// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
				var position = row.insertCell(0);
				var id = row.insertCell(1);
				var title = row.insertCell(2);
				var price = row.insertCell(3);
				
				position.innerHTML = "" + (i+1);
				id.innerHTML = products[i].id; 
				title.innerHTML = products[i].title; 
				price.innerHTML = products[i].price; 
			} else {
				var position = row.insertCell(0);
				var id = row.insertCell(1);
				position.innerHTML = "" + (i+1);
				id.innerHTML = products[i].message; 
			
			}
		}
	
	}
	
	function deleteTable(table) {
		while(table.rows.length > 1 ) table.deleteRow(1);
	}

</script>


<?php




$URLAPP="";


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




function printJSON($result) {
	//header('Content-type: application/json; charset=UTF-8');
	$resultjson = json_encode(json_decode($result), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	echo "<label for='lview'>Título:</label>";
	echo "<textarea id='view' name='view' rows='12' cols='70'>";
	echo $resultjson ;
	echo "</textarea>";

}

function printProducts ($products ) {

	echo 	"<table border='1'>";
	echo 	"<tr>" . 
		"<th>Posicion</th>" . 
		"<th>ID</th>" . 
		"<th>Título</th>" . 
		"<th>Precio</th>" . 
		"</tr>"; 
	

	$i=1;
	foreach($products as $product) {
		if(!isset($product->error)) {
			echo "<tr>"; 
			echo "<td>" . $i . "</td>"; 
			echo "<td>" . $product->id . "</td>"; 
			echo  "<td>" . $product->title . "</td>";
			echo "<td>" . $product->price . "</td>"; 
			echo "</tr>";
		} else {
			echo "<tr>"; 
			echo "<td>" . $i . "</td>";
			echo "<td>" .  $product->message . "</td>";
			echo "<td></td>";
			echo "<td></td>";
			//echo "<td>" . "Error: producto no exhibible" . "</td>"; 
			echo "</tr>";
		}
		$i++;
	}
	echo "</table>";

	
}






function getCategories($country_code) {

	$ch = curl_init();

	// Toma todas las categorias
	// curl -X GET -H 'Authorization: Bearer ['ACCESSTOKEN']'   https://api.mercadolibre.com/sites/MLA/categories

	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/sites/' . $country_code . '/categories');
	$headers = array(
		    "Authorization: Bearer " . $GLOBALS['ACCESSTOKEN']
	);
  
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch); 
	
	$resultjson = json_decode($result);
	return $resultjson;
}

function printHTMLSelectCountry() {
	global $authSites;
	
	echo '<label for="fname"><b>País:</b></label>';
	
	echo '<select name="country" id="country" required>';
	echo '	<option value="" selected></option>';

	foreach( $authSites as $key => $val ) {
		echo "<option value='" .$key . "'>" .$key . "</option>"; 
	} 
	echo '</select>';
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

	);


	curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/oauth/token");
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	#curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);





			

	$result = curl_exec($ch);

	if(curl_error($ch)) {
		#fwrite($fp, curl_error($ch));
		echo  curl_error($ch);
	}
	else {
		$resultjson = json_decode($result);

		setcookie("access_token", $resultjson->access_token, time() + (86400 * 30), "/");

	}

	curl_close($ch);
	
	echo "<h4>Mercado Libre API</h4>";  
	echo "<div class='line' ><p><b>APP ID:</b> " .  $_COOKIE['client_id'] . "</p></div>";  
	echo "<div class='line' ><p><b>APP token:</b> " . $resultjson->access_token . "</p></div>";  
	echo "<div class='line'>";
	printHTMLSelectCountry();
	echo "</div>";

	
	echo "<script>";
	echo "document.getElementById('country').addEventListener('change', countryOnChangeEvent);"; 
	echo "</script>";
	
	echo "<div class='titule2'>20 productos más vendidos de Computación:</div>";

	echo 	"<table class='products' id='products'>";
	echo 	"<tr>" . 
		"<th>Posicion</th>" . 
		"<th>ID</th>" . 
		"<th>Título</th>" . 
		"<th>Precio</th>" . 
		"</tr>"; 
	echo 	"</table>";

	echo 	"<dialog id='esperar'>" .
	      	"<h2>Espere</h2>" .
		"</dialog>";


//	$products = get20BestSellingProducts("MLA", "MLA1648");
//	printProducts ($products );
	
	//echo "Nombre: " . $products[0]['title'];
/*
	if(isset($result->error)) {
		echo "Error: " . $result->message ;
		//echo  curl_error($ch);
	} else {
		printJSON(json_encode($result));
	}

*/



	
	echo "</body></html>";
	exit("");

}


?>



<h4>Mercado Libre API</h4>

<form action="mercadolibre.php?allright" method="post">
	<?php 
		printHTMLSelectCountry();
	?>

	<label for="fname"><b>Client ID:</b></label>
	<input type="text" id="client_id" name="client_id" required>
	<label for="client_secret"><b>Client Secret:</b></label>
	<input type="text" id="client_secret" name="client_secret" required>
	<div class="line">
		<input type='submit' class="post" name="post" value="Enviar">
	</div>

</form> 

</body>
</html>
