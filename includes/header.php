<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ToolTitan'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #007bff !important;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: calc(100vh - 56px);
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .btn-custom {
            border-radius: 0.375rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-tools"></i> ToolTitan
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="bi bi-house"></i> Dashboard</a>
                        </li>
                        
                        <?php if (hasRole('admin')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="admin/manage_user.php">Manage Users</a></li>
                                    <li><a class="dropdown-item" href="admin/manage_products.php">Manage Products</a></li>
                                    <li><a class="dropdown-item" href="admin/view_orders.php">View Orders</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (hasRole('supplier')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-box"></i> Supplier
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="supplier/add_product.php">Add Product</a></li>
                                    <li><a class="dropdown-item" href="supplier/my_products.php">My Products</a></li>
                                    <li><a class="dropdown-item" href="supplier/update_stock.php">Update Stock</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (hasRole('customer')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-cart"></i> Customer
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="customer/view_products.php">View Products</a></li>
                                    <li><a class="dropdown-item" href="customer/my_orders.php">My Orders</a></li>
                                    <li><a class="dropdown-item" href="customer/place_order.php">Place Order</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text">Role: <?php echo ucfirst($_SESSION['role']); ?></span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="loginpage.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid">
        <div class="row">