<?php
include("../controllers/conn.php"); // Use centralized connection

// Purpose: This file allows the admin to add new workers to the system. 
// It generates a unique worker ID, sets default values, and inserts the worker into the database.

// Generate worker_id automatically
// Explanation: The worker ID is incremented based on the last ID in the database.
$result = $conn->query("SELECT MAX(worker_id) AS last_id FROM workers");
$row = $result->fetch_assoc();
$lastId = $row['last_id'] ?? "2025000";
$newWorkerId = intval($lastId) + 1;

// Default values for new workers
$defaultSchedule = "8 AM - 12 PM and 1 PM - 5 PM";
$defaultSalaryStatus = "Not Yet Receive";  // Default salary status for payroll

// Handle form submission
// Explanation: When the form is submitted, the worker's details are inserted into the `workers` and `payroll` tables.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];

    // Insert worker into `workers` table
    $sql_worker = "INSERT INTO workers (worker_id, fullname, role, schedule, status) 
                   VALUES ('$newWorkerId', '$fullname', '$role', '$defaultSchedule', 'enabled')";

    if ($conn->query($sql_worker) === TRUE) {
        // Insert into `payroll` table
        $sql_payroll = "INSERT INTO payroll (worker_id, fullname, role, salary_status) 
                        VALUES ('$newWorkerId', '$fullname', '$role', '$defaultSalaryStatus')";

        if ($conn->query($sql_payroll) === TRUE) {
            echo "<script>alert('Worker added successfully!'); window.location.href='workers.php';</script>";
        } else {
            echo "Error in Payroll: " . $conn->error;
        }
    } else {
        echo "Error in Worker: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Workers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        label {
            font-size: 16px; /* Adjusted font size */
            margin-bottom: 5px; /* Adjusted margin */
        }
        .back-button {
            text-decoration: none;
            font-size: 20px;
            color: green;
            display: inline-block;
            margin-bottom: 20px;
            padding: 5px 10px;
            border: 1px solid green;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .back-button:hover {
            background-color: green;
            color: white;
        }
        .form-container {
            flex: 1;
            max-width: 400px;
        }
        .form-container input[type="text"],
        .form-container input[type="submit"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .form-container input[type="submit"] {
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<nav class="sidebar navbar navbar-expand-lg custom-blue">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarNav" aria-controls="sidebarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="sidebarNav">
                <ul class="navbar-nav flex-column w-100">
                    <div class="sidebar-logo text-center w-100 my-3">
                        <img src="../assets/image/peso.png" class="img-fluid" alt="Company Logo" style="max-width: 100px;">
                    </div>
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="attendance.php"><i class="bi bi-calendar-check me-2"></i> Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="workers.php"><i class="bi bi-people-fill me-2"></i> Workers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="timeSheet.php"><i class="bi bi-clock-history me-2"></i> Time Sheet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                    </li>
                    <!-- Logout Button -->
                    <li class="nav-item mt-3">
                        <a class="nav-link text-white fw-bold" href="../controllers/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded shadow-sm bg-light">
            <h1 class="text-primary kanit">Workers</h1>
            <div class="fw-bold fs-5">
                <i class="bi bi-person"></i> <span id="userName">ADMIN</span>
            </div>
        </div>

        <div class="add-workers-container">
        <div class="d-flex align-items-center justify-content-between gap-5" style="border: 1px solid; padding: 20px; border-radius: 20px; border-color: rgb(194, 189, 189);">

            <!-- Form Section -->
            <form action="" method="post" class="form-container">
                <a href="workers.php" class="back-button">BACK</a>
                <br><br>

                <label for="fullname">FULL NAME:</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter full name" required>

                <label for="role">ENTER JOB ROLE:</label>
                <input type="text" id="role" name="role" placeholder="Enter job role" required>

                <input type="submit" id="submit" name="submit" value="Save">
            </form>

            <!-- Image Section -->
            <div style="flex: 1; text-align: center;">
                <img src="../assets/image/ADMIN__4_-removebg-preview.png" alt="Worker Image" width="800" height="500" style="border-radius: 10px; max-width: 100%; height: auto;">
            </div>

        </div>

        
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
