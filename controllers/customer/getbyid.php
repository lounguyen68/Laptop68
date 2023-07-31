<?php

    include_once("../../config/config.php");
    include_once("../../models/CustomerModel.php");

    $customer = new CustomerModel();
    $customer->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $customer->getById($customer->id);
    $stmt_o = $customer ->  getOrder($customer -> id);

    $num = $stmt -> rowCount();

    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $orders = $stmt_o -> fetch(PDO::FETCH_ASSOC);
        $customer_info = [
            "status" => "success",
            "data" => $data,
            "orders" => $orders
        ];
    } else {
        $customer_info = [
            "status" => "fail",
            "message" => "No user found."
        ];
    }

    echo json_encode($customer_info);
?>

