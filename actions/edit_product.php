<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

if (isset($_POST['update_product'])) {
    $id          = intval($_POST['id']);
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);

    // Fetch current image
    $result = $conn->query("SELECT image FROM products WHERE id = $id LIMIT 1");
    if (!$result || $result->num_rows === 0) {
        die("Product not found.");
    }

    $product     = $result->fetch_assoc();
    $imageName   = $product['image']; // default: keep old image

    // If a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image       = $_FILES['image'];
        $targetDir   = "../assets/images/";
        $newImage    = time() . '_' . basename($image["name"]);
        $targetFile  = $targetDir . $newImage;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowedTypes)) {
            die("Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.");
        }

        // Resize new image
        list($width, $height) = getimagesize($image["tmp_name"]);
        $new_width = 600;
        $new_height = intval($height * ($new_width / $width));

        $src = imagecreatefromstring(file_get_contents($image["tmp_name"]));
        $resized = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($resized, $targetFile, 90);
        imagedestroy($src);
        imagedestroy($resized);

        // Delete old image file
        if (file_exists($targetDir . $imageName)) {
            unlink($targetDir . $imageName);
        }

        $imageName = $newImage; // update image filename
    }

    // Update product
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $imageName, $id);

    if ($stmt->execute()) {
        header("Location: ../admin/products.php?updated=1");
        exit;
    } else {
        echo "Failed to update: " . $stmt->error;
    }
} else {
    header("Location: ../admin/products.php");
    exit;
}
