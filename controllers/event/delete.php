<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    include_once("../../models/EventModel.php");
    $event = new EventModel();
    $event->id = isset($_GET['id']) ? $_GET['id'] : die();

    if($event->delete($event->id)){
        $event_info = [
            "status" => "success",
            "message" => "Deleted event"
        ];
    } else {
        $event_info = [
            "status" => "fail",
            "message" => "Failed to delete event"
        ];
    }
    echo json_encode($event_info);