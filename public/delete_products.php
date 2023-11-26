<?php

// Start the session at the beginning
require_once 'index.php';
require_once '../config/database.php'; // Path to your database.php file

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete'])) {
        $idsToDelete = $_POST['delete'];
        var_dump($idsToDelete);
        die('end');
        // Ensure $databaseHandler is correctly instantiated
        if (isset($databaseHandler)) {
            // Call the deleteProducts method from DatabaseHandler
            $result = $databaseHandler->deleteProducts($idsToDelete);

            if ($result) {
                // Deletion was successful
                $_SESSION['message'] = "Products deleted successfully";
            } else {
                // Deletion failed
                $_SESSION['message'] = "Error deleting products";
            }
        } else {
            // DatabaseHandler instance wasn't found
            $_SESSION['message'] = "Error: Database handler not initialized.";
        }
    }
    // Redirect back to the product list
    header("Location: index.php");
    exit();
}
?>
*/
if (isset($_POST['delete'])) {
    $id = $_POST['delete_check'];
    $extractId= implode(',' , $id);
    echo $extractId;
}

