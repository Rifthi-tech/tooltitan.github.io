<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

requireRole('admin');

$page_title = 'Admin Dashboard - ToolTitan';
include 'includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Admin Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
                        <p>You have full administrative access to the system.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Manage Users</h5>
                                        <p class="text-muted">Add, edit, and delete users</p>
                                        <a href="admin/manage_user.php" class="btn btn-primary">Manage Users</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-box text-success" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Manage Products</h5>
                                        <p class="text-muted">Oversee all products</p>
                                        <a href="admin/manage_products.php" class="btn btn-success">Manage Products</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-list-check text-info" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">View Orders</h5>
                                        <p class="text-muted">Monitor all system orders</p>
                                        <a href="admin/view_orders.php" class="btn btn-info">View Orders</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>