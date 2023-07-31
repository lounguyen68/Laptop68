<?php
    include_once("../../models/CustomerModel.php");
$customer = new CustomerModel();
$data = json_decode(file_get_contents("php://input"));

$customer->username = $data->username;
$customer->password = password_hash($data->password, PASSWORD_DEFAULT);
$customer->first_name = $data->first_name;
$customer->last_name = $data->last_name;
$customer->phone = $data->phone;
$customer->email = $data->email;
$customer->status = 1;
if(empty($data->username) || empty($data->password)){
    $customer_info = [
        "status" => "fail",
        "message" => "Invalid username or password"
    ];
}else if(!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)){
    $customer_info = [
        "status" => "fail",
        "message" => "Invalid email address"
    ];
}
else{
    if($customer->create()){
        $customer_info = [
            "status" => "success",
            "message" => "Created new customer"
        ];
    } else {
        $customer_info = [
            "status" => "fail",
            "message" => "Failed to create customer"
        ];
    }
}

echo json_encode($customer_info);

?>