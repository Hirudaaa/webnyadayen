<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../admin/products.php");
    exit;
}

$id = intval($_GET['id']);

// Get image name to delete it too
$res = $conn->query("SELECT image FROM products WHERE id = $id");
if ($res && $res->num_rows > 0) {
    $img = $res->fetch_assoc()['image'];
    $conn->query("DELETE FROM products WHERE id = $id");
    @unlink("../assets/images/$img");
}

header("Location: ../admin/products.php?deleted=1");
exit;
