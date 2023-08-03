<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");

include_once("../../config/config.php");
include_once("../../models/CustomerModel.php");

$user = new CustomerModel();

$data = json_decode(file_get_contents("php://input"));

$user -> username = $data -> username;
$user -> password = $data -> password;

$stmt = $user -> getPassword($user -> username);
$stmt2 = $user -> getStatus($user -> username);

if($stmt -> rowCount() <= 0) {
    $user_info = [
        "status" => "fail",
        "message" => "Username or password incorrect"
    ];
}else {
    $data2 = $stmt -> fetch();
    $password = $user -> password;
    $hashed_password = $data2['password'];

    if (password_verify($password, $hashed_password)) {
        $data3 = $stmt2 -> fetch();
        $status = $data3['status'];
        if($status) {
            $user_info = [
                "status" => "success",
                "message" => "Login successful",
                "id" => $data2['id']
            ];
        } else {
            $user_info = [
                "status" => "fail",
                "message" => "Account banned !",
            ];
        }
    } else {
        $user_info = [
            "status" => "fail",
            "message" => "Username or password incorrect"
        ];
    }
}


echo json_encode($user_info);