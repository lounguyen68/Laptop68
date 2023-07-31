<?php

include_once($_SERVER['DOCUMENT_ROOT']."/PhoneLand/models/Model.php");
class NewsModel extends Model{
    public $id;
    public $admin_id;
    public $category_id;
    public $name;
    public $summary;
    public $avatar;
    public $content;
    public $status;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $created_at;
    public $updated_at;
    public function __construct(){
        parent::__construct();
    }
    public function getAll($n, $c, $s){
        $query = "SELECT * FROM news Where 1=1";
        if (isset($c)) {
            $query .= " AND category_id = :category_id";
        }
        if (isset($n)) {
            $query .= " AND name LIKE :name";
        }
        if (isset($s)) {
            $query .= " AND status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if (isset($c)) {
            $stmt->bindParam(':category_id', $c, PDO::PARAM_INT);
        }
        if (isset($n)) {
            $stmt->bindValue(':name', "%$n%", PDO::PARAM_STR);
        }
        if (isset($s)) {
            $stmt->bindParam(':status', $s, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt;
    }
    public function getByID($id){
        $query = "SELECT * FROM news WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function getByCategoryID($id){
        $query = "SELECT * FROM news WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function create(){
        $query = "INSERT INTO news(admin_id, category_id, name, summary,avatar , content, status)
        Value(:admin_id, :category_id, :name, :summary, :avatar, :content, :status)";
        $stmt = $this->conn->prepare($query);

        $this->admin_id =  htmlspecialchars(strip_tags($this->admin_id));
        $this->category_id =  htmlspecialchars(strip_tags($this->category_id));
        $this->name =  htmlspecialchars(strip_tags($this->name));
        $this->summary =  htmlspecialchars(strip_tags($this->summary));
        $this->avatar =  htmlspecialchars(strip_tags($this->avatar));
        $this->content =  htmlspecialchars($this->content);
        $this->status =  htmlspecialchars(strip_tags($this->status));
        

        $stmt->bindParam(':admin_id', $this->admin_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':status', $this->status);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
    public function update($id){
        $query = "UPDATE news SET
            category_id = :category_id, 
            name = :name, 
            summary = :summary, 
            content = :content, 
            status = :status,
            avatar = :avatar,
            updated_at = :updated_at
            where id = :id
        ";
        $stmt = $this->conn->prepare($query);

        $this->category_id =  htmlspecialchars(strip_tags($this->category_id));
        $this->name =  htmlspecialchars(strip_tags($this->name));
        $this->summary =  htmlspecialchars(strip_tags($this->summary));
        $this->avatar =  htmlspecialchars(strip_tags($this->avatar));
        $this->content =  strip_tags($this->content);
        $this->status =  htmlspecialchars(strip_tags($this->status));
        $this->updated_at =  htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':summary', $this->summary);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function delete($id){
        $query = "DELETE FROM news WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function search($query, $limit)
    {
        $sql = "SELECT * FROM news WHERE name LIKE :query LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

}