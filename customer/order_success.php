<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'Order Confirmed - Customer';

$orderObj = new Order();

$order_id = $_GET['order_id'] ?? '';
if (!$order_id) {
    header('Location: products.php');
    exit();
}

$order_details = $orderObj->getOrderDetails($order_id);
if (empty($order_details)) {
    header('Location: products.php');
    exit();
}

// Verify this order belongs to the current customer
if ($order_details[0]['customer_id'] != $_SESSION['user_id']) {
    header('Location: products.php');
    exit();
}

$order_info = $order_details[0]; // Get order info from first item

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h1 class="h2 text-success">Order Confirmed!</h1>
            <p class="lead">Thank you for your order. Your order has been successfully placed.</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p class="mb-1"><strong>Order ID:</strong> #<?php echo str_pad($order_info['order_id'], 6, '0', STR_PAD_LEFT); ?></p>
                        <p class="mb-1"><strong>Order Date:</strong> <?php echo date('M j, Y g:i A', strtotime($order_info['order_date'] ?? $order_info['created_at'] ?? 'now')); ?></p>
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="badge bg-success"><?php echo ucfirst($order_info['status']); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Customer:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <p class="mb-1"><strong>Total Amount:</strong> 
                            <span class="text-primary h5">$<?php echo number_format($order_info['total_amount'], 2); ?></span>
                        </p>
                    </div>
                </div>
                
                <h6>Ordered Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_details as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item['product_image']): ?>
                                            <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                 class="img-thumbnail me-2" alt="Product" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <div class="d-flex gap-2 justify-content-center">
                <a href="products.php" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Continue Shopping
                </a>
                <a href="orders.php" class="btn btn-outline-primary">
                    <i class="bi bi-list-check"></i> View All Orders
                </a>
            </div>
        </div>
        
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle"></i>
            <strong>What's Next?</strong> Your order is being processed. You can track your order status in the "My Orders" section.
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>