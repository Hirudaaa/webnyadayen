<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

$productId = $_POST['product_id'];
$delta = (int) $_POST['delta'];

if (!isset($_SESSION['cart'][$productId])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

$_SESSION['cart'][$productId]['quantity'] += $delta;

if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
    unset($_SESSION['cart'][$productId]);
    echo json_encode(['success' => false, 'message' => 'Item removed from cart']);
    exit;
}

$quantity = $_SESSION['cart'][$productId]['quantity'];
$price = $_SESSION['cart'][$productId]['price'];
$subtotal = $quantity * $price;

// Recalculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

echo json_encode([
    'success' => true,
    'quantity' => $quantity,
    'subtotal' => $subtotal,
    'total' => $total
]);
