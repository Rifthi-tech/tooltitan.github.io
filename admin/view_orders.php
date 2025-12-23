<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

requireRole('admin');

$page_title = 'View Orders - Admin';
include '../includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <h1><i class="bi bi-list-check"></i> View All Orders</h1>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> This page will display all orders in the system for administrative oversight.
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-clipboard-data" style="font-size: 4rem; color: #6c757d;"></i>
                <h4 class="mt-3">Order Management Coming Soon</h4>
                <p class="text-muted">This feature is under development.</p>
                <a href="../index.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>