<?php
session_start();
include("../controllers/conn.php");
date_default_timezone_set("Asia/Manila"); 

// Purpose: This file displays the timesheet of workers for a selected date and time period (AM/PM).
// It calculates the total worked hours for each worker based on their time-in and time-out.

$selected_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$time_period = isset($_GET['time_period']) ? $_GET['time_period'] : 'all';

// Modify SQL query to filter AM/PM if needed
// Explanation: The `time_condition` variable is used to filter records based on the selected time period.
$time_condition = "";
if ($time_period == 'am') {
    $time_condition = "AND a.time_in < '12:00:00'";
} elseif ($time_period == 'pm') {
    $time_condition = "AND a.time_in >= '12:00:00'";
}

$sql = "SELECT w.worker_id, w.fullname, w.role, a.time_in, a.time_out 
        FROM workers w 
        INNER JOIN attendance a ON w.worker_id = a.worker_id 
        AND a.date = '$selected_date' $time_condition";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Sheet</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .content { border: 1px solid; padding: 20px 50px; border-radius: 20px; border-color: rgb(204, 201, 201); }
        td, table tr th { border: 1px solid; padding: 12px 15px; }
        .add-work { text-decoration: none; font-size: 20px; margin-left: 80%; border: 1px solid; padding: 10px; border-radius: 20px; background-color: green; color: white; }
        .content-table { border-collapse: collapse; margin: 25px 0; font-size: 1em; min-width: 400px; }
        .content-table thead tr { text-align: left; font-weight: bold; }
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

    <div class="main-content container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3" style="border: 1px solid; padding: 7px; border-radius: 9px; border-color: rgb(197, 194, 194);">
            <h1 class="text-primary kanit">Time Sheet</h1>
        </div>

        <div class="content">
            <form method="GET" class="mb-3 p-3 bg-light rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <label for="date" class="me-2 fw-bold text-primary">📅 Select Date:</label>
                    <input type="date" id="date" name="date" class="form-control me-3 border-primary rounded" value="<?= $selected_date; ?>" style="max-width: 200px;">

                    <label for="time_period" class="me-2 fw-bold text-primary">⏳ Time Period:</label>
                    <select id="time_period" name="time_period" class="form-control me-3 border-primary rounded" style="max-width: 150px;">
                        <option value="all" <?= ($time_period == 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="am" <?= ($time_period == 'am') ? 'selected' : ''; ?>>AM</option>
                        <option value="pm" <?= ($time_period == 'pm') ? 'selected' : ''; ?>>PM</option>
                    </select>

                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>

            <table class="table table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>WORKERS ID</th>
                        <th>WORKERS NAME</th>
                        <th>ROLE</th>
                        <th>WORKED HOURS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $worker_id = $row['worker_id'];
                            $fullname = $row['fullname'];
                            $role = $row['role'];
                            $time_in = $row['time_in'];
                            $time_out = $row['time_out'];

                            if (is_null($time_in) || is_null($time_out)) {
                                echo "<tr>
                                        <td>$worker_id</td>
                                        <td>$fullname</td>
                                        <td>$role</td>
                                        <td class='text-center text-danger'>No Record</td>
                                      </tr>";
                            } else {
                                // Calculate total worked time
                                $start_time = strtotime($time_in);
                                $end_time = strtotime($time_out);
                                $worked_seconds = $end_time - $start_time;
                                $total_hours = floor($worked_seconds / 3600);
                                $total_minutes = floor(($worked_seconds % 3600) / 60);

                                echo "<tr>
                                        <td>$worker_id</td>
                                        <td>$fullname</td>
                                        <td>$role</td>
                                        <td>" . sprintf("%d hrs %d mins", $total_hours, $total_minutes) . "</td>
                                      </tr>";
                            }
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center text-danger">No records found for ' . $selected_date . '.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
