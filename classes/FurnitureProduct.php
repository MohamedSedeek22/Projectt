<?php
require_once 'Product.php';
require_once __DIR__ . '/../config/database.php';


class FurnitureProduct extends Product {
    private $height;
    private $width;
    private $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function setHeight($height) {
        $this->height = $height;
    }
        
    public function getHeight() {
        return $this->height;
    }
    public function setWidth($width) {
        $this->width = $width;
    }
        
    public function getWidth() {
        return $this->width;
    }
    public function setLength($length) { 
        $this->length = $length;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function display() {
        
    }
    public function save($productId){
        $stmt = $conn->prepare("INSERT INTO dvd (product_id, height,width,length) VALUES (?, ?, ?)");
        $stmt->bind_param("ii", $productId, $this->height,$this->width, $this->length);
    }
    
}

