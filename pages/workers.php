<?php
session_start();
include("../controllers/conn.php");

// Get search keyword
$search_query = isset($_GET['search']) ? trim($_GET['search']) : "";
$success_msg = isset($_GET['success']) ? $_GET['success'] : "";

// Pagination Settings
$records_per_page = isset($_GET['records']) ? (int)$_GET['records'] : 10; // Default 10 records
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default 1
$offset = ($page - 1) * $records_per_page;

// Get total number of records
$total_sql = "SELECT COUNT(*) AS total FROM workers 
              WHERE worker_id LIKE '%$search_query%' 
              OR fullname LIKE '%$search_query%' 
              OR role LIKE '%$search_query%'";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch paginated results
$sql = "SELECT * FROM workers 
        WHERE worker_id LIKE '%$search_query%' 
        OR fullname LIKE '%$search_query%' 
        OR role LIKE '%$search_query%'
        LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/image/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .content { border: 1px solid rgb(204, 201, 201); padding: 10px 25px; border-radius: 10px; background-color: #f8f9fa; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); } /* Reduce padding, add background color and box shadow */
        .search-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; } /* Reduce margin */
        .search-input { width: 40%; padding: 6px; border-radius: 10px; border: 1px solid #ccc; } /* Reduce padding */
        .add-work { text-decoration: none; font-size: 14px; padding: 6px 10px; border-radius: 10px; background-color: green; color: white; border: 1px solid green; } /* Reduce font size and padding */
        .content-table { border-collapse: collapse; width: 100%; font-size: 0.9em; } /* Reduce font size */
        .content-table th, .content-table td { border: 1px solid #ccc; padding: 6px; text-align: center; } /* Reduce padding */
        .content-table thead { background-color: #007bff; color: white; }
        .action-buttons { display: flex; justify-content: center; gap: 5px; } /* Reduce gap */
        .main-content { max-width: calc(100% - 200px); margin-left: 200px; margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); } /* Adjust this line */
        h1 { font-size: 1.5rem; } /* Reduce header size */
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
    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
        <h1 class="text-primary kanit">Workers</h1>
        <div class="fw-bold fs-5">
            <i class="bi bi-person"></i> <span id="userName">ADMIN</span>
        </div>
    </div>

    <!-- Success Message -->
    <?php if ($success_msg == "updated"): ?>
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Worker information updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="content">
        <div class="search-box">
            <form method="GET" class="w-100">
                <input type="text" name="search" class="search-input" placeholder="Search Worker ID, Name, or Role" value="<?= htmlspecialchars($search_query); ?>">
                <select name="records" id="records" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
            <option value="5" <?= $records_per_page == 5 ? 'selected' : '' ?>>5</option>
            <option value="10" <?= $records_per_page == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $records_per_page == 25 ? 'selected' : '' ?>>25</option>
        </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <a href="add-workers.php" class="add-work">Add_Worker</a>
        </div>

        <table class="content-table">
            <thead class="table-primary">
                <tr>
                    <th>WORKER ID</th>
                    <th>WORKER NAME</th>
                    <th>JOB ROLE</th>
                    <th>WORK SCHEDULE</th>
                    <th>TOTAL REQUIRED HOURS PER DAY</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_btn = ($row['status'] == 'enabled') 
                            ? '<button class="btn btn-danger btn-sm" onclick="toggleStatus('.$row['worker_id'].', \'disable\')">Disable</button>' 
                            : '<button class="btn btn-success btn-sm" onclick="toggleStatus('.$row['worker_id'].', \'enable\')">Enable</button>';
                        echo "<tr>
                            <td>{$row['worker_id']}</td>
                            <td>{$row['fullname']}</td>
                            <td>{$row['role']}</td>
                            <td>{$row['schedule']}</td>
                            <td>8</td>
                            <td class='action-buttons'> <!-- Add class here -->
                                <button class='btn btn-warning btn-sm edit-btn' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#editWorkerModal'
                                    data-worker-id='{$row['worker_id']}'
                                    data-fullname='" . htmlspecialchars($row['fullname'], ENT_QUOTES) . "'
                                    data-role='" . htmlspecialchars($row['role'], ENT_QUOTES) . "'>
                                    Edit
                                </button>
                                $status_btn
                            </td>
                        </tr>";
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center text-danger">No matching records found.</td></tr>';
                }
            ?>
            </tbody>
        </table>
        <!-- Pagination -->
    <div class="pagination-container">
        <nav>
            <ul class="pagination">
                <!-- Previous Button -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search_query); ?>&records=<?= $records_per_page; ?>&page=<?= ($page - 1); ?>">Previous</a>
                </li>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search_query); ?>&records=<?= $records_per_page; ?>&page=<?= $i; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search_query); ?>&records=<?= $records_per_page; ?>&page=<?= ($page + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
    </div>
</div>

<!-- Edit Worker Modal -->
<div class="modal fade" id="editWorkerModal" tabindex="-1" aria-labelledby="editWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Worker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editWorkerForm" method="POST" action="../controllers/update_worker.php">
                    <input type="hidden" id="worker_id" name="worker_id">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Worker Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Job Role</label>
                        <input type="text" class="form-control" id="role" name="role" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
function toggleStatus(workerId, action) {
    $.post('../controllers/toggle_worker_status.php', { worker_id: workerId, action: action }, function(response) {
        location.reload();
    });
}
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("worker_id").value = this.getAttribute("data-worker-id");
            document.getElementById("fullname").value = this.getAttribute("data-fullname");
            document.getElementById("role").value = this.getAttribute("data-role");
        });
    });
    // Auto-hide success alert after 3 seconds
    setTimeout(() => {
        const alertBox = document.getElementById("success-alert");
        if (alertBox) {
            alertBox.style.display = "none";
        }
    }, 3000);
});
</script>
</body>
</html>
