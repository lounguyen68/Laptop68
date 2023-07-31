<?php

    include_once("../../config/config.php");
    include_once("../../models/EventModel.php");

    $event = new EventModel();
    $event->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $event->getById($event->id);

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

