<?php
session_start();
include 'conn.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $contact_number = trim($_POST['contact_number']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin'; // Automatically assign the "admin" role

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username already exists!'); window.location.href='signup.php';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, full_name, contact_number, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $fullname, $contact_number, $password, $role);
        
        if ($stmt->execute()) {
            echo "<script>alert('Admin registration successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed! Please try again.'); window.location.href='signup.php';</script>";
        }
    }
    
    $stmt->close();
    $conn->close();
}
?>
