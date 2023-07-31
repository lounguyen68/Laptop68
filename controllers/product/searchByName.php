<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once("../../config/config.php");
    include_once("../../models/ProductModel.php");

    $q = isset($_GET['q']) ? $_GET['q'] : "null";
    $l = isset($_GET['l']) ? $_GET['l'] : "null";
    if($q == null) {
    	$products = [
            "status" => "fail",
            "message" => "No product found."
        ];
    }
    else {
	    $model = new ProductModel();
	    $stmt = $model->search($q, $l);
	    $num = $stmt -> rowCount();
	    if ($num > 0) {
	        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	        $products = [
	            "status" => "success",
	            "data" => $data
	        ];
	    } else {
	        $products = [
	            "status" => "fail",
	            "message" => "No product found."
	        ];
	    }
    }
    echo json_encode($products);

?>