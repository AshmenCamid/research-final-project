<?php
// Set default timezone dynamically
$timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : 'Asia/Manila';
date_default_timezone_set($timezone);

$conn = new mysqli("sql210.infinityfree.com", "if0_38751015", "vQYMafvBZESJ", "if0_38751015_project_db");

?>