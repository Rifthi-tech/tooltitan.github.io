<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'Checkout - Customer';
$message = '';
$error = '';

$cartObj = new Cart();
$orderObj = new Order();

// Get cart items
$cart_items = $cartObj->getItems($_SESSION['user_id']);
$cart_total = $cartObj->getCartTotal($_SESSION['user_id']);

// Redirect if cart is empty
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'place_order') {
        // Validate stock availability
        $stock_error = false;
        foreach ($cart_items as $item) {
            if ($item['stock_quantity'] < $item['quantity']) {
                $stock_error = true;
                $error = 'Some items in your cart are no longer available in the requested quantity.';
                break;
            }
        }
        
        if (!$stock_error) {
            $order_id = $orderObj->createOrder($_SESSION['user_id'], $cart_items);
            
            if ($order_id) {
                // Clear cart after successful order
                $cartObj->clearCart($_SESSION['user_id']);
                
                // Redirect to success page
                header('Location: order_success.php?order_id=' . $order_id);
                exit();
            } else {
                $error = 'Failed to place order. Please try again.';
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <h1 class="h3 mb-4"><i class="bi bi-credit-card"></i> Checkout</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Review</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="row align-items-center border-bottom py-3">
                                <div class="col-md-2">
                                    <?php if ($item['product_image']): ?>
                                        <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                             class="img-fluid rounded" alt="Product" style="max-height: 60px;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">$<?php echo number_format($item['price'], 2); ?> each</small>
                                </div>
                                <div class="col-md-2">
                                    <span>Qty: <?php echo $item['quantity']; ?></span>
                                </div>
                                <div class="col-md-2">
                                    <strong>$<?php echo number_format($item['subtotal'], 2); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Demo Mode:</strong> This is a demonstration. No actual payment or shipping is required.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p class="mb-1"><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                <p class="mb-1"><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Shipping Method</h6>
                                <p class="mb-1"><strong>Standard Shipping:</strong> Free</p>
                                <p class="mb-1"><strong>Estimated Delivery:</strong> 3-5 business days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<?php echo count($cart_items); ?> items):</span>
                            <span>$<?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="text-primary">$<?php echo number_format($cart_total, 2); ?></strong>
                        </div>
                        
                        <form method="POST">
                            <input type="hidden" name="action" value="place_order">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> Place Order
                                </button>
                                <a href="cart.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Cart
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>