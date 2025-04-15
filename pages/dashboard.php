<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "project_db"; // Change this to your actual database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get date and period from AJAX request (default to today if not set)
$date = isset($_POST['date']) ? $_POST['date'] : date("Y-m-d");
$selectedPeriod = isset($_POST['period']) ? $_POST['period'] : '';

// Get total workers
$sql_total = "SELECT COUNT(*) AS totalWorkers FROM workers";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$totalWorkers = $row_total['totalWorkers'];

// Check if 'shift_period' column exists in the attendance table
$columnCheck = $conn->query("SHOW COLUMNS FROM attendance LIKE 'shift_period'");
$hasShiftPeriod = $columnCheck->num_rows > 0;

// Get currently in (workers who have checked in but have not timed out)
$sql_in = "SELECT COUNT(*) AS currentlyIn FROM attendance WHERE date = '$date' AND (time_out IS NULL OR time_out = '')";

if ($hasShiftPeriod && !empty($selectedPeriod)) {
    $sql_in .= " AND shift_period = '$selectedPeriod'";
}

$result_in = $conn->query($sql_in);
$row_in = $result_in->fetch_assoc();
$currentlyIn = ($row_in && isset($row_in['currentlyIn'])) ? $row_in['currentlyIn'] : 0;

// Calculate Off-Duty Workers (workers who have timed out)
$sql_offDuty = "SELECT COUNT(*) AS offDutyWorkers FROM attendance WHERE date = '$date' AND time_out IS NOT NULL AND time_out <> ''";

if ($hasShiftPeriod && !empty($selectedPeriod)) {
    $sql_offDuty .= " AND shift_period = '$selectedPeriod'";
}

$result_offDuty = $conn->query($sql_offDuty);
$row_offDuty = $result_offDuty->fetch_assoc();
$offDutyWorkers = ($row_offDuty && isset($row_offDuty['offDutyWorkers'])) ? $row_offDuty['offDutyWorkers'] : 0;

// Calculate Absent Workers (Workers who have NO attendance record for the selected day & period)
$sql_absent = "SELECT COUNT(*) AS absentWorkers FROM workers 
                WHERE id NOT IN (
                    SELECT worker_id FROM attendance WHERE date = '$date'";

if ($hasShiftPeriod && !empty($selectedPeriod)) {
    $sql_absent .= " AND shift_period = '$selectedPeriod'";
}

$sql_absent .= ")";

$result_absent = $conn->query($sql_absent);
$row_absent = $result_absent->fetch_assoc();
$absentWorkers = ($row_absent && isset($row_absent['absentWorkers'])) ? $row_absent['absentWorkers'] : 0;
// Calculate Absent Workers (Total Workers - (Currently In + Off-Duty Workers))
$absentWorkers = $totalWorkers - ($currentlyIn + $offDutyWorkers);


// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo json_encode([
        'totalWorkers' => $totalWorkers,
        'currentlyIn' => $currentlyIn,
        'offDutyWorkers' => $offDutyWorkers,
        'absentWorkers' => $absentWorkers
    ]);
    exit;
}

// Handle Worker Timeout (if worker clicks "Time Out" button)
if (isset($_POST['worker_id']) && isset($_POST['time_out'])) {
    $workerId = $_POST['worker_id'];
    $timeOut = date("H:i:s");

    $sql_timeOut = "UPDATE attendance SET time_out = '$timeOut' WHERE worker_id = '$workerId' AND date = '$date' AND (time_out IS NULL OR time_out = '')";
    $conn->query($sql_timeOut);

    echo json_encode(['success' => true]);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .content { border: 1px solid; padding: 20px 50px; border-radius: 20px; border-color: rgb(204, 201, 201); }
        td, th { border: 1px solid; padding: 12px 15px; text-align: center; }
        .content-table { border-collapse: collapse; margin: 25px 0; font-size: 1em; min-width: 400px; }
        .content-table thead tr { font-weight: bold; background-color: #f8f9fa; }
        .content { text-align: center; padding: 20px; }
        .chart-container { width: 80%; margin: 20px auto; }
        .card-title { font-size: 1.5rem; font-weight: bold; }
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
        <div class="d-flex justify-content-between align-items-center mb-3 border p-3 rounded shadow-sm bg-light">
            <div class="d-flex align-items-center">
            <h1 class="header-title">Dashboard</h1>
            </div>

            <div class="fw-bold fs-5">
                <i class="bi bi-person"></i> <span id="userName">ADMIN</span>
            </div>
        </div>
        
        <br>

        <!-- Dashboard Cards -->
        <div class="content">
            <div class="row">
                <!-- Filter Section -->
                <div class="d-flex align-items-center justify-content-center mb-4 p-3 bg-white rounded shadow-sm filter-section">
                    <label for="filterDate" class="me-2 fw-bold text-primary">📅 Select Date:</label>
                    <input type="date" id="filterDate" class="form-control form-control-sm me-3" style="width: 200px;">
                    
                    <label for="timePeriod" class="me-2 fw-bold text-primary">Select Period:</label>
                    <select id="timePeriod" class="form-select form-select-sm me-3 custom-dropdown" style="width: auto;">
                        <option value="">All</option>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>

                    <button id="filterButton" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card text-center p-3 shadow">
                        <h3>Total Workers</h3>
                        <h4><span id="total-workers"><?= $totalWorkers ?></span></h4>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card text-center p-3 shadow">
                        <h3>Currently IN:</h3>
                        <h4><span id="currently-in"><?= $currentlyIn ?></span></h4>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card text-center p-3 shadow">
                        <h3>Off-Duty Workers</h3>
                        <h4><span id="off-duty-workers"><?= $offDutyWorkers ?></span></h4>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card text-center p-3 shadow">
                        <h3>Absent Workers</h3>
                        <h4><span id="absent-workers"><?= $absentWorkers ?></span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
