<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = intval($_GET['id']);
$product = $conn->query("SELECT * FROM products WHERE id = $id LIMIT 1")->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Product</h2>
    <form action="../actions/edit_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="../assets/images/<?= $product['image'] ?>" height="100">
        </div>

        <div class="mb-3">
            <label class="form-label">Change Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>

        <button type="submit" name="update_product" class="btn btn-primary">Update</button>
        <a href="products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
