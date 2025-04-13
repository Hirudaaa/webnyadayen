<?php
// layout.php — wrap other pages inside this layout
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'Admin' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background: #1f2937;
            color: #fff;
            padding-top: 1.5rem;
        }
        .sidebar a {
            color: #ccc;
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #374151;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">
        <h4 class="text-white">Admin</h4>
        <a href="index.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="products.php" class="<?= $page === 'products' ? 'active' : '' ?>">🍔 Products</a>
        <a href="orders.php" class="<?= $page === 'orders' ? 'active' : '' ?>">📦 Orders</a>
        <a href="users.php" class="<?= $page === 'users' ? 'active' : '' ?>">👥 Users</a>
        <a href="../actions/logout.php">🚪 Logout</a>
    </div>
    <div class="flex-grow-1 p-4">
        <?= $content ?>
    </div>
</div>
</body>
</html>
