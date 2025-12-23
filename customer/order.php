<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'Quick Order - Customer';
$message = '';
$error = '';

$productObj = new Product();
$orderObj = new Order();

// Handle quick order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'quick_order') {
        $product_id = $_POST['product_id'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;
        
        // Get product details
        $product = $productObj->getById($product_id);
        
        if (!$product) {
            $error = 'Product not found.';
        } elseif ($product['stock_quantity'] < $quantity) {
            $error = 'Insufficient stock available.';
        } else {
            // Create order items array
            $cart_items = [[
                'product_id' => $product['product_id'],
                'quantity' => $quantity,
                'price' => $product['price'],
                'subtotal' => $product['price'] * $quantity
            ]];
            
            $order_id = $orderObj->createOrder($_SESSION['user_id'], $cart_items);
            
            if ($order_id) {
                // Redirect to success page
                header('Location: order_success.php?order_id=' . $order_id);
                exit();
            } else {
                $error = 'Failed to create order. Please try again.';
            }
        }
    } elseif ($action === 'confirm_order') {
        $product_id = $_POST['product_id'] ?? '';
        $quantity = $_POST['quantity'] ?? 1;
        
        // Get product details for confirmation
        $product = $productObj->getById($product_id);
        
        if (!$product) {
            $error = 'Product not found.';
        } elseif ($product['stock_quantity'] < $quantity) {
            $error = 'Insufficient stock available.';
        }
    }
}

// Get product details if product_id is provided
$product = null;
$quantity = 1;

if (isset($_POST['product_id'])) {
    $product = $productObj->getById($_POST['product_id']);
    $quantity = $_POST['quantity'] ?? 1;
} elseif (isset($_GET['product_id'])) {
    $product = $productObj->getById($_GET['product_id']);
    $quantity = $_GET['quantity'] ?? 1;
}

if (!$product) {
    header('Location: products.php');
    exit();
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Order Confirmation</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4">
                        <?php if ($product['product_image']): ?>
                            <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                                 class="img-fluid rounded" alt="Product">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        
                        <?php if ($product['description']): ?>
                            <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?>
                            </div>
                            <div class="col-sm-6">
                                <strong>Available Stock:</strong> <?php echo $product['stock_quantity']; ?>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <label for="quantity" class="form-label">Quantity:</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" 
                                           value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['stock_quantity']; ?>" required>
                                </div>
                                <div class="col-sm-6 d-flex align-items-end">
                                    <div>
                                        <strong>Total: $<span id="total"><?php echo number_format($product['price'] * $quantity, 2); ?></span></strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="quick_order" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Confirm Order
                                </button>
                                <a href="products.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Products
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update total when quantity changes
document.getElementById('quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 0;
    const price = <?php echo $product['price']; ?>;
    const total = quantity * price;
    document.getElementById('total').textContent = total.toFixed(2);
});
</script>

<?php include '../includes/footer.php'; ?>