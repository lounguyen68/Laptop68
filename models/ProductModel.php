<?php

include_once($_SERVER['DOCUMENT_ROOT']."/Laptop68/models/Model.php");

class ProductModel extends Model{
    public $id;
    public $admin_id;
    public $category_id;
    public $title;
    public $avatar;
    public $color;
    public $price;
    public $amount;
    public $summary;
    public $content;
    public $status;
    public $created_at;
    public $updated_at;
    public function __construct(){
        parent::__construct();
    }
    public function getAll($name, $price, $category, $status){
        $query = "SELECT * FROM products Where 1 = 1 ";
        if(isset($name)) {
            $query.= " AND title LIKE :name";
        }
        if(isset($price)) {
            $query.= " AND price >= :price";
        }
        if(isset($category)) {
            $query.= " AND category_id = :category_id";
        }
        if(isset($status)) {
            $query.= " AND status = :status";
        }
        
        $stmt = $this->conn->prepare($query);

        if(isset($name)) {
            $stmt -> bindValue(':name', "%$name%", PDO::PARAM_STR);
        }
        if(isset($price)) {
            $stmt -> bindParam(':price', $price);
        }
        if(isset($category)) {
            $stmt -> bindParam(':category_id', $category);
        }
        if(isset($status)) {
            $stmt -> bindParam(':status', $status);  
        }

        $stmt->execute();
        return $stmt;
    }  

public function Search($name, $l1, $l2, $sort){
        $query = "SELECT * FROM products Where 1 = 1 ";
        if(isset($name)) {
            $query.= " AND title LIKE :name";
        }
        if($l2 > 0) {
            $query.= " AND price BETWEEN :l1 AND :l2";
        }
        $query.= " AND status = 1";
        $query.= " AND category_id IN (SELECT id FROM categories WHERE status = 1)";
        if($sort == 1) {
            $query.= " ORDER BY price ASC";
        }else{
            $query.= " ORDER BY price DESC";
        }
       
            
        
        
        $stmt = $this->conn->prepare($query);

        if(isset($name)) {
            $stmt -> bindValue(':name', "%$name%", PDO::PARAM_STR);
        }
        if($l2 > 0) {
            $stmt -> bindParam(':l1', $l1);
            $stmt -> bindParam(':l2', $l2);
        }

        $stmt->execute();
        return $stmt;
    }  


    public function getById($id){
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function getByCategoryID($id){
        $query = "SELECT * FROM products WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function create(){
        $query = "INSERT INTO products SET
                admin_id = :admin_id,
                category_id = :category_id,
                title = :title,
                avatar = :avatar,
                color = :color,
                price = :price,
                amount = :amount,
                summary = :summary,
                content = :content,
                status = :status
            ";
        $stmt = $this->conn->prepare($query);
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->summary = htmlspecialchars(strip_tags($this->summary));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->status = htmlspecialchars(strip_tags($this->status));
     

        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':status', $this->status);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function update($id){
        $query = "UPDATE products SET
                category_id = :category_id,
                title = :title,
                avatar = :avatar,
                color = :color,
                price = :price,
                amount = :amount,
                summary = :summary,
                content = :content,
                status = :status,
                updated_at = :updated_at
                Where id=:id";
        $stmt = $this->conn->prepare($query);

        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->summary = htmlspecialchars(strip_tags($this->summary));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->updated_at =  htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function delete($id){
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    
}