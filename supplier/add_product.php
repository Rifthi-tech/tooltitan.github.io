<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

requireRole('supplier');

$page_title = 'Add Product - Supplier';
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock_quantity = $_POST['stock_quantity'] ?? '';
    $product_image = '';
    
    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
                $product_image = 'assets/images/products/' . $new_filename;
            }
        }
    }
    
    // Validation
    if (empty($name) || empty($price) || empty($stock_quantity)) {
        $error = 'Name, price, and stock quantity are required.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a valid positive number.';
    } elseif (!is_numeric($stock_quantity) || $stock_quantity < 0) {
        $error = 'Stock quantity must be a valid number.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, product_image, supplier_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $stock_quantity, $product_image, $_SESSION['user_id']]);
            $message = 'Product added successfully!';
            
            // Clear form data
            $name = $description = $price = $stock_quantity = '';
        } catch (PDOException $e) {
            $error = 'Failed to add product. Please try again.';
        }
    }
}

include '../includes/header.php';
?>

<div class="col-12">
    <div class="main-content p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-plus-circle"></i> Add New Product</h1>
            <a href="my_products.php" class="btn btn-outline-primary">
                <i class="bi bi-box-seam"></i> View My Products
            </a>
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="addProductForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price ($) *</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                           min="0" value="<?php echo htmlspecialchars($stock_quantity ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="product_image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image" 
                                           accept="image/*">
                                    <div class="form-text">Supported formats: JPG, JPEG, PNG, GIF</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          placeholder="Enter product description..."><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Add Product
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Form
                                </button>
                                <a href="../index.php" class="btn btn-outline-primary">
                                    <i class="bi bi-house"></i> Back to Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <strong>Product Name:</strong> Use clear, descriptive names
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <strong>Pricing:</strong> Set competitive prices
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <strong>Stock:</strong> Keep accurate inventory counts
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <strong>Images:</strong> High-quality photos increase sales
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <strong>Description:</strong> Include key features and benefits
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    const stock = document.getElementById('stock_quantity').value;
    
    if (!name || !price || !stock) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
    }
    
    if (parseFloat(price) <= 0) {
        e.preventDefault();
        alert('Price must be greater than 0.');
        return;
    }
    
    if (parseInt(stock) < 0) {
        e.preventDefault();
        alert('Stock quantity cannot be negative.');
        return;
    }
});

// Image preview
document.getElementById('product_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here if needed
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include '../includes/footer.php'; ?>