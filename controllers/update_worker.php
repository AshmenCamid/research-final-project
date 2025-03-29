<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $worker_id = $_POST['worker_id'];
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];

    $sql = "UPDATE workers SET fullname = ?, role = ? WHERE worker_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $fullname, $role, $worker_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../pages/workers.php?success=updated");
        exit();
    } else {
        echo "Error updating record.";
    }
    mysqli_stmt_close($stmt);
}
?>
