<?php

    include_once("../../config/config.php");
    include_once("../../models/CustomerModel.php");

    $customer = new CustomerModel();
    $stmt = $customer->countUsers();

    $num = $stmt -> rowCount();

    if ($num > 0) {
        $data = $stmt -> fetch(PDO::FETCH_ASSOC);
        
        $customer_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $customer_info = [
            "status" => "fail",
            "message" => "No user found."
        ];
    }

    echo json_encode($customer_info);
?>

