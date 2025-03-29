<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "project_db"; // Change this to your actual database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
