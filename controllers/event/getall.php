<?php

    header("Access-Control-Allow-Origin: *");

    include_once("../../config/config.php");
    include_once("../../models/EventModel.php");

    $event = new EventModel();
    $c = $_GET['c'] != '' ? $_GET['c'] : null;
    $stmt = $event->getAll($c);
    $num = $stmt -> rowCount();
    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $event_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $event_info = [
            "status" => "fail",
            "message" => "No event found."
        ];
    }

    echo json_encode($event_info);
?>

