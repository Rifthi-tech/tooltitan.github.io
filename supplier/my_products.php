<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

requireRole('supplier');

$page_title = 'My Products - Supplier';
include '../includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <h1><i class="bi bi-box-seam"></i> My Products</h1>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> This page will display all products managed by this supplier.
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-boxes" style="font-size: 4rem; color: #6c757d;"></i>
                <h4 class="mt-3">Product Listing Coming Soon</h4>
                <p class="text-muted">This feature is under development.</p>
                <a href="../index.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>