<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];
$name = $_SESSION['name'];

if (empty($cart)) {
    $_SESSION['message'] = "Your cart is empty.";
    header("Location: ../pages/cart.php");
    exit();
}


if (!$user) {
    $_SESSION['message'] = "User not found.";
    header("Location: ../pages/cart.php");
    exit();
}

// Insert into orders table
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
// Prepare the order insert query
$orderStmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())");
$orderStmt->bind_param("id", $user_id, $total); // i = int (user_id), d = double (total_price)
$orderStmt->execute();
$order_id = $orderStmt->insert_id;
$orderStmt->close();

// Prepare the order_items insert query
$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart as $product_id => $item) {
    $itemStmt->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
    $itemStmt->execute();
}
$itemStmt->close();

// Clear cart and redirect
unset($_SESSION['cart']);
$_SESSION['message'] = "Order placed successfully!";
header("Location: ../pages/cart.php");
exit();
?>
