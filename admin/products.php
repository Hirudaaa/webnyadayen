<?php
session_start();
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #3498db; color: white; }
        a.button {
            padding: 6px 12px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        a.button:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .top-btns { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Products</h1>
        <div class="top-btns">
            <a href="add_product.php" class="button">+ Add Product</a>
            <a href="index.php" class="button" style="background:#34495e;">⬅ Dashboard</a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><img src="../assets/images/<?= $row['image'] ?>" width="50"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="button">Edit</a>
                    <a href="delete_product.php?id=<?= $row['id'] ?>" class="button btn-danger" onclick="return confirm('Are you sure to delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
