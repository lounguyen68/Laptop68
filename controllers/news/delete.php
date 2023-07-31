<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once("../../models/NewsModel.php");
    $news = new NewsModel();
    $news->id = isset($_GET['id']) ? $_GET['id'] : die();

    if($news->delete($news->id)){
        $news_info = [
            "status" => "success",
            "message" => "Deleted news"
        ];
    } else {
        $news_info = [
            "status" => "fail",
            "message" => "Failed to delete news"
        ];
    }
    echo json_encode($news_info);