<?php

include_once($_SERVER['DOCUMENT_ROOT']."/PhoneLand/models/Model.php");
class EventModel extends Model{
    public $id;
    public $admin_id;
    public $category_id;
    public $image_event;
    public $description;
    public function __construct(){
        parent::__construct();
    }
    public function getAll($c = null) {
        $query = "SELECT * FROM event";
        if (isset($c)) {
            $query .= " WHERE category_id = :category_id";
        }
        $query .= " ORDER BY category_id ASC";

        $stmt = $this->conn->prepare($query);

        if (isset($c)) {
            $stmt->bindParam(':category_id', $c, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }
    public function getByID($id){
        $query = "SELECT * FROM event WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function getByCategoryID($id){
        $query = "SELECT * FROM event WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function create(){
        $query = "INSERT INTO event SET
            admin_id = :admin_id,
            category_id = :category_id, 
            image_event = :image_event,
            description = :description
        ";
        $stmt = $this->conn->prepare($query);

        $this->admin_id =  htmlspecialchars(strip_tags($this->admin_id));
        $this->category_id =  htmlspecialchars(strip_tags($this->category_id));
        $this->image_event =  htmlspecialchars(strip_tags($this->image_event));
        $this->description =  htmlspecialchars(strip_tags($this->description));
        

        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':image_event', $this->image_event);
        $stmt->bindParam(':description', $this->description);
        
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function update($id){
        $query = "UPDATE event SET
            category_id = :category_id, 
            image_event = :image_event,
            description = :description
            where id = :id
        ";
        $stmt = $this->conn->prepare($query);

        $this->category_id =  htmlspecialchars(strip_tags($this->category_id));
        $this->image_event =  htmlspecialchars(strip_tags($this->image_event));
        $this->description =  htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(':image_event', $this->image_event);
        $stmt->bindParam(':description', $this->description);

        $stmt->execute();
        return $stmt;
    }
    public function delete($id){
        $query = "DELETE FROM event WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function search($query, $limit)
    {
        $sql = "SELECT * FROM event WHERE id LIKE :query LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

}