<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

$page = 'products';
$pageTitle = 'Add Product';

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <!-- Inter + Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Product</h2>
        <form action="../actions/add_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" name="price" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
            <a href="products.php" class="btn btn-secondary">Back to Products</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const preview = document.createElement('img');
    preview.style.maxHeight = '100px';
    preview.style.marginTop = '10px';

    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        e.target.parentElement.appendChild(preview);
    }
});
</script>

</body>
</html>
<?php
$content = ob_get_clean();
require 'layout.php';