<?php
require_once 'Product.php';
require_once __DIR__ . '/../config/database.php';


class DVDProduct extends Product {
    private $size; 

    public function __construct($sku, $name, $price, $size) {
        $this->size = $size;
    }

    public function setSize($size) {
        $this->size = $size;
    }
    
    public function getSize() {
        return $this->size;
    }

    public function save($productId){
        $stmt = $conn->prepare("INSERT INTO dvd (product_id, size) VALUES (?, ?)");
        $stmt->bind_param("ii", $productId, $this->size);
    }
}
