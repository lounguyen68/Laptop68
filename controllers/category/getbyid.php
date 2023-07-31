<?php
    header("Access-Control-Allow-Origin: *");
    
    include_once("../../config/config.php");
    include_once("../../models/CategoryModel.php");

    $category = new CategoryModel();
    $category->id = isset($_GET['id']) ? $_GET['id'] : "null";
    $stmt = $category->getById($category->id);

    $num = $stmt -> rowCount();

    if ($num > 0) {
        $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        $category_info = [
            "status" => "success",
            "data" => $data
        ];
    } else {
        $category_info = [
            "status" => "fail",
            "message" => "No category found."
        ];
    }

    echo json_encode($category_info);
?>

