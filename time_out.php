<?php
// This script handles the Time Out functionality for workers.
// It validates the worker ID, checks if the worker has timed in, and updates the attendance record.

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila'); // Set timezone to Philippine time

include("controllers/conn.php"); // Use centralized connection

// Get worker ID from request
$worker_id = isset($_POST['worker_id']) ? trim($_POST['worker_id']) : '';

if (empty($worker_id)) {
    echo json_encode(["error" => "Worker ID is required."]);
    exit;
}

// Get current date and time in Philippine timezone
$date = date("Y-m-d");
$time_out = date("H:i:s");

// Check if the worker has timed in for the day
$check_sql = "SELECT * FROM attendance WHERE worker_id = ? AND date = ? AND time_in IS NOT NULL AND time_out IS NULL";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $worker_id, $date);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows == 0) {
    echo json_encode(["error" => "You have not timed in yet or have already timed out."]);
    $check_stmt->close();
    $conn->close();
    exit;
}
$check_stmt->close();

// Update time_out ONLY if time_in is NOT NULL
$sql = "UPDATE attendance SET time_out = ? WHERE worker_id = ? AND date = ? AND time_in IS NOT NULL";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sss", $time_out, $worker_id, $date);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => "Time Out recorded successfully.", "date" => $date, "time_out" => $time_out]);
        } else {
            echo json_encode(["error" => "No matching Time In found."]);
        }
    } else {
        echo json_encode(["error" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "SQL Error: " . $conn->error]);
}

$conn->close();
?>
