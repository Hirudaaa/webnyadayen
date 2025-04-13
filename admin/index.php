<?php
session_start();
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

// Optional: check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { text-align: center; margin-bottom: 30px; }
        .admin-links { display: flex; justify-content: space-around; gap: 20px; }
        .admin-links a {
            display: block;
            text-align: center;
            background: #3498db;
            color: white;
            padding: 20px;
            text-decoration: none;
            border-radius: 8px;
            flex: 1;
            transition: 0.3s ease;
        }
        .admin-links a:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="admin-links">
            <a href="products.php">Manage Products</a>
            <a href="orders.php">View Orders</a>
            <a href="users.php">Manage Users</a>
        </div>
    </div>
</body>
</html>
