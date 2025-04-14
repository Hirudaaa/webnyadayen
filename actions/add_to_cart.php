<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['product_id'])) {
    echo json_encode(['error' => 'No product selected.']);
    exit;
}

require_once '../config/db.php';

$product_id = (int) $_POST['product_id'];
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();

if (!$product) {
    echo json_encode(['error' => 'Product not found.']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'product_id' => $product_id, // 👈 this is important for placing orders later
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1,
        'image' => $product['image']
    ];
}

// Count total items
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['quantity'];
}

echo json_encode(['message' => '🛒 Added to cart!', 'cartCount' => $cartCount]);
?>
