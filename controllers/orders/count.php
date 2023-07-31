<?php

    include_once("../../config/config.php");
    include_once("../../models/OrderModel.php");

    $order = new OrderModel();
    
    $stmt = $order -> countPrice();
    

    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetch(PDO::FETCH_ASSOC);
        $order_info = [
            "status" => "success",
            "data" => $data,
        ];
    } else {
        $order_info = [
            "status" => "fail",
            "message" => "No order found."
        ];
    }

    echo json_encode($order_info);
?>

