<?php
    header("Access-Control-Allow-Origin: *");
    
    include_once("../../config/config.php");
    include_once("../../models/NewsModel.php");

    $news = new NewsModel();

    $n = $_GET['n'] != '' ? $_GET['n'] : null;
    $c = $_GET['c'] != '' ? $_GET['c'] : null;
    $s = $_GET['s'] != '' ? $_GET['s'] : null;
    

    $stmt = $news->getAll($n, $c, $s);
    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $news_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $news_info = [
            "status" => "fail",
            "message" => "No news found."
        ];
    }

    echo json_encode($news_info);
?>

