<?php

include 'product.php';

function get20BestSellingProducts($country_id, $category_id) {
	$result = get20BestSellingProductsID($country_id, $category_id); 

	if (isset($result->content)) {
		$products = $result->content;
		$i=0;
		foreach( $products as $product ) {
			$id = $product->id;
			$product_item= getProduct($id);	

			$product_items[$i] = $product_item;
			$i++;
		} 
		return $product_items;

	
	}
	error_log("La consulta de los 20 mas vendidos no arrojo ningun resultado. Pais: " . $country_id . ", Categoria: ".  $category_id, 0);
	error_log(	"Mensaje de error: {error: " . $result->error . ", " .
			"message: " . $result->message . ", " .
			"status: " . $result->status . "}" );
	return $result;

}

$result=get20BestSellingProducts($_GET['country_id'], $_GET['category_id'] );

echo json_encode($result);



?>
