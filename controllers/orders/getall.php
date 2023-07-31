<?php
    header("Access-Control-Allow-Origin: *");

    include_once("../../config/config.php");
    include_once("../../models/OrderModel.php");

    $order = new OrderModel();
    $id = $_GET['id'] != '' ? $_GET['id'] : null;
    $t = $_GET['t'] != '' ? $_GET['t'] : null;
    $stmt = $order->getAll($id, $t);
    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $order_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $order_info = [
            "status" => "fail",
            "message" => "No order available"
        ];
    }

    echo json_encode($order_info);
?>

