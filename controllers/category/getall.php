<?php

    header("Access-Control-Allow-Origin: *");

    include_once("../../config/config.php");
    include_once("../../models/CategoryModel.php");

    $category = new CategoryModel();
    $s = $_GET['s'] != '' ? $_GET['s'] : null;
    $n = $_GET['n'] != '' ? $_GET['n'] : null;
    $stmt = $category->getAll($s, $n);
    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $category_info = [
            "status" => "success",
            "data" => $data,
            "n" => $n
        ];
    } else {
        $category_info = [
            "status" => "fail",
            "message" => "No category information available"
        ];
    }

    echo json_encode($category_info);
?>

