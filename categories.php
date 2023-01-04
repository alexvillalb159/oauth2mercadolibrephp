<?php


//curl -X GET -H 'Authorization: Bearer $ACCESS_TOKEN' https://api.mercadolibre.com/categories/MLA5725	
function getCategories($country_id) {
	$ch = curl_init();
	

	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/sites/'  . $country_id . '/categories');

	$headers = array(
		    "Authorization: Bearer " . $_COOKIE['access_token']
	);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


	$result = json_decode(curl_exec($ch)); 

	/*if (isset($result->error)) {
		error_log("La consulta de subcategorias no arrojo ningun resultado. Categoria: ".  $category_id, 0);
		error_log(	"Mensaje de error: {error: " . $result->error . ", " .
				"message: " . $result->message . ", " .
				"status: " . $result->status . "}" ); 

	} */


	curl_close($ch);
	return $result;

}

$result = getCategories($_GET['country_id'] );

echo json_encode($result);
