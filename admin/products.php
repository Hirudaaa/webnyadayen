<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

$page = 'products';
$pageTitle = 'Manage Products';


// Check for success alert
$success = isset($_GET['success']) ? true : false;

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        img.thumbnail {
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Product List</h2>
    <a href="index.php" class="btn btn-outline-secondary mb-3">← Back to Dashboard</a>
    <?php if ($success): ?>
        <div class="alert alert-success">Product added successfully!</div>
    <?php endif; ?>
    <a href="add_product.php" class="btn btn-primary mb-3">+ Add New Product</a>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price ($)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><img src="../assets/images/<?= $row['image'] ?>" class="thumbnail" alt=""></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="../actions/delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
$content = ob_get_clean();
require 'layout.php';