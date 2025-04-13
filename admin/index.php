<?php
require_once '../middlewares/admin_check.php';
require_once '../config/db.php';

$page = 'dashboard';
$pageTitle = 'Dashboard';

// Dashboard data
$totalProducts = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$pendingOrders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
$approvedOrders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'approved'")->fetch_assoc()['count'];
$shippedOrders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'shipped'")->fetch_assoc()['count'];

ob_start();
?>

<h2 class="mb-4">Dashboard</h2>
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <p class="display-6"><?= $totalProducts ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="display-6"><?= $totalUsers ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <p class="display-6"><?= $pendingOrders ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Approved</h5>
                <p class="display-6"><?= $approvedOrders ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Shipped</h5>
                <p class="display-6"><?= $shippedOrders ?></p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require 'layout.php';
