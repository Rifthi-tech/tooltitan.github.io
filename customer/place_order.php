<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

requireRole('customer');

$page_title = 'Place Order - Customer';
include '../includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <h1><i class="bi bi-cart-plus"></i> Place New Order</h1>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> This page will allow customers to create new orders.
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-cart" style="font-size: 4rem; color: #6c757d;"></i>
                <h4 class="mt-3">Order System Coming Soon</h4>
                <p class="text-muted">This feature is under development.</p>
                <a href="../index.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>