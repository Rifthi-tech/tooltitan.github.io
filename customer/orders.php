<?php
require_once '../config/bootstrap.php';

Session::requireRole('customer');

$page_title = 'My Orders - Customer';

$orderObj = new Order();
$orders = $orderObj->getCustomerOrders($_SESSION['user_id']);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="bi bi-list-check"></i> My Orders</h1>
            <a href="products.php" class="btn btn-outline-primary">
                <i class="bi bi-shop"></i> Continue Shopping
            </a>
        </div>

        <?php if (!empty($orders)): ?>
            <div class="row">
                <?php foreach ($orders as $order): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Order #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></h6>
                            <span class="badge bg-<?php echo $order['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Order Date</small>
                                    <div><?php echo date('M j, Y', strtotime($order['order_date'])); ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Amount</small>
                                    <div class="h6 text-primary">$<?php echo number_format($order['total_amount'], 2); ?></div>
                                </div>
                            </div>
                            
                            <?php
                            // Get order details for this order
                            $order_details = $orderObj->getOrderDetails($order['order_id']);
                            ?>
                            
                            <div class="mb-3">
                                <small class="text-muted">Items (<?php echo count($order_details); ?>)</small>
                                <div class="mt-1">
                                    <?php foreach (array_slice($order_details, 0, 3) as $item): ?>
                                        <div class="d-flex align-items-center mb-1">
                                            <?php if ($item['product_image']): ?>
                                                <img src="../<?php echo htmlspecialchars($item['product_image']); ?>" 
                                                     class="img-thumbnail me-2" alt="Product" style="width: 30px; height: 30px; object-fit: cover;">
                                            <?php endif; ?>
                                            <small><?php echo htmlspecialchars($item['product_name']); ?> (<?php echo $item['quantity']; ?>x)</small>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (count($order_details) > 3): ?>
                                        <small class="text-muted">... and <?php echo count($order_details) - 3; ?> more items</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                        data-bs-target="#orderModal<?php echo $order['order_id']; ?>">
                                    <i class="bi bi-eye"></i> View Details
                                </button>
                                <?php if ($order['status'] === 'confirmed'): ?>
                                    <span class="btn btn-sm btn-outline-success disabled">
                                        <i class="bi bi-check-circle"></i> Confirmed
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Details Modal -->
                <div class="modal fade" id="orderModal<?php echo $order['order_id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?> Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Order Date:</strong> <?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-<?php echo $order['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <h6>Order Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
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
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th>$<?php echo number_format($order['total_amount'], 2); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">No Orders Yet</h4>
                <p class="text-muted">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="products.php" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>