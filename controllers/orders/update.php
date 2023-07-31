<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


    include_once("../../models/OrderModel.php");
    $order = new OrderModel();
    $data = json_decode(file_get_contents("php://input"));
    
    $order->id = $data->id;
    $order->fullname = $data->fullname;
    $order->address = $data->address;
    $order->mobile = $data->mobile;
    $order->email = $data->email;
    $order->note = $data->note;
    $order->price_total = $data->price_total;
    $order->payment_status = $data->payment_status;
    $order->updated_at = getdate(); 

    if(empty($data->fullname) || empty($data->email) || empty($data->mobile) || empty($data->address)){
        $admin_info = [
            "status" => "success",
            "message" => "Order's information is required"
        ];
    }else if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)){
        $admin_info = [
            "status" => "fail",
            "message" => "Invalid email address"
        ];
    }
    else{
        if($order->update($order->id)){
            $admin_info = [
                'status'=>'success',
                'message'=>"Updated order's information"
            ];

                
        }else{
            $admin_info = [
                'status'=>'success',
                'message'=>"Failed to update order's information"
            ];
        }
    }
    echo json_encode($admin_info);
