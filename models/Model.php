<?php

    include_once("C:/xampp2/htdocs/Laptop68/config/config.php");

    class Model {
        public $conn;
        public function __construct() {
            $db = new Database();
            $this -> conn = $db -> connect();
        }
    }
?>