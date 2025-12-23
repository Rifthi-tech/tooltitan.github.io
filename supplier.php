<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

requireRole('supplier');

$page_title = 'Supplier Dashboard - ToolTitan';
include 'includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-box"></i> Supplier Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-plus-circle text-success" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Add Product</h5>
                                        <p class="text-muted">Add new products to inventory</p>
                                        <a href="supplier/add_product.php" class="btn btn-success">Add Product</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">My Products</h5>
                                        <p class="text-muted">Manage your products</p>
                                        <a href="supplier/my_products.php" class="btn btn-primary">View Products</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="bi bi-arrow-repeat text-warning" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">Update Stock</h5>
                                        <p class="text-muted">Manage inventory levels</p>
                                        <a href="supplier/update_stock.php" class="btn btn-warning">Update Stock</a>
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