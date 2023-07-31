<?php
    header("Access-Control-Allow-Origin: *");
    include_once("../../config/config.php");
    include_once("../../models/OrderModel.php");

    $order = new OrderModel();
    $order->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $order->getById($order->id);
    $stmt_pd = $order -> getProductbyId($order->id);
    

    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $products = $stmt_pd -> fetchAll(PDO::FETCH_ASSOC);
        $order_info = [
            "status" => "success",
            "data" => $data,
            "products" => $products
        ];
    } else {
        $order_info = [
            "status" => "fail",
            "message" => "No order found."
        ];
    }

    echo json_encode($order_info);
?>

