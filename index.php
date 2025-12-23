<?php
require_once 'config/bootstrap.php';
Session::requireLogin();

$page_title = 'Dashboard - ToolTitan';
$user = Session::getUser();

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h1 class="h3 mb-4">
                    <i class="bi bi-speedometer2"></i> Dashboard
                    <small class="text-muted">- <?php echo ucfirst($user['role']); ?></small>
                </h1>
                
                <div class="row g-3">
                    <?php if (Session::hasRole('admin')): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Manage Users</h5>
                                    <p class="text-muted">Add, edit, and manage user accounts</p>
                                    <a href="admin/manage_users.php" class="btn btn-primary">Manage Users</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-box text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Manage Products</h5>
                                    <p class="text-muted">Oversee all products in the system</p>
                                    <a href="admin/manage_products.php" class="btn btn-success">Manage Products</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (Session::hasRole('supplier')): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">My Products</h5>
                                    <p class="text-muted">Manage your product inventory</p>
                                    <a href="supplier/products.php" class="btn btn-primary">View Products</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (Session::hasRole('customer')): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="bi bi-shop text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Browse Products</h5>
                                    <p class="text-muted">View available products</p>
                                    <a href="customer/products.php" class="btn btn-primary">Browse Products</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>