<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'ToolTitan'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #212529;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%) !important;
            border-bottom: 2px solid #ffffff;
        }
        .navbar-brand { 
            font-weight: bold;
            color: #ffffff !important;
            text-decoration: none;
        }
        .navbar-brand:hover {
            color: #f8f9fa !important;
        }
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }
        .dropdown-menu {
            background: #343a40;
            border: 1px solid #495057;
        }
        .dropdown-item {
            color: #ffffff;
        }
        .dropdown-item:hover {
            background: #495057;
            color: #ffffff;
        }
        .dropdown-item-text {
            color: rgba(255, 255, 255, 0.7);
        }
        .dropdown-divider {
            border-color: #495057;
        }
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }
        .card { 
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid #dee2e6;
            background: #ffffff;
        }
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .table th { 
            background-color: #f8f9fa;
            color: #495057;
        }
        .btn { 
            border-radius: 0.375rem;
        }
        .btn-primary {
            background: #212529;
            border-color: #212529;
        }
        .btn-primary:hover {
            background: #1c1f23;
            border-color: #1c1f23;
        }
        .btn-outline-primary {
            color: #212529;
            border-color: #212529;
        }
        .btn-outline-primary:hover {
            background: #212529;
            border-color: #212529;
            color: #ffffff;
        }
        .text-primary {
            color: #212529 !important;
        }
        .bg-primary {
            background-color: #212529 !important;
        }
        .badge.bg-primary {
            background-color: #212529 !important;
        }
        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .footer {
            background: #212529;
            color: #ffffff;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <?php
            // Determine the correct path to index.php based on current location
            $indexPath = 'index.php';
            if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || 
                strpos($_SERVER['PHP_SELF'], '/supplier/') !== false || 
                strpos($_SERVER['PHP_SELF'], '/customer/') !== false) {
                $indexPath = '../index.php';
            }
            ?>
            <a class="navbar-brand" href="<?php echo $indexPath; ?>">
                <i class="bi bi-tools"></i> ToolTitan
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (Session::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $indexPath; ?>">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if (Session::hasRole('admin')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'manage_users.php' : 'admin/manage_users.php'; ?>">
                                    <i class="bi bi-people"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'manage_products.php' : 'admin/manage_products.php'; ?>">
                                    <i class="bi bi-box"></i> Products
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (Session::hasRole('supplier')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo strpos($_SERVER['PHP_SELF'], '/supplier/') !== false ? 'products.php' : 'supplier/products.php'; ?>">
                                    <i class="bi bi-box-seam"></i> My Products
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (Session::hasRole('customer')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo strpos($_SERVER['PHP_SELF'], '/customer/') !== false ? 'products.php' : 'customer/products.php'; ?>">
                                    <i class="bi bi-shop"></i> Products
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (Session::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text">Role: <?php echo ucfirst($_SESSION['role']); ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/supplier/') !== false || strpos($_SERVER['PHP_SELF'], '/customer/') !== false ? '../logout.php' : 'logout.php'; ?>">
                                    <i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid main-content">