<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

requireRole('customer');

$page_title = 'Customer Dashboard - ToolTitan';
include 'includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-circle"></i> Customer Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
                        <p>You have customer access to the system.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-shop text-primary" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Browse Products</h5>
                                        <p class="text-muted">View available products</p>
                                        <a href="customer/view_products.php" class="btn btn-primary">View Products</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-cart-plus text-success" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Place Order</h5>
                                        <p class="text-muted">Create new orders</p>
                                        <a href="customer/place_order.php" class="btn btn-success">Place Order</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-bag-check text-info" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">My Orders</h5>
                                        <p class="text-muted">Track your orders</p>
                                        <a href="customer/my_orders.php" class="btn btn-info">View Orders</a>
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