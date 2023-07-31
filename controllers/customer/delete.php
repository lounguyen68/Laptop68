<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once("../../models/CustomerModel.php");
    $customer = new CustomerModel();
    $customer->id = isset($_GET['id']) ? $_GET['id'] : die();

    if($customer->delete($customer->id)){
        $customer_info = [
            "status" => "success",
            "message" => "Deleted user"
        ];
    } else {
        $customer_info = [
            "status" => "fail",
            "message" => "Failed to delete user"
        ];
    }
    echo json_encode($customer_info);