<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");

include_once("../../config/config.php");
include_once("../../models/AdminModel.php");

$admin = new AdminModel();

$data = json_decode(file_get_contents("php://input"));

$admin -> adminname = $data -> adminname;
$admin -> password = $data -> password;

$stmt = $admin->getByAdminname($admin -> adminname);

$row = $stmt -> rowCount();

if($row > 0) {

    $data = $stmt -> fetch(PDO::FETCH_ASSOC);

    $password = $admin -> password;

    $status = $data['status'];

    if($status == 1) {

    $hashed_password = $data['password'];

        if (password_verify($password, $hashed_password)) {
            $admin_info = [
                "status" => "success",
                "message" => "Login successful",
                "data" => $data
            ];
        } else {
            $admin_info = [
                "status" => "fail",
                "message" => "Invalid email or password",
            ];
        }
    }else {
        $admin_info = [
                "status" => "fail",
                "message" => "Account not active or has expired",
            ];
    }
}else {
    $admin_info = [
                "status" => "fail",
                "message" => "Username or password incorrect",
            ];
}

echo json_encode($admin_info);