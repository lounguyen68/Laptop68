<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
    header("Access-Control-Max-Age: 86400");

        include_once("../../models/CategoryModel.php");

        $category = new CategoryModel();
        $data = json_decode(file_get_contents("php://input"));

        $category->admin_id = $data->admin_id;
        $category->name = $data->name;
        $category->type = $data->type;
        $category->des = $data->des;
        $category->status = $data->status;

        if($data->avatar) {
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
                // Nếu định dạng không được hỗ trợ, quăng lỗi
                throw new Exception("Unsupported image format: $mime_type");
            }

            $avatar_path = 'laptop68/assets/images/' . uniqid() . $extension;
            $category->avatar = "http://localhost/".$avatar_path;

        }else {
            $category->avatar = null;
        }

        if(empty($data->name)){
            $admin_info = [
                "status" => "success",
                "message" => "Name not empty !"
            ];
        } else {
            if($category->create()){
                if($data->avatar) {
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/'. $avatar_path, $avatar_data);
                }
                $admin_info = [
                    "status" => "success",
                    "message" => "Created new category"
                ];
            } else {
                $admin_info = [
                    "status" => "fail",
                    "message" => "Failed to create category"
                ];
            }
        }

    echo json_encode($admin_info);