<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'Shop Products - Customer';
$message = '';
$error = '';

$productObj = new Product();
$cartObj = new Cart();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_to_cart') {
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($product_id > 0 && $quantity > 0) {
            // Check if product exists and has enough stock
            $product = $productObj->getById($product_id);
            if (!$product) {
                $error = 'Product not found.';
            } elseif ($product['stock_quantity'] < $quantity) {
                $error = 'Not enough stock available. Only ' . $product['stock_quantity'] . ' items left.';
            } else {
                $result = $cartObj->addItem($_SESSION['user_id'], $product_id, $quantity);
                if ($result) {
                    $message = 'Product "' . htmlspecialchars($product['name']) . '" added to cart successfully!';
                } else {
                    $error = 'Failed to add product to cart. Please try again.';
                }
            }
        } else {
            $error = 'Invalid product or quantity.';
        }
    }
}

$products = $productObj->getAll();
$cart_count = $cartObj->getCartCount($_SESSION['user_id']);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="bi bi-shop"></i> Shop Products</h1>
            <div class="d-flex gap-2">
                <a href="cart.php" class="btn btn-outline-primary position-relative">
                    <i class="bi bi-cart"></i> Cart
                    <?php if ($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $cart_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="orders.php" class="btn btn-outline-info">
                    <i class="bi bi-list-check"></i> My Orders
                </a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning">
                <h4>No Products Available</h4>
                <p>There are no products in the database yet.</p>
                <a href="../add_sample_products.php" class="btn btn-primary">Add Sample Products</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <!-- Product Image -->
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <?php if ($product['product_image'] && file_exists('../' . $product['product_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                     class="img-fluid" alt="Product" style="max-height: 180px; max-width: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="text-center">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    <div><small class="text-muted">No Image</small></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            
                            <?php if ($product['description']): ?>
                                <p class="card-text text-muted"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">$<?php echo number_format($product['price'], 2); ?></span>
                                    <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                                        <?php echo $product['stock_quantity'] > 0 ? 'Stock: ' . $product['stock_quantity'] : 'Out of Stock'; ?>
                                    </span>
                                </div>
                                
                                <small class="text-muted d-block mb-3">
                                    Supplier: <?php echo htmlspecialchars($product['supplier_name'] ?? 'N/A'); ?>
                                </small>
                                
                                <?php if ($product['stock_quantity'] > 0): ?>
                                    <div class="d-grid gap-2">
                                        <!-- Quick Order Button -->
                                        <form method="POST" action="order.php" style="display: inline;">
                                            <input type="hidden" name="action" value="quick_order">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="bi bi-lightning"></i> Order Now
                                            </button>
                                        </form>
                                        
                                        <!-- Add to Cart Form -->
                                        <form method="POST" class="add-to-cart-form">
                                            <input type="hidden" name="action" value="add_to_cart">
                                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                            <div class="input-group">
                                                <input type="number" name="quantity" class="form-control form-control-sm" 
                                                       value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                       style="max-width: 70px;" required>
                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-cart-plus"></i> Add to Cart
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-x-circle"></i> Out of Stock
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Add to cart form handling
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.add-to-cart-form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const quantity = form.querySelector('input[name="quantity"]').value;
            const productId = form.querySelector('input[name="product_id"]').value;
            const button = form.querySelector('button[type="submit"]');
            
            if (!quantity || quantity < 1) {
                alert('Please enter a valid quantity');
                e.preventDefault();
                return false;
            }
            
            if (!productId) {
                alert('Product ID is missing');
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
            button.disabled = true;
            
            // Re-enable button after form submission
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>