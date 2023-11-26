<?php

require_once '../classes/Product.php';
require_once '../classes/DVDProduct.php';
require_once '../config/database.php';


// Check for unique SKU
function skuExists($conn, $sku) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE sku = ?");
    $stmt->bind_param("s", $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <script>
        // Function to show/hide fields based on product type
        function toggleFields() {
            var productType = document.getElementById("type").value;
            var dvdFields = document.getElementById("dvdFields");
            var bookFields = document.getElementById("bookFields");
            var furnitureFields = document.getElementById("furnitureFields");
            dvdFields.style.display = "none";
            bookFields.style.display = "none";
            furnitureFields.style.display = "none";
            if (productType === "dvd") {
                dvdFields.style.display = "block";
            } else if (productType === "book") {
                bookFields.style.display = "block";
            } else if (productType === "furniture") {
                furnitureFields.style.display = "block";
            }
        }

        // Validate form
        function validateForm() {
            var sku = document.getElementById("sku").value;
            var name = document.getElementById("name").value;
            var price = document.getElementById("price").value;
            var type = document.getElementById("type").value;
            var size = document.getElementById("size").value;
            var weight = document.getElementById("weight").value;
            var height = document.getElementById("height").value;
            var width = document.getElementById("width").value;
            var length = document.getElementById("length").value;
            var validationMessage = document.getElementById("validationMessage");
            validationMessage.innerHTML = "";
            var valid = true;

            if (!sku || !name || !price || type === "none") {
                validationMessage.innerHTML += "Please, submit required data.<br>";
                valid = false;
            }

            if (!/^[A-Za-z\s]+$/.test(name)) {
                validationMessage.innerHTML += "Name should only contain letters and spaces.<br>";
                valid = false;
            }

            if (isNaN(price) || price <= 0) {
                validationMessage.innerHTML += "Please, provide a valid price.<br>";
                valid = false;
            }

            if (type === "dvd" && (!size || isNaN(size) || size <= 0)) {
                validationMessage.innerHTML += "Please, provide a valid size (MB).<br>";
                valid = false;
            }

            if (type === "book" && (!weight || isNaN(weight) || weight <= 0)) {
                validationMessage.innerHTML += "Please, provide a valid weight (kg).<br>";
                valid = false;
            }

            if (type === "furniture" && (!height || !width || !length || isNaN(height) || isNaN(width) || isNaN(length) || height <= 0 || width <= 0 || length <= 0)) {
                validationMessage.innerHTML += "Please, provide valid dimensions (height, width, length).<br>";
                valid = false;
            }

            validationMessage.style.display = valid ? "none" : "block";
            return valid;
        }
    </script>
</head>
<body>
    <form action="add_product.php" method="post" onsubmit="return validateForm()">
        <div id="validationMessage" style="color: red;"></div>
        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" required><br><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required><br><br>
        <label for="type">Type:</label>
        <select id="type" name="type" onchange="toggleFields()" required>
            <option value="none">None</option>
            <option value="dvd">DVD</option>
            <option value="book">Book</option>
            <option value="furniture">Furniture</option>
        </select><br><br>
        
        <!-- DVD Fields -->
        <div id="dvdFields" style="display: none;">
            <label for="size">Size (MB):</label>
            <input type="text" id="size" name="size"><br><br>
        </div>
        
        <!-- Book Fields -->
        <div id="bookFields" style="display: none;">
            <label for="weight">Weight (kg):</label>
            <input type="text" id="weight" name="weight"><br><br>
        </div>
        
        <!-- Furniture Fields -->
        <div id="furnitureFields" style="display: none;">
            <label for="height">Height (cm):</label>
            <input type="text" id="height" name="height"><br><br>
            <label for="width">Width (cm):</label>
            <input type="text" id="width" name="width"><br><br>
            <label for="length">Length (cm):</label>
            <input type="text" id="length" name="length"><br><br>
        </div>

        <input type="submit" value="Save">
        <a href="index.php"><button type="button">Cancel</button></a>
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    


    if (skuExists($conn, $sku)) {
        echo "SKU already exists. Please use a unique SKU";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, product_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $sku, $name, $price, $type);
        $stmt->execute();
        $last_id = $conn->insert_id;

        switch ($type) {
            case "dvd":
                $size = $_POST['size'];

                $dvdObject= new DVDProduct($sku, $name, $price, $size);
                $dvdObject->save($last_id);
                break ;
            case "book":
                $weight = $_POST['weight'];
                $bookObject= new BookProduct($sku, $name, $price, $weight);
                $bookObject->save($last_id);
            
                break;
            case "furniture":
                $height = $_POST['height'];
                $width = $_POST['width'];
                $length = $_POST['length'];
                $frunitureObject= new FurnitureProduct($sku, $name, $price,$height, $width , $length);
                $frunitureObject->save($last_id);
                break;
        }
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href='index.php';</script>";
    }
}
$conn->close();
?>
