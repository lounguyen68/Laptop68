<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once("../../config/config.php");
    include_once("../../models/AdminModel.php");

    $q = isset($_GET['q']) ? $_GET['q'] : "null";
    $l = isset($_GET['l']) ? $_GET['l'] : "null";
    if($q == null) {
    	$admins = [
            "status" => "fail",
            "message" => "No admin found."
        ];
    }
    else {
	    $model = new AdminModel();
	    $stmt = $model->search($q, $l);
	    $num = $stmt -> rowCount();
	    if ($num > 0) {
	        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	        $admins = [
	            "status" => "success",
	            "data" => $data
	        ];
	    } else {
	        $admins = [
	            "status" => "fail",
	            "message" => "No admin found."
	        ];
	    }
    }
    echo json_encode($admins);

?>