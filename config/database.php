<?php

require_once '../classes/DatabaseHandler.php';

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "products_db";


$databaseHandler = new DatabaseHandler($servername, $username, $password, $dbname);

// Check connection
