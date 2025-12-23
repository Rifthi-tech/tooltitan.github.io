<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'Shopping Cart - Customer';
$message = '';
$error = '';

$cartObj = new Cart();

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_quantity') {
        $cart_id = $_POST['cart_id'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        
        if ($cartObj->updateQuantity($cart_id, $quantity)) {
            $message = 'Cart updated successfully!';
        } else {
            $error = 'Failed to update cart.';
        }
    } elseif ($action === 'remove_item') {
        $cart_id = $_POST['cart_id'] ?? '';
        
        if ($cartObj->removeItem($cart_id)) {
            $message = 'Item removed from cart!';
        } else {
            $error = 'Failed to remove item.';
        }
    } elseif ($action === 'clear_cart') {
        if ($cartObj->clearCart($_SESSION['user_id'])) {
            $message = 'Cart cleared successfully!';
        } else {
            $error = 'Failed to clear cart.';
        }
    }
}

$cart_items = $cartObj->getItems($_SESSION['user_id']);
$cart_total = $cartObj->getCartTotal($_SESSION['user_id']);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="bi bi-cart"></i> Shopping Cart</h1>
            <div class="d-flex gap-2">
                <a href="products.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
                <?php if (!empty($cart_items)): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="clear_cart">
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Clear all items from cart?')">
                            <i class="bi bi-trash"></i> Clear Cart
                        </button>
                    </form>
                <?php endif; ?>
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

                        <?php if (!empty($cart_items)): ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Cart Items</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="row align-items-center border-bottom py-3">
                                    <div class="col-md-2">
                                        <?php if ($item['product_image'] && file_exists('../' . $item['product_image'])): ?>
                                            <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                 class="img-fluid rounded" alt="Product" style="max-height: 80px;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 80px; width: 80px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">$<?php echo number_format($item['price'], 2); ?> each</small>
                                        <br>
                                        <small class="text-muted">Available: <?php echo $item['stock_quantity']; ?></small>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-flex align-items-center gap-2" onsubmit="updateCartItem(this); return true;">
                                            <input type="hidden" name="action" value="update_quantity">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <label class="form-label mb-0 me-2">Qty:</label>
                                            <input type="number" name="quantity" class="form-control form-control-sm quantity-input" 
                                                   value="<?php echo $item['quantity']; ?>" min="1" 
                                                   max="<?php echo $item['stock_quantity']; ?>" style="width: 80px;"
                                                   data-price="<?php echo $item['price']; ?>" 
                                                   data-cart-id="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <strong class="text-primary subtotal-<?php echo $item['cart_id']; ?>">$<?php echo number_format($item['subtotal'], 2); ?></strong>
                                        <br>
                                        <small class="text-muted calculation-<?php echo $item['cart_id']; ?>"><?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></small>
                                    </div>
                                    <div class="col-md-1">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="remove_item">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Remove this item?')" title="Remove item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Items (<span id="total-items"><?php echo count($cart_items); ?></span>):</span>
                                    <span id="cart-subtotal">$<?php echo number_format($cart_total, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span class="text-success">Free</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    <strong class="text-primary" id="cart-total">$<?php echo number_format($cart_total, 2); ?></strong>
                                </div>
                            
                            <div class="d-grid">
                                <a href="checkout.php" class="btn btn-success btn-lg">
                                    <i class="bi bi-credit-card"></i> Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Your Cart is Empty</h4>
                <p class="text-muted">Add some products to get started!</p>
                <a href="products.php" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
// Real-time price calculation
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const cartId = this.dataset.cartId;
            const price = parseFloat(this.dataset.price);
            const quantity = parseInt(this.value) || 0;
            const subtotal = price * quantity;
            
            // Update subtotal display
            const subtotalElement = document.querySelector('.subtotal-' + cartId);
            const calculationElement = document.querySelector('.calculation-' + cartId);
            
            if (subtotalElement) {
                subtotalElement.textContent = '$' + subtotal.toFixed(2);
            }
            
            if (calculationElement) {
                calculationElement.textContent = quantity + ' × $' + price.toFixed(2);
            }
            
            // Update cart total
            updateCartTotal();
        });
    });
});

function updateCartTotal() {
    let total = 0;
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(function(input) {
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value) || 0;
        total += price * quantity;
    });
    
    // Update total displays
    const cartSubtotal = document.getElementById('cart-subtotal');
    const cartTotal = document.getElementById('cart-total');
    
    if (cartSubtotal) {
        cartSubtotal.textContent = '$' + total.toFixed(2);
    }
    
    if (cartTotal) {
        cartTotal.textContent = '$' + total.toFixed(2);
    }
}

function updateCartItem(form) {
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    button.disabled = true;
    
    // Re-enable after submission
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}
</script>