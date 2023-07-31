<?php

    header("Access-Control-Allow-Origin: *");

    include_once("../../config/config.php");
    include_once("../../models/ProductModel.php");

    $product = new ProductModel();
    $product->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $product->getById($product->id);

    $num = $stmt -> rowCount();

    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $product_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $product_info = [
            "status" => "fail",
            "message" => "No product found."
        ];
    }

    echo json_encode($product_info);
?>

