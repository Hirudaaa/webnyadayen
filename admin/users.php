<?php
require_once '../config/db.php';
require_once '../middlewares/admin_check.php';

$pageTitle = "Users";

// Fetch users
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

ob_start();
?>

<h2 class="mb-4">User Management</h2>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'dark' : 'secondary' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No users found.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>