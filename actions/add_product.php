<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

if (isset($_POST['add_product'])) {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);

    // Handle image upload
    $image       = $_FILES['image'];
    $targetDir   = "../assets/images/";
    $imageName   = time() . '_' . basename($image["name"]);
    $targetFile  = $targetDir . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($imageFileType, $allowedTypes)) {
        die("Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.");
    }

    if (move_uploaded_file($image["tmp_name"], $targetFile)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $imageName);

        if ($stmt->execute()) {
            header("Location: ../admin/products.php?success=1");
            exit;
        } else {
            echo "Database error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload image.";
    }
} else {
    header("Location: ../admin/add_product.php");
    exit;
}
