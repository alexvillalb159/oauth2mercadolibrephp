<?php

function getProduct($product_id) {
	// curl -X GET  -H 'Authorization: Bearer $GLOBALS['ACCESSTOKEN']' https://api.mercadolibre.com/items/$ITEM_ID?include_attributes=all
	$ch = curl_init();	
	curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items/" . $product_id . "?include_attributes=all");	
	$headers = array(
		    "Authorization: Bearer " . $_COOKIE['access_token']
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


	$result = json_decode(curl_exec($ch)); 
	curl_close($ch);
	return $result;


}


function get20BestSellingProductsID($country_id, $category_id) {

	// 20 productos más vendidos
	// curl -X GET -H 'Authorization: Bearer $_COOKIE['access_token'] https://api.mercadolibre.com/highlights/$SITE_ID/category/$CATEGORY_ID


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/highlights/" . $country_id . "/category/" . $category_id);

	
	error_log("Consulta: ". "https://api.mercadolibre.com/highlights/" . $country_id . "/category/" . $category_id, 0);
	error_log("Cabecera: ". "Authorization: Bearer " . $_COOKIE['access_token'], 0);

	$headers = array(
		    "Authorization: Bearer " . $_COOKIE['access_token']
	);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


	$result = json_decode(curl_exec($ch)); 
	curl_close($ch);
	return $result;

}




?>
