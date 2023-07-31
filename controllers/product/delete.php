<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once("../../models/ProductModel.php");
    $product = new ProductModel();
    $product->id = isset($_GET['id']) ? $_GET['id'] : die();

    if($product->delete($product->id)){
        $product_info = [
            "status" => "success",
            "message" => "Deleted product"
        ];
    } else {
        $product_info = [
            "status" => "fail",
            "message" => "Failed to delete product"
        ];
    }
    echo json_encode($product_info);