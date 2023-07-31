<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once("../../config/config.php");
    include_once("../../models/CategoryModel.php");

    $q = isset($_GET['q']) ? $_GET['q'] : "null";
    $l = isset($_GET['l']) ? $_GET['l'] : "null";
    if($q == null) {
    	$categories = [
            "status" => "fail",
            "message" => "No admin found."
        ];
    }
    else {
	    $model = new CategoryModel();
	    $stmt = $model->search($q, $l);
	    $num = $stmt -> rowCount();
	    if ($num > 0) {
	        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	        $categories = [
	            "status" => "success",
	            "data" => $data
	        ];
	    } else {
	        $categories = [
	            "status" => "fail",
	            "message" => "No category found."
	        ];
	    }
    }
    echo json_encode($categories);

?>