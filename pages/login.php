<?php
session_start();
include '../controllers/conn.php'; // Include database connection file

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to check user credentials
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            if ($role === "admin") {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                $_SESSION['role'] = $role;

                echo json_encode(["status" => "success", "message" => "Login successful!", "redirect" => "pages/dashboard.php"]);
                exit;
            } else {
                echo json_encode(["status" => "error", "message" => "Access Denied! Only admins can log in."]);
                exit;
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password!"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username not found!"]);
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
