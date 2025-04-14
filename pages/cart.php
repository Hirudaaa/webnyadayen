<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Food E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .cart-item {
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .cart-item img {
            max-width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            color: #888;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .qty-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-control button {
            width: 30px;
            height: 30px;
            font-size: 18px;
            padding: 0;
            line-height: 1;
        }

        .qty-value {
            min-width: 25px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">🛒 Your Shopping Cart</h2>

    <?php if (count($cart) === 0): ?>
        <div class="empty-cart">
            <h4>Your cart is empty 😕</h4>
            <p><a href="../index.php" class="btn btn-primary mt-3">Start Shopping</a></p>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($cart as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                    <div class="d-flex cart-item p-3 align-items-center" data-id="<?= $id ?>">
                        <img src="../assets/images/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="ms-3 flex-grow-1">
                            <h5><?= htmlspecialchars($item['name']) ?></h5>
                            <div class="qty-control my-2">
                                <button class="btn btn-outline-secondary btn-sm decrease">−</button>
                                <span class="qty-value"><?= $item['quantity'] ?></span>
                                <button class="btn btn-outline-secondary btn-sm increase">+</button>
                            </div>
                            <p class="mb-0 fw-semibold text-primary">$<?= number_format($item['price'], 2) ?> each</p>
                        </div>
                        <div class="text-end">
                            <p class="fw-bold text-success subtotal">$<?= number_format($subtotal, 2) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 class="mb-3">🧾 Order Summary</h5>
                    <p class="d-flex justify-content-between">
                        <span>Total Items:</span>
                        <span id="total-items"><?= count($cart) ?></span>
                    </p>
                    <p class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span id="total-amount">$<?= number_format($total, 2) ?></span>
                    </p>
                    <hr>
                    <a href="checkout.php" class="btn btn-success w-100">Checkout Now</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.cart-item').forEach(item => {
    const id = item.getAttribute('data-id');
    const qtyDisplay = item.querySelector('.qty-value');
    const subtotalElem = item.querySelector('.subtotal');

    item.querySelector('.increase').addEventListener('click', () => updateQty(id, 1));
    item.querySelector('.decrease').addEventListener('click', () => updateQty(id, -1));

    function updateQty(productId, delta) {
        fetch('../actions/update_cart_ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}&delta=${delta}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                qtyDisplay.textContent = data.quantity;
                subtotalElem.textContent = `$${parseFloat(data.subtotal).toFixed(2)}`;
                document.getElementById('total-amount').textContent = `$${parseFloat(data.total).toFixed(2)}`;
            } else {
                alert(data.message);
            }
        });
    }
});
</script>
</body>
</html>
