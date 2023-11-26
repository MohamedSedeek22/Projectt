<?php
require_once 'Product.php';
require_once __DIR__ . '/../config/database.php';

class BookProduct extends Product {
    private $weight; 

    public function __construct($sku, $name, $price, $weight) {
        $this->weight = $weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }
    
    public function getWeight() {
        return $this->weight;
    }
    public function save($productId){
        $stmt = $conn->prepare("INSERT INTO dvd (product_id, weight) VALUES (?, ?)");
        $stmt->bind_param("ii", $productId, $this->weight);
    }
}

