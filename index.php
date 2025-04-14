<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/db.php';

$username = $_SESSION['name'] ?? null;
$role = $_SESSION['role'] ?? null;

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total products
$countResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch products
$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food E-Commerce - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .dropdown-user {
            position: relative;
            display: inline-block;
        }

        .dropdown-user .dropdown-menu {
            display: none;
            position: absolute;
            top: 110%;
            right: 0;
            z-index: 1000;
            min-width: 180px;
            padding: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.2s ease-in-out;
        }

        .dropdown-user:hover .dropdown-menu,
        .dropdown-user .dropdown-menu:hover {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cart-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: white;
            border: 1px solid #ccc;
            padding: 12px 16px;
            border-radius: 50px;
            font-size: 18px;
            color: #000;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-float .badge {
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🍽️ Food E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown-user ms-3">
                        <a class="nav-link" href="#" role="button">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=0D8ABC&color=fff" width="36" height="36" class="rounded-circle shadow-sm">
                        </a>
                        <div class="dropdown-menu">
                            <p class="mb-1 fw-bold">Hi, <?= htmlspecialchars($username) ?></p>
                            <hr class="my-2">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="admin/index.php" class="dropdown-item">Admin Page</a>
                            <?php endif; ?>
                            <a href="actions/logout.php" class="dropdown-item text-danger">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="pages/login.php" class="nav-link px-3">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/signup.php" class="btn btn-primary ms-2">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- Toast Container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="toast-cart" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        ✅ Cart updated successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>


<div class="container mt-5">
<div class="bg-white p-5 rounded shadow-sm text-center mb-5">
    <h1 class="display-5 fw-bold text-primary">Welcome to Our Food Store</h1>
    <p class="lead text-muted">Delicious meals delivered fast & fresh to your door 🍔🍕🥗</p>
</div>
    <div class="row">
        <?php while ($product = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card product-card">
                <img src="assets/images/<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                    <p class="card-text fw-bold">$<?= number_format($product['price'], 2) ?></p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary mb-2 w-100">View Details</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
    <button type="button" class="btn btn-success w-100 add-to-cart-btn" data-id="<?= $product['id'] ?>">🛒 Add to Cart</button>
<?php else: ?>
    <a href="pages/login.php" class="btn btn-outline-secondary w-100">Login to Add</a>
<?php endif; ?>

                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Floating Cart -->
<?php
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<a href="pages/cart.php" class="cart-float">
    🛒 <span class="badge bg-danger"><?= $cartCount ?></span>
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.id;

        fetch('actions/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message || 'Item added to cart!');
            // Optionally update cart count badge
            const badge = document.querySelector('.cart-float .badge');
            if (badge) badge.textContent = data.cartCount || '0';
        });
    });
});
</script>
<script>
function showToast(message = "Cart updated successfully!") {
  const toast = document.getElementById('toast-cart');
  toast.querySelector('.toast-body').textContent = message;
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
}
</script>
</body>
</html>