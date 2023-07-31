<?php

    include_once("../../config/config.php");
    include_once("../../models/AdminModel.php");

    $admin = new AdminModel();
    $admin->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $admin->getById($admin->id);

    $num = $stmt -> rowCount();

    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $admin_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $admin_info = [
            "status" => "fail",
            "message" => "No user found."
        ];
    }

    echo json_encode($admin_info);
?>
