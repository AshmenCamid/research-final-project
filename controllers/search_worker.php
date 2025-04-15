<?php
include("conn.php"); // Use centralized connection

// Get worker_id from request
if (isset($_POST['worker_id'])) {
    $worker_id = $_POST['worker_id'];

    // Fetch worker details including status
    $sql = "SELECT fullname, status FROM workers WHERE worker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            "fullname" => $row['fullname'],
            "date" => date("F d, Y"), // Format: March 09, 2025
            "status" => $row['status'] // Include worker status
        ];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => "Worker not found"]);
    }

    $stmt->close();
}

$conn->close();
?>
