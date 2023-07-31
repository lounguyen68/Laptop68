<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


    include_once("../../models/AdminModel.php");
    $admin = new AdminModel();
    $data = json_decode(file_get_contents("php://input"));

    $admin->adminname = $data->adminname;
    $admin->role = 0;
    $admin->password = $data->password;
    $admin->first_name = $data->first_name;
    $admin->last_name = $data->last_name;
    $admin->phone = $data->phone;

    $stmt = $admin->getByAdminname($admin -> adminname);

    $row = $stmt -> rowCount();

    if($row > 0) {
        $admin_info = [
                    "status" => "fail",
                    "message" => "User already exists"
                ];

    }else {
        if(empty($data->adminname) || empty($data->password)){
            $admin_info = [
                "status" => "fail",
                "message" => "Invalid email or password"
            ];
        }
        else{
            if($admin->create()){
                $admin_info = [
                    "status" => "success",
                    "message" => "Created admin"
                ];
            } else {
                $admin_info = [
                    "status" => "fail",
                    "message" => "Failed to create new admin"
                ];
            }
        }
    }

    echo json_encode($admin_info);