


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

// Requisitos del programa para correr:  php-curl


if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
else  
         $url = "http://";   

$CurPageURL = $url . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$pos=strrpos($CurPageURL, '/');

$URLAPP=substr($CurPageURL, 0, $pos);




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
		    "Authorization: Bearer " . $_COOKIE['access_token']
	);
  
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch); 
	
	$resultjson = json_decode($result);
	return $resultjson;
}

function printHTMLSelectCountry() {
	global $authSites;
	
	echo '<label class="country" for="fname"><b>País:</b></label>';
	
	echo '<select name="country" id="country" required>';
	echo '	<option value="" selected></option>';

	foreach( $authSites as $key => $val ) {
		echo "<option value='" .$key . "'>" .$key . "</option>"; 
	} 
	echo '</select>';
}





?>

<!DOCTYPE html>
<html>
<head>
<title>API Mercado Libre</title>

<?php
if(isset($_GET['allright']))  {

	setcookie("client_id", $_POST['client_id'], time() + (86400 * 30), "/");
	setcookie("client_secret", $_POST['client_secret'], time() + (86400 * 30), "/");
	setcookie("country", $_POST['country'], time() + (86400 * 30), "/");




	echo "<meta http-equiv='Refresh' content='0; url=\"" . $authSites[$_POST['country']]['url'] . "/authorization?response_type=code&client_id=" ;
	echo  $_POST['client_id'] . "&redirect_uri=" . $URLAPP . "/mercadolibre.php\"' />";

	echo "</head>"; 
	echo "</html>";
	
	exit("");
	
}

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
		echo "</head>"; 
		echo "</html>";
		exit("");
	}

	$resultjson = json_decode($result);

	setcookie("access_token", $resultjson->access_token, time() + (86400 * 30), "/");
	curl_close($ch);

}


?>

<style>
	body {
		
		margin-top:20px;
		margin-left: 20px;
		margin-right:200px;
		background-color: aquamarine;
		display: grid;
		grid-template-columns: 500px 500px;
		/* grid-auto-rows: 35px; */
	}

	div.form {
		grid-column-start: 1;
		grid-column-end: 3;
		display: flex;
		align-items: center;
	}


	div.line {
		grid-column-start: 1;
		grid-column-end: 3;
		display: flex;
		align-items: left; 
	}

	div.error {
		grid-column-start: 1;
		grid-column-end: 3;
		display: flex;
		align-items: center;
		font-size: 20px;
		color: #fe0000;
		font-weight: bold;
	}

	div.titule2 {
		grid-column-start: 1;
		grid-column-end: 3;
		text-align: center;
		font-weight: bold;
	}

	h4 {
		text-align: center;
		grid-column-start: 1;
		grid-column-end: 3;
	}


	form.autenticacion {
		
		display: grid;
		grid-template-columns: 320px 320px;
		grid-column-start: 1;
		grid-column-end: 3;
		/*grid-auto-rows: 30px; */
		grid-row-start: 3;
		grid-row-end: 7;
		justify-content: center;

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
  		grid-column-end: 3;

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

<script type="text/javascript">

	var urlroot = window.location.origin + window.location.pathname.substring(0, window.location.pathname. lastIndexOf("/")); 
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


	
	function printProducts(products) {
		var table = document.getElementById("products");
		deleteTable(table);

		for(i=0; i < products.length; i++) {
			// Create an empty <tr> element and add it to the 1st position of the table:
			var row = table.insertRow(i+1);
			if(products[i] != null) {

				var position = row.insertCell(0);
				var id = row.insertCell(1);
				var title = row.insertCell(2);
				var price = row.insertCell(3);

				if( typeof(products[i].error) === "undefined") {
					// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
					
					position.innerHTML = "" + (i+1);
					id.innerHTML = products[i].id; 
					title.innerHTML = products[i].title; 
					price.innerHTML = products[i].price; 
				} else {
					position.innerHTML = "" + (i+1);
					//id.innerHTML = products[i].id; 
					title.innerHTML = products[i].message; 
					//price.innerHTML = products[i].price; 
				
				}
				
				continue;
			}

			var position = row.insertCell(0);
			var id = row.insertCell(1);
			position.innerHTML = "" + (i+1);
			if(products[i] != null) id.innerHTML = products[i].message; 
			else id.innerHTML = "Error";
			

		}
	
	}
	function deleteTable(table) {
		while(table.rows.length > 1 ) table.deleteRow(1);
	}

	function printSelectCategories(categories) {
		var filtros = document.getElementById('filtros');
		addfilter(filtros, categories);
		

	}


	function addfilter(filtros, categories) {
		var newhtml = 	'<select name="filtro" id="' + filtros.childNodes.length   + '"  readonly disabled>' +
			 	'	<option value="" selected></option>';

		for(i=0; i < categories.length; i++) {
			newhtml = newhtml + "<option value='"  + categories[i].id + "'>"  + categories[i].name + "</option>"; 
		} 
		newhtml += '</select>';
		
		filtros.insertAdjacentHTML("beforeend", newhtml);

		
		var country_code = country_codes[document.getElementById('country').value];
		
		filtros.childNodes[filtros.childNodes.length-1].value = country_code + "1648";
		updateProducts(); 
	
	
	}

	function removeAllfilters() {
		var filtros = document.getElementById('filtros');

		for(i=filtros.childNodes.length - 1; i >=2; i--) {
			filtros.removeChild(filtros.childNodes[i]);
		}
		
	
	}
	function updateProducts() {
		var categorie_id = getCategorieID();
		var country = document.getElementById('country').value;
		var diverror = document.getElementById('error');
		
		// Hace una solicitud sincrona
		let xhr = new XMLHttpRequest();

		xhr.open("GET", urlroot + "/20masvendidos.php?" + "country_id=" + country_codes[country] + "&category_id=" + 
				categorie_id, false);
		xhr.send(); 

		if (xhr.readyState === 4) {
     			//console.log(xhr.responseText);
			products = JSON.parse(xhr.responseText);
			if( typeof(products.error) != "undefined") {
				var table = document.getElementById("products");
				deleteTable(table);
				diverror.innerHTML = "Error: " + products.message;
				return;
			}
			diverror.innerHTML = "";
			printProducts(products);

		} 



	
	
	}
	function getCategorieID() {
		var size = document.getElementById('filtros').childNodes.length;
		var lastfilter = document.getElementById('filtros').childNodes[size-1];
		if(lastfilter.value == "") {
			lastfilter = document.getElementById('filtros').childNodes[size-2];
		}
		return lastfilter.value;
	}

	function countryOnChangeEvent()
	{
		removeAllfilters();
		var diverror = document.getElementById('error');
		diverror.innerHTML = "";
		window.esperar.showModal();
		var country = document.getElementById('country').value;
		
		
		// Borra la tabla de productos
		var table = document.getElementById("products");
		deleteTable(table);		

		// Quita el foco de la lista de países 
		//document.getElementById("country").blur();
		//document.getElementById("products").focus();;

		var categories = getCategories();
		printSelectCategories(categories); 
		window.esperar.close();

		
	}
	/*	
	function getCategories(country_code) {
	
		var country = document.getElementById('country').value;

		let xhr = new XMLHttpRequest();
		try {

			xhr.open("GET", 'https://api.mercadolibre.com/sites/'  + country_codes[country] + '/categories', false);
			xhr.setRequestHeader("Authorization",  "Bearer " + <php echo "'" .  $_COOKIE['access_token']  . "'"; ?>); 			
			xhr.send(); 
			if (xhr.readyState === 4) {
	     			//console.log(xhr.responseText);
				categories = JSON.parse(xhr.responseText);
			}
		} catch (err ) {
			console.log("Excepción: " + err);
		}
		return categories;
	}
	*/

	function getCategories() {
	
		var country = document.getElementById('country').value;

		let xhr = new XMLHttpRequest();
		try {

			xhr.open("GET", urlroot + "/categories.php?country_id=" +  country_codes[country] , false);

			xhr.send(); 
			if (xhr.readyState === 4) {
	     			//console.log(xhr.responseText);
				categories = JSON.parse(xhr.responseText);
			}
		} catch (err ) {
			console.log("Excepción: " + err);
		}
		return categories;
	}


	
</script>






</head>

<body>



<?php

if(isset($_GET['code'])) {
	/*	

	if(curl_error($ch)) {
		#fwrite($fp, curl_error($ch));
		echo  curl_error($ch);
		exit("");
	}
	else {
		$resultjson = json_decode($result);
	}

	setcookie("access_token", $resultjson->access_token, time() + (86400 * 30), "/");
	curl_close($ch);
	*/


	
	echo "<h4>Mercado Libre API</h4>";

	echo "
	<style>
		label {

	  		text-align: end;
	  		margin-top: 8px;
		}
			
		select {
			width:200px;
			margin-top: 5px;
			
		}
	</style>
	";


	echo "<div class='line' ><p><b>APP ID:</b> " .  $_COOKIE['client_id'] . "</p></div>";  
	echo "<div class='line' ><p><b>APP token:</b> " . $resultjson->access_token . "</p></div>";  
	echo "<div class='line' id='filtros'>";
	printHTMLSelectCountry();
	echo "</div>";

	
	echo 	"<script>
		 	document.getElementById('country').addEventListener('change', countryOnChangeEvent);
		 </script>";
	
	echo "<div class='titule2'>20 productos más vendidos:</div>";

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



	echo "<div class='error' id='error'>";
	echo "</div>";
	
	echo "</body></html>";
	exit("");

}


?>



<h4>Mercado Libre API</h4>

<form class="autenticacion" action="mercadolibre.php?allright" method="post">

	<label class="form" for="urlapp"><b>URL APP:</b></label>
	<textarea id="urlapp" placeholder='AYUDA' autocomplete="bday"  readonly></textarea>
	<!-- <input type="text" id="urlapp" name="client_id"> -->

	<?php 
		printHTMLSelectCountry();
	?>

	<label class="form" for="fname"><b>Client ID:</b></label>
	<input type="text" id="client_id" name="client_id"  required>
	<label class="form" for="client_secret"><b>Client Secret:</b></label>
	<input type="text" id="client_secret" name="client_secret" required>
	<div class="form">
		<input type='submit' class="post" name="post" value="Enviar">
	</div>
	<script>
		document.getElementById("urlapp").value = <?php echo "'" . $CurPageURL . "'"; ?> ;
	</script>
	
</form> 

</body>
</html>
