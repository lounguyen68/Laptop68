<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once("../../config/config.php");
    include_once("../../models/NewsModel.php");

    $q = isset($_GET['q']) ? $_GET['q'] : "null";
    $l = isset($_GET['l']) ? $_GET['l'] : "null";
    if($q == null) {
    	$news = [
            "status" => "fail",
            "message" => "No user found."
        ];
    }
    else {
	    $model = new NewsModel();
	    $stmt = $model->search($q, $l);
	    $num = $stmt -> rowCount();
	    if ($num > 0) {
	        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	        $news = [
	            "status" => "success",
	            "data" => $data
	        ];
	    } else {
	        $news = [
	            "status" => "fail",
	            "message" => "No user found."
	        ];
	    }
    }
    echo json_encode($news);

?>