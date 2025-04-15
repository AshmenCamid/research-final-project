<?php
$host = "sql210.infinityfree.com";
$username = "if0_38751015";
$password = "vQYMafvBZESJ";
$database = "if0_38751015_project_db";

// Set default timezone dynamically
$timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : 'Asia/Manila';
date_default_timezone_set($timezone);

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
