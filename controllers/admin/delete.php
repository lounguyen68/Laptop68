<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once("../../models/AdminModel.php");
    $admin = new AdminModel();
    $admin->id = isset($_GET['id']) ? $_GET['id'] : die();

    if($admin->delete($admin->id)){
        $admin_info = [
            "status" => "success",
            "message" => "Deleted this admin"
        ];
    } else {
        $admin_info = [
            "status" => "fail",
            "message" => "Failed to delete this admin"
        ];
    }
    echo json_encode($admin_info);