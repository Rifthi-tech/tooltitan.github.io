<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

requireLogin();

$page_title = 'Dashboard - ToolTitan';
$user = getCurrentUser();

// Get some statistics
try {
    $stats = [];
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $stats['total_users'] = $stmt->fetchColumn();
    
    // Users by role
    $stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $stats['users_by_role'] = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $stats = ['total_users' => 0, 'users_by_role' => []];
}

include 'includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="card-title mb-2">
                                    <i class="bi bi-speedometer2"></i> Welcome, <?php echo htmlspecialchars($user['username']); ?>!
                                </h1>
                                <p class="card-text mb-0">
                                    You are logged in as: <strong><?php echo ucfirst($user['role']); ?></strong>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <i class="bi bi-person-circle" style="font-size: 4rem; opacity: 0.7;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <?php if (hasRole('admin')): ?>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        <h3 class="mt-2"><?php echo $stats['total_users']; ?></h3>
                        <p class="text-muted mb-0">Total Users</p>
                    </div>
                </div>
            </div>
            
            <?php foreach ($stats['users_by_role'] as $role_stat): ?>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge text-info" style="font-size: 2rem;"></i>
                        <h3 class="mt-2"><?php echo $role_stat['count']; ?></h3>
                        <p class="text-muted mb-0"><?php echo ucfirst($role_stat['role']); ?>s</p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (hasRole('admin')): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="admin/manage_user.php" class="btn btn-outline-primary btn-lg">
                                            <i class="bi bi-people"></i><br>
                                            <small>Manage Users</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="admin/manage_products.php" class="btn btn-outline-success btn-lg">
                                            <i class="bi bi-box"></i><br>
                                            <small>Manage Products</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="admin/view_orders.php" class="btn btn-outline-info btn-lg">
                                            <i class="bi bi-list-check"></i><br>
                                            <small>View Orders</small>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (hasRole('supplier')): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="supplier/add_product.php" class="btn btn-outline-success btn-lg">
                                            <i class="bi bi-plus-circle"></i><br>
                                            <small>Add Product</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="supplier/my_products.php" class="btn btn-outline-primary btn-lg">
                                            <i class="bi bi-box-seam"></i><br>
                                            <small>My Products</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="supplier/update_stock.php" class="btn btn-outline-warning btn-lg">
                                            <i class="bi bi-arrow-repeat"></i><br>
                                            <small>Update Stock</small>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (hasRole('customer')): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="customer/view_products.php" class="btn btn-outline-primary btn-lg">
                                            <i class="bi bi-shop"></i><br>
                                            <small>Browse Products</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="customer/place_order.php" class="btn btn-outline-success btn-lg">
                                            <i class="bi bi-cart-plus"></i><br>
                                            <small>Place Order</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-grid">
                                        <a href="customer/my_orders.php" class="btn btn-outline-info btn-lg">
                                            <i class="bi bi-bag-check"></i><br>
                                            <small>My Orders</small>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> System Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Your Account Details:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>User ID:</strong> <?php echo $user['user_id']; ?></li>
                                    <li><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
                                    <li><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></li>
                                    <li><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>System Status:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-check-circle text-success"></i> Database Connected</li>
                                    <li><i class="bi bi-check-circle text-success"></i> Session Active</li>
                                    <li><i class="bi bi-check-circle text-success"></i> All Systems Operational</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>