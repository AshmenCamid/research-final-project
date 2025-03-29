<?php
session_start();
include("../controllers/conn.php");
date_default_timezone_set("Asia/Manila");

// Purpose: This file generates a report of workers, including their total worked hours, salary status, and last payment date.
// It also allows filtering by worker ID/name and date range.

// Get search keyword (worker ID or name) and date range from the request
$search_query = isset($_GET['search']) ? trim($_GET['search']) : "";
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : "";

// SQL query to get total worked hours for each worker (all-time total)
// Note: This query uses subqueries to fetch the last payment date and salary status for each worker.
$sql = "SELECT 
            w.worker_id, 
            w.fullname, 
            w.role, 
            COALESCE(SUM(TIMESTAMPDIFF(HOUR, a.time_in, a.time_out)), 0) AS total_hours,
            (SELECT payment_date FROM payroll WHERE worker_id = w.worker_id ORDER BY payment_date DESC LIMIT 1) AS last_payment_date,
            (SELECT salary_status FROM payroll WHERE worker_id = w.worker_id ORDER BY payment_date DESC LIMIT 1) AS salary_status
        FROM workers w
        LEFT JOIN attendance a ON w.worker_id = a.worker_id";

// Apply search and date range filters
// Explanation: Filters are dynamically added to the SQL query based on user input.
$conditions = [];
if (!empty($search_query)) {
    $conditions[] = "(w.worker_id LIKE '%$search_query%' OR w.fullname LIKE '%$search_query%')";
}
if (!empty($start_date) && !empty($end_date)) {
    $conditions[] = "(w.worker_id IN (SELECT worker_id FROM payroll WHERE payment_date BETWEEN '$start_date' AND '$end_date'))";
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY w.worker_id";

// Execute the query and fetch results
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .content { border: 1px solid rgb(204, 201, 201); padding: 20px; border-radius: 10px; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); } /* Enhance padding, add background color and box shadow */
        .search-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; } /* Enhance margin */
        .search-input { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #ccc; } /* Enhance padding */
        .add-work { text-decoration: none; font-size: 14px; padding: 10px 15px; border-radius: 10px; background-color: green; color: white; border: 1px solid green; } /* Enhance font size and padding */
        .content-table { border-collapse: collapse; width: 100%; font-size: 0.9em; } /* Enhance font size */
        .content-table th, .content-table td { border: 1px solid #ccc; padding: 10px; text-align: center; } /* Enhance padding */
        .content-table thead { background-color: #007bff; color: white; }
        .action-buttons { display: flex; justify-content: center; gap: 10px; } /* Enhance gap */
        .main-content { max-width: calc(100% - 200px); margin-left: 200px; margin-top: 20px; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); } /* Adjust this line */
        h1 { font-size: 1.5rem; } /* Enhance header size */
.filter-form { display: flex; gap: 10px; flex-wrap: wrap; } /* Enhance form layout */
        .filter-form .form-control, .filter-form .btn { flex: 1; min-width: 150px; } /* Enhance form control layout */
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
        <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded shadow-sm bg-light">
            <h1 class="text-primary kanit">Reports</h1>
            <div class="fw-bold fs-5">
                <i class="bi bi-person"></i> <span id="userName">ADMIN</span>
            </div>
        </div>

        <div class="content p-4 border rounded shadow-sm bg-light">
            <form method="GET" class="filter-form">
                <div class="col-md-3">
                    <label for="search" class="fw-bold text-primary">🔍 Search Worker:</label>
                    <input type="text" id="search" name="search" class="form-control border-primary rounded" 
                        placeholder="Enter Worker ID or Name" value="<?= $search_query; ?>">
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="fw-bold text-primary">📅 Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control border-primary" value="<?= $start_date; ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="fw-bold text-primary">📅 End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control border-primary" value="<?= $end_date; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form><br>

            <table class="table table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>WORKER ID</th>
                        <th>WORKER NAME</th>
                        <th>ROLE</th>
                        <th>REQUIRED HOURS</th>
                        <th>TOTAL WORKED HOURS</th>
                        <th>STATUS</th>
                        <th>SALARY STATUS</th>
                        <th>LAST PAYMENT DATE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $formatted_payment_date = (!empty($row['last_payment_date']) && $row['last_payment_date'] != '0000-00-00') 
                                ? date("F j, Y", strtotime($row['last_payment_date'])) 
                                : '';

                                $status = ($row['total_hours'] >= 160) ? "<span class='text-success fw-bold'>Complete</span>" : "<span class='text-danger fw-bold'>Incomplete</span>";

                            echo "<tr>
                                    <td>{$row['worker_id']}</td>
                                    <td>{$row['fullname']}</td>
                                    <td>{$row['role']}</td>
                                    <td>160 Hours</td>
                                    <td class='total-hours'>{$row['total_hours']} hrs</td>
                                    <td>{$status}</td>
                                    <td class='" . ($row['salary_status'] == "Received" ? "text-success fw-bold" : "text-danger fw-bold") . "'>
                                        {$row['salary_status']}
                                    </td>
                                    <td>{$formatted_payment_date}</td>
                                    <td><button class='btn btn-success btn-sm payout-btn' data-worker-id='{$row['worker_id']}'>💰 Payout</button></td>
                                  </tr>";
                        }
                    } else {
                        echo '<tr><td colspan="9" class="text-center text-danger">No records found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/reports.js"></script>

</body>
</html>
