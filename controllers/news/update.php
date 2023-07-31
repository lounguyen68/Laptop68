<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Request-With");


    include_once("../../models/NewsModel.php");
    $news = new NewsModel();
    $data = json_decode(file_get_contents("php://input"));
    $news->id = $data->id;  

    $news->category_id = $data->category_id;
    $news->name = $data->name;
    $news->summary = $data->summary; 
    $news->content = $data->content;
    $news->status = $data->status;
    $news->updated_at = (new \DateTime())->format('Y-m-d H:i:s'); 

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
            $news->avatar = "http://localhost/".$avatar_path;

    }else {
        $news->avatar = $data->avatar;
    }

    if(empty($data->category_id) || empty($data->name)){
        
        $news_info = [
            "status" => "fail",
            "message" => "Category ID and Name are required"
        ];
    }{
        if($news->update($news->id)){
            if(strpos($data->avatar, "data:image") === 0) {
                file_put_contents($_SERVER['DOCUMENT_ROOT'] .'/'. $avatar_path, $avatar_data);
            }
            $news_info = [
                "status" => "success",
                "message" => "Updated news' information"
            ];
        } else {
            $news_info = [
                "status" => "fail",
                "message" => "Failed to update news' information"
            ];
        }
    }

    echo json_encode($news_info);