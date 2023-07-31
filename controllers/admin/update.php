<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


include_once("../../models/AdminModel.php");
$admin = new AdminModel();
$data = json_decode(file_get_contents("php://input"));
$admin->id = $data->id;  

$admin->role = $data->role;
$admin->password = $data->password;
$admin->first_name = $data->first_name;
$admin->last_name = $data->last_name;
$admin->phone = $data->phone;
$admin->address = $data->address;
$admin->email = $data->email;
$admin->avatar = $data->avatar;
$admin->last_login =$data->last_login;
$admin->status = $data->status;
$admin->updated_at = $data->updated_at; 
if(!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)){
    $admin_info = [
        "status" => "fail",
        "message" => "Invalid email address"
    ];
}else{
    if($admin->update($admin->id)){
        $admin_info = [
            "status" => "success",
            "message" => "Updated admin's information"
        ];
    } else {
        $admin_info = [
            "status" => "fail",
            "message" => "Failed to update admin's information"
        ];
    }
}

echo json_encode($admin_info);