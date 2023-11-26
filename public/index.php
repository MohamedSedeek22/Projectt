<?php
session_start(); // Ensure session is started

// Check if there's a message set in the session
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Clear the message after displaying
}
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "products_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch products
function fetchProducts($conn) {
    $sql = "SELECT * FROM products ORDER BY id ASC"; // Modified to order by SKU
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<div class='card-content'>";
            echo "<input type='checkbox' class='delete-checkbox' name='delete_check' value='" . $row["id"] . "'>";
            echo "<p>SKU: " . $row["sku"] . "</p>";
            echo "<p>Name: " . $row["name"] . "</p>";
            echo "<p>Price: $" . number_format($row["price"], 2) . "</p>";
            
            // Check if the size key exists for DVDs
            if ($row["product_type"] === "dvd" && isset($row["size"])) {
                echo "<p>Size: " . $row["size"] . " MB</p>";
            }
            
            // Check if the weight key exists for books
            if ($row["product_type"] === "book" && isset($row["weight"])) {
                echo "<p>Weight: " . $row["weight"] . " KG</p>";
            }
            
            // Check if the dimensions key exists for furniture
            if ($row["product_type"] === "furniture" && isset($row["dimensions"])) {
                // Assuming dimensions are stored as 'HeightxWidthxLength'
                $dimensions = explode('x', $row["dimensions"]);
                if (count($dimensions) === 3) {
                    echo "<p>Dimensions: " . htmlspecialchars($dimensions[0]) . "x" . htmlspecialchars($dimensions[1]) . "x" . htmlspecialchars($dimensions[2]) . "</p>";
                } else {
                    echo "<p>Invalid dimensions format.</p>";
                }
            }
    
            echo "</div>"; // Close card-content div
            echo "</div>"; // Close card div
        }
    } else {
        echo "<p>No products found</p>";
    }
}

// Check if form submitted for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete'])) {
        $idsToDelete = implode(',', $_POST['delete']);
        $sql = "DELETE FROM products WHERE id IN ($idsToDelete)";
        if ($conn->query($sql) === TRUE) {
            echo "Products deleted successfully";
        } else {
            echo "Error deleting products: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">

    <title>Product List</title>
    <style>
    </style>
</head>
<body>
<div>
    <!-- Start of Product Operations -->
        <div class = "productList">  Product List 
        <form class="add" action="add_product.php" method="get" style="display: inline-block;">
            <input type="submit" value="Add Product">
        </form>
        <form class="delete" action="delete_products.php" method="post" style="display: inline-block;"> <!-- Modified action to index.php -->
            <input type="submit" name="delete" value="Mass Delete">
        </form>
        </div>

</div>
<div class="line"></div>
<!-- End of Product Operations -->
<!-- Start of Product List -->
<form action="delete_products.php" method="post">
    <div class="product-container">
        <?php fetchProducts($conn); ?>
    </div>
</form>

<?php $conn->close(); ?>
</body>
</html>
