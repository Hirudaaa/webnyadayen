<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic input validation (optional: add filter_var for email)
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: ../pages/login.php");
        exit;
    }

    // Prepare query to find user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // Check password (MD5 for demo, use password_hash in real apps)
        if ($user['password'] === md5($password)) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "Username or password is incorrect.";
        }
    } else {
        $_SESSION['error'] = "Username or password is incorrect.";
    }

    // Redirect back to login with error
    header("Location: ../pages/login.php");
    exit;
} else {
    // If accessed directly
    header("Location: ../pages/login.php");
    exit;
}
