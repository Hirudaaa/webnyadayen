<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $new_status = trim($_POST['status'] ?? '');

    if (!$order_id || !$new_status) {
        echo "Invalid input.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Order #$order_id updated to '$new_status'.";
    } else {
        $_SESSION['flash_error'] = "Failed to update order.";
    }

    $stmt->close();
    header('Location: ../admin/orders.php');
    exit;
} else {
    http_response_code(405);
    echo "Method not allowed.";
    exit;
}
