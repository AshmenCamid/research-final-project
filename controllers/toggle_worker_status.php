<?php
include("conn.php"); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $worker_id = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($worker_id > 0 && ($action === 'enable' || $action === 'disable')) {
        $new_status = ($action === 'enable') ? 'enabled' : 'disabled';
        
        $query = "UPDATE workers SET status = ? WHERE worker_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $new_status, $worker_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => true, "message" => "Worker status updated."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update status."]);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($conn);
