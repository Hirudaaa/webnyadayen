<?php
require_once '../config/db.php';
require_once '../middlewares/admin_check.php';

$pageTitle = "Orders";

// Filter by status (optional)
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$query = "
    SELECT 
        o.id AS order_id,
        o.*, 
        oi.*, 
        u.name AS user_name, 
        p.name AS product_name, 
        p.price 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
";


if (in_array($filter, ['pending', 'approved', 'shipped'])) {
    $query .= " WHERE o.status = '$filter'";
}

$query .= " ORDER BY o.created_at DESC";

$result = $conn->query($query);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

ob_start();
?>

<h2 class="mb-4">Orders</h2>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-3">
    <?php foreach (['all', 'pending', 'approved', 'shipped'] as $status): ?>
        <li class="nav-item">
            <a class="nav-link <?= $filter === $status ? 'active' : '' ?>"
               href="?status=<?= $status ?>">
                <?= ucfirst($status) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if (count($orders) > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-borderless shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ordered At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $i => $order): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($order['user_name']) ?></td>
                        <td><?= htmlspecialchars($order['id']['product_name']) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td class="text-success fw-bold">
                            <?= number_format($order['price'] * $order['quantity'], 2) ?>
                        </td>
                        <td>
                        <form method="POST" action="../actions/update_orderstatus.php" class="d-inline" id="statusForm<?= $order['id'] ?>">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select 
                                name="status" 
                                class="form-select form-select-sm d-inline w-auto" 
                                id="statusSelect<?= $order['id'] ?>" 
                                onchange="confirmStatusChange(event, <?= $order['id'] ?>)" 
                                onfocus="storePreviousStatus(this)">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $order['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="canceled" <?= $order['status'] === 'canceled' ? 'selected' : '' ?>>Canceled</option>
                            </select>
                        </form>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No orders found.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>