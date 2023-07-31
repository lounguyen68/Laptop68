<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


    include_once("../../models/CustomerModel.php");

    $customer = new CustomerModel();
    $data = json_decode(file_get_contents("php://input"));
    $customer->id = $data->id;

    $old_stmt = $customer -> getById($data->id);
    $old_data = $old_stmt -> fetch();
    foreach($old_data as $field => $value) {
        $customer->$field = $value;
    }

    foreach($data as $field => $value) {
        $customer-> $field = $value;
    }

    if(isset($data->avatar)) {
        if(strpos($data->avatar, "data:image") === 0) {
                $avatar_base64 = $data->avatar;
                list($type, $avatar_data) = explode(';', $avatar_base64);
                list(, $avatar_data) = explode(',', $avatar_data);
                $avatar_data = base64_decode($avatar_data);

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($avatar_data);
                
                $extension = '.png';
                switch ($mime_type) {
                  case 'image/jpeg':
                    $extension = '.jpg';
                    break;
                  case 'image/png':
                    $extension = '.png';
                    break;
                  case 'image/gif':
                    $extension = '.gif';
                    break;
                  case 'image/webp':
                    $extension = '.webp';
                    break;
                  default:
                    // Nếu định dạng không được hỗ trợ, thả lỗi
                    throw new Exception("Unsupported image format: $mime_type");
                }

                $avatar_path = 'laptop68/assets/images/' . uniqid() . $extension;
                $customer->avatar = "http://localhost/".$avatar_path;

        }else {
            $customer->avatar = $data->avatar;
        }
    }


    if(!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)){
        $customer_info = [
            "status" => "fail",
            "message" => "Invalid email address"
        ];
    }else{
        if($customer->update($customer->id)){
            if(isset($data->avatar)) {
                if(strpos($data->avatar, "data:image") === 0) {
                file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/'. $avatar_path, $avatar_data);
            }
            }
            $customer_info = [
                "status" => "success",
                "message" => "Updated customer's information"
            ];
        } else {
            $customer_info = [
                "status" => "fail",
                "message" => "Failed to update customer's information"
            ];
        }
    }

echo json_encode($customer_info);