

<?php
   header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    include_once "../../models/CategoryModel.php";

    $category = new CategoryModel();
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    $stmt = $category -> getById($category->id);

    if ($category->delete($category->id)) {
        $category_info = [
            "status" => "success",
            "message" => "Deleted category",
            "avatar" => $avatar_path
        ];
    } else {
        $category_info = [
            "status" => "fail",
            "message" => "Failed to delete category",
        ];
    }

echo json_encode($category_info);