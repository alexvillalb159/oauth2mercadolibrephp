<?php

include 'product.php';

function get20BestSellingProducts($country_id, $category_id) {
	$result = get20BestSellingProductsID($country_id, $category_id); 
	//echo "PRODUCTS: " . json_encode($result) . "----------------<br>"; 
	$products = $result->content;
	$i=0;
	foreach( $products as $product ) {
		$id = $product->id;
		$product_item= getProduct($id);	
		//echo "BARBARO: " . json_encode($product_item)  . " ------------- <br>"; 
		$product_items[$i] = $product_item;
		$i++;
	} 
	return $product_items;

}

$result=get20BestSellingProducts($_GET['country_id'], $_GET['category_id'] );

echo json_encode($result);

?>
