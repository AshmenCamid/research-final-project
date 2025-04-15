<?php
// This script handles the Time In functionality for workers.
// It validates the worker ID and full name, checks if the worker has already timed in, and inserts a new attendance record.

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set("Asia/Manila"); // Set timezone

include("controllers/conn.php"); // Use centralized connection

// Get worker ID and full name
$worker_id = isset($_POST['worker_id']) ? trim($_POST['worker_id']) : '';
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';

if (empty($worker_id) || empty($fullname)) {
    echo json_encode(["error" => "Worker ID and Full Name are required."]);
    exit;
}

// Get current date and time
$date = date("Y-m-d");
$time_in = date("H:i:s");

// Check if the worker has already timed in for the day
$check_sql = "SELECT * FROM attendance WHERE worker_id = ? AND date = ? AND time_out IS NULL";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $worker_id, $date);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(["error" => "You have already timed in for today."]);
    $check_stmt->close();
    $conn->close();
    exit;
}
$check_stmt->close();

// Determine shift period (AM or PM)
$shift_period = (date("H") < 12) ? "AM" : "PM";

// Insert record into attendance table with shift_period
$sql = "INSERT INTO attendance (worker_id, fullname, date, time_in, shift_period, time_out) 
        VALUES (?, ?, ?, ?, ?, NULL)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssss", $worker_id, $fullname, $date, $time_in, $shift_period);
    if ($stmt->execute()) {
        echo json_encode(["success" => "Time In recorded successfully at " . $time_in . " (" . $shift_period . ")"]);
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
}

$conn->close();
?>
