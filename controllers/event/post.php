<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


    include_once("../../models/EventModel.php");
    $event = new EventModel();
    $data = json_decode(file_get_contents("php://input"));

    $event->admin_id = $data->admin_id;
    $event->category_id = $data->category_id;
    $event->description = $data->description;


    if($data->image_event) {
            $avatar_base64 = $data->image_event;
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
            $event->image_event = "http://localhost/".$avatar_path;

    }else {
        $event->image_event = null;
    }

    if(empty($data->admin_id) || empty($data->category_id)){
        $event_info = [
            "status" => "fail",
            "message" => "Admin ID and Category ID are required"
        ];
    }
    else{
        if($event->create()){
            if($data->image_event) {
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/'. $avatar_path, $avatar_data);
                }
            $event_info = [
                "status" => "success",
                "message" => "Created event"
            ];
        } else {
            $event_info = [
                "status" => "fail",
                "message" => "Failed to create event"
            ];
        }
    }

    echo json_encode($event_info);