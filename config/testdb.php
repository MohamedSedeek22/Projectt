<?php

require_once '../config/database.php';

$products = $databaseHandler->getAllProducts();
echo "<pre>";
print_r($products);
echo "</pre>";
