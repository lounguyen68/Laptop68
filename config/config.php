<?php
    class Database {
        private $hostname = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbname = 'laptop68';

        private $conn;

        public function connect() {
            $this -> conn = null;
            try {
                $this ->conn = new PDO('mysql:host='.$this->hostname.';dbname='.$this->dbname, 'root', $this->password);
                $this ->conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            }
            catch (PDOException $e) {
                http_response_code(500);
                echo "Error: ".$e->getMessage();
            }
            return $this-> conn;   
        }

    }
?>