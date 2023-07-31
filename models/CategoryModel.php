<?php

include_once($_SERVER['DOCUMENT_ROOT']."/PhoneLand/models/Model.php");

class CategoryModel extends Model{
    public $id;
    public $admin_id;
    public $name;
    public $type;
    public $avatar;
    public $des;
    public $status;
    public $created_at;
    public $updated_at;
    public function __construct(){
        parent::__construct();
    }
    public function getAll($status = null, $name = null){
        $query = "SELECT * FROM categories";
    if (isset($status)) {
        $query .= " WHERE status = :status";
        if (isset($name)) {
            $query .= " AND name LIKE :name";
        }
    } else {
        if (isset($name)) {
            $query .= " WHERE name LIKE :name";
        }
    }
    $stmt = $this->conn->prepare($query);
        if (isset($status)) {
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        }
        if (isset($name)) {
            $stmt->bindValue(':name', "%$name%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt;
    }    

    public function getById($id){
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function create(){
        $query = "INSERT INTO categories(admin_id, name, type, avatar, description, status)
        VALUES(:admin_id, :name, :type, :avatar, :description, :status)";
        $stmt = $this->conn->prepare($query);
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->des = htmlspecialchars(strip_tags($this->des));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->status = htmlspecialchars(strip_tags($this->status));
       

        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':description', $this->des);
        $stmt->bindParam(':status', $this->status);
        

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function update($id){
        $query = "UPDATE categories SET
                name = :name,  
                type = :type, 
                avatar = :avatar, 
                description = :description, 
                status = :status, 
                updated_at = :updated_at
                Where id=:id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->des = htmlspecialchars(strip_tags($this->des));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->updated_at =  htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':description', $this->des);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function delete($id){
        $query = "
        DELETE FROM products WHERE category_id =:id_cate;
        DELETE FROM news WHERE category_id =:id_cate;
        DELETE FROM event WHERE category_id =:id_cate;
        DELETE FROM categories WHERE id = :id_cate
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cate', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }
}