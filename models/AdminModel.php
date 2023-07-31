<?php

include_once($_SERVER['DOCUMENT_ROOT']."/Laptop68/models/Model.php");
class AdminModel extends Model{
    public $id;
    public $adminname;
    public $role;
    public $password;
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $email;
    public $avatar;
    public $last_login;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct();
    }
    public function getById($id){
        $query = "SELECT * FROM admins WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function getByAdminname($name){
        $query = "SELECT * FROM admins WHERE adminname = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

    public function countAdminname($name){
        $query = "SELECT count(*) as count FROM admins WHERE adminname = :adminname";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':adminname', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    
    public function create(){
        $query = "INSERT INTO admins(adminname, password, role,  first_name, last_name, phone)
VALUES(:adminname, :password, :role, :first_name, :last_name, :phone)";
        $stmt = $this->conn->prepare($query);
        $this->adminname =  htmlspecialchars(strip_tags($this->adminname));
        $this->role =       htmlspecialchars(strip_tags($this->role));
        $this->password =   password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
        $this->first_name =  htmlspecialchars(strip_tags($this->first_name));
        $this->last_name =  htmlspecialchars(strip_tags($this->last_name));
        $this->phone =      htmlspecialchars(strip_tags($this->phone));

        $stmt->bindParam(':adminname', $this->adminname);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function update($id){
        $query = "UPDATE admins SET
                password = :password,
                role = :role,  
                first_name = :first_name, 
                last_name = :last_name, 
                phone = :phone, 
                address = :address, 
                email = :email, 
                avatar = :avatar, 
                last_login = :last_login, 
                status = :status, 
                updated_at = :updated_at
                Where id=:id";

        $stmt = $this->conn->prepare($query);

        $this->role =       htmlspecialchars(strip_tags($this->role));
        $this->password =   password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);
        $this->first_name =  htmlspecialchars(strip_tags($this->first_name));
        $this->last_name =  htmlspecialchars(strip_tags($this->last_name));
        $this->phone =      htmlspecialchars(strip_tags($this->phone));
        $this->address =    htmlspecialchars(strip_tags($this->address));
        $this->email =      htmlspecialchars(strip_tags($this->email));
        $this->avatar =     htmlspecialchars(strip_tags($this->avatar));
        $this->last_login = htmlspecialchars(strip_tags($this->last_login));
        $this->status =     htmlspecialchars(strip_tags($this->status));
        $this->updated_at =  htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':last_login', $this->last_login);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function delete($id){
        $query = "DELETE FROM admins WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function search($query, $limit)
    {
        $sql = "SELECT * FROM admins WHERE adminname LIKE :query LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}