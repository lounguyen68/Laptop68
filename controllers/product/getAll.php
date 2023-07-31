<?php
    header("Access-Control-Allow-Origin: *");

    include_once("../../config/config.php");
    include_once("../../models/ProductModel.php");

    $product = new ProductModel();
    $n = $_GET['n'] != '' ? $_GET['n'] : null;
    $p = $_GET['p'] != '' ? $_GET['p'] : null;
    $c = $_GET['c'] != '' ? $_GET['c'] : null;
    $s = $_GET['s'] != '' ? $_GET['s'] : null;

    $stmt = $product->getAll($n, $p, $c, $s);
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
            "message" => "No product available"
        ];
    }

    echo json_encode($product_info);
?>

