<?php
include("conn.php"); // Ensure database connection is included
date_default_timezone_set("Asia/Manila");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $worker_id = $_POST['worker_id'] ?? '';

    if (!empty($worker_id)) {
        $payment_date = date("Y-m-d H:i:s"); // Get current date and time

        // Fetch worker's name
        $worker_query = "SELECT fullname FROM workers WHERE worker_id = '$worker_id'";
        $worker_result = mysqli_query($conn, $worker_query);
        
        if ($worker_result && mysqli_num_rows($worker_result) > 0) {
            $worker_data = mysqli_fetch_assoc($worker_result);
            $worker_name = $worker_data['fullname'];

            // Update salary_status to "Received" and set payment_date
            $sql = "UPDATE payroll SET salary_status = 'Received', payment_date = '$payment_date' WHERE worker_id = '$worker_id'";

            if (mysqli_query($conn, $sql)) {
                echo "Salary status updated to 'Received' for Worker: $worker_name";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "Worker not found";
        }
    } else {
        echo "Invalid Worker ID";
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
