<?php

include_once($_SERVER['DOCUMENT_ROOT']."/PhoneLand/models/Model.php");
class OrderModel extends Model{
    public $id;
    public $user_id;
    public $fullname;
    public $address;
    public $mobile;
    public $email;
    public $note;
    public $price_total;
    public $payment_status;
    public $created_at;
    public $updated_at;
    
    public function __construct(){
        parent::__construct();
    }
    public function getAll($id, $time) {
        $query = "SELECT * FROM orders WHERE 1=1 ";
        
        if (isset($id)) {
            $query .= " AND id = :id";
        }
        if (isset($time)) {
            if ($time == 1) {
                $query .= " AND DATE(created_at) = CURDATE()";
            } else if ($time == 2) {
                $query .= " AND YEARWEEK(created_at) = YEARWEEK(NOW())";
            } else if ($time == 3) {
                $query .= " AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
            } else if ($time == 4) {
                $query .= " AND YEAR(created_at) = YEAR(NOW())";
            }
        } else {
            // Thêm điều kiện mặc định nếu $time không tồn tại
            $query .= " AND YEAR(created_at) = YEAR(NOW())";
        }

            $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        if (isset($id)) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt;
    }
    public function getByID($id){
        $query = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function countPrice(){
        $query = "SELECT
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id ELSE NULL END) as today_order_count,
    COUNT(CASE WHEN DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN id ELSE NULL END) as yesterday_order_count,
    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN price_total ELSE 0 END) as today_total_amount,
    SUM(CASE WHEN DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN price_total ELSE 0 END) as yesterday_total_amount
FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getRecents() {
        $query = "SELECT orders.id, users.username, orders.created_at FROM orders INNER JOIN users ON orders.user_id = users.id  ORDER BY orders.created_at DESC LIMIT 7";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function getProductbyId($id) {
        $query = "SELECT * FROM orders INNER JOIN order_details ON orders.id = order_details.order_id INNER JOIN products ON order_details.product_id = products.id WHERE orders.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getByUserID($id){
        $query = "SELECT * FROM orders WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function createOrderDetail($order_id, $product_id, $product_price, $quantity) {
        $query = "INSERT INTO order_details (order_id, product_id, product_price, quantity) VALUES (:order_id, :product_id, :product_price, :quantity)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":product_price", $product_price);
        $stmt->bindParam(":quantity", $quantity);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function create(){
        $query = "INSERT INTO orders SET
            user_id = :user_id,
            fullname = :fullname, 
            address = :address,
            mobile = :mobile,
            email = :email,
            note = :note,
            price_total = :price_total
        ";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->price_total = htmlspecialchars(strip_tags($this->price_total));

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':mobile', $this->mobile);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':price_total', $this->price_total);
        
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    public function update($id){
        $query = "UPDATE orders SET
            user_id = :user_id,
            fullname = :fullname, 
            address = :address,
            mobile = :mobile,
            email = :email,
            note = :note,
            price_total = :price_total,
            payment_status = :payment_status,
            updated_at = :updated_at
            where id = :id
        ";
        $stmt = $this->conn->prepare($query);

        $this->user_id =  htmlspecialchars(strip_tags($this->user_id));
        $this->fullname =  htmlspecialchars(strip_tags($this->fullname));
        $this->address =  htmlspecialchars(strip_tags($this->address));
        $this->mobile =  htmlspecialchars(strip_tags($this->mobile));
        $this->email =  htmlspecialchars(strip_tags($this->email));
        $this->note =  htmlspecialchars(strip_tags($this->note));
        $this->price_total =  htmlspecialchars(strip_tags($this->price_total));
        $this->updated_at =  htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':mobile', $this->mobile);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':price_total', $this->price_total);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function delete($id){
        $query = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function search($query, $limit)
    {
        $sql = "SELECT * FROM orders WHERE id LIKE :query LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

}