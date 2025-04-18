<?php
// layout.php — wrap other pages inside this layout
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'Admin' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            height: auto;
            background: #1f2937;
            color: #fff;
            padding-top: 1.5rem;
        }
        .sidebar a {
            color: #ccc;
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #374151;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3">
        <h4 class="text-white">Admin</h4>
        <a href="index.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="products.php" class="<?= $page === 'products' ? 'active' : '' ?>">🍔 Products</a>
        <a href="orders.php" class="<?= $page === 'orders' ? 'active' : '' ?>">📦 Orders</a>
        <a href="users.php" class="<?= $page === 'users' ? 'active' : '' ?>">👥 Users</a>
        <a href="../actions/logout.php">🚪 Logout</a>
    </div>
    <div class="flex-grow-1 p-4">
        <?= $content ?>
    </div>
</div>
<script>
    // Store previous value on focus
    function storePreviousStatus(selectElement) {
        selectElement.dataset.prevValue = selectElement.value;
    }

    // Confirm on change
    function confirmStatusChange(event, orderId) {
        const select = event.target;
        const newValue = select.value;
        const prevValue = select.dataset.prevValue;

        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: `Change status to "${newValue}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusForm' + orderId).submit();
            } else {
                // Revert to previous value
                select.value = prevValue;
            }
        });
    }
</script>
</body>
</html>
