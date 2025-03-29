<?php
// Purpose: This file provides an API endpoint to fetch worker statistics for the dashboard.
// It calculates the total workers, currently in workers, off-duty workers, and absent workers.

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila'); // Set timezone to Philippine time

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Get date and period from request
$date = isset($_POST['date']) ? trim($_POST['date']) : date("Y-m-d");
$period = isset($_POST['period']) ? trim($_POST['period']) : 'AM';

// Fetch total workers
$totalWorkersResult = $conn->query("SELECT COUNT(*) as totalWorkers FROM workers");
$totalWorkers = $totalWorkersResult->fetch_assoc()['totalWorkers'];

// Fetch currently in workers
// Explanation: Counts workers who are currently in based on the `time_out` field being NULL.
$currentlyInResult = $conn->query("SELECT COUNT(*) as currentlyIn FROM attendance WHERE date = '$date' AND shift_period = '$period' AND time_out IS NULL");
$currentlyIn = $currentlyInResult->fetch_assoc()['currentlyIn'];

// Fetch off-duty workers
$offDutyWorkersResult = $conn->query("SELECT COUNT(*) as offDutyWorkers FROM attendance WHERE date = '$date' AND shift_period = '$period' AND time_out IS NOT NULL");
$offDutyWorkers = $offDutyWorkersResult->fetch_assoc()['offDutyWorkers'];

// Calculate absent workers
$absentWorkers = $totalWorkers - $currentlyIn - $offDutyWorkers;

echo json_encode([
    "totalWorkers" => $totalWorkers,
    "currentlyIn" => $currentlyIn,
    "offDutyWorkers" => $offDutyWorkers,
    "absentWorkers" => $absentWorkers
]);

$conn->close();
?>
