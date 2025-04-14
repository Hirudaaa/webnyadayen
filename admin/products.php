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
    <div class="table-responsive">
    <table class="table table-hover table-borderless align-middle shadow-sm rounded">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th class="d-none d-md-table-cell">Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $index => $product): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td>
                    <img src="../assets/images/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="60" height="60" style="object-fit:cover; border-radius:6px;">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td class="text-success fw-semibold">$<?= number_format($product['price'], 2) ?></td>
                <td class="d-none d-md-table-cell text-muted small">
                    <?= strlen($product['description']) > 50 ? substr($product['description'], 0, 50) . '...' : $product['description'] ?>
                </td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                        ✏️ Edit
                    </a>
                    <form action="actions/delete_product.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            🗑️ Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

</body>
</html>
<?php
$content = ob_get_clean();
require 'layout.php';