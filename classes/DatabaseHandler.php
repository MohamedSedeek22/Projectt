<?php

class DatabaseHandler {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
        }
        public function insertProduct($sku, $name, $price, $product_type, $specific_attribute) {
            $sql = "INSERT INTO products (sku, name, price, product_type, specific_attribute) VALUES (?, ?, ?, ?, ?)";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("ssdss", $sku, $name, $price, $product_type, $specific_attribute);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
        


    public function deleteProducts($ids) {
        $idPlaceholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM products WHERE id IN ($idPlaceholders)";
        
        $stmt = $this->conn->prepare($sql);
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // Optionally, log error or throw exception
            return false;
        }
    }
    
    


    public function __destruct() {
        $this->conn->close();
    }
    }