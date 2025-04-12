<?php
session_start();
include 'DBconnection.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: Login.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Product form submission
    if (isset($_POST['product-form'])) {
        $id = $_POST['product-id'] ?? '';
        $name = $_POST['product-name'] ?? '';
        $description = $_POST['product-description'] ?? '';
        $price = $_POST['product-price'] ?? 0;
        $stock = $_POST['product-stock'] ?? 0;

        if ($id) {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=? WHERE id=?");
            $stmt->execute([$name, $description, $price, $stock, $id]);
        } else {
            // Add new product
            $newId = 'P' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("INSERT INTO products (id, name, description, price, stock_quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$newId, $name, $description, $price, $stock]);
        }
    }
    
    // Customer form submission
    if (isset($_POST['customer-form'])) {
        $id = $_POST['customer-id'] ?? '';
        $name = $_POST['customer-name'] ?? '';
        $email = $_POST['customer-email'] ?? '';
        $phone = $_POST['customer-phone'] ?? '';
        $address = $_POST['customer-address'] ?? '';

        if ($id) {
            // Update existing customer
            $stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?");
            $stmt->execute([$name, $email, $phone, $address, $id]);
        } else {
            // Add new customer
            $newId = 'C' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("INSERT INTO customers (id, name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$newId, $name, $email, $phone, $address]);
        }
    }
    
    // Supplier form submission
    if (isset($_POST['supplier-form'])) {
        $id = $_POST['supplier-id'] ?? '';
        $name = $_POST['supplier-name'] ?? '';
        $contact = $_POST['supplier-contact'] ?? '';
        $address = $_POST['supplier-address'] ?? '';

        if ($id) {
            // Update existing supplier
            $stmt = $conn->prepare("UPDATE suppliers SET name=?, contact=?, address=? WHERE id=?");
            $stmt->execute([$name, $contact, $address, $id]);
        } else {
            // Add new supplier
            $newId = 'S' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("INSERT INTO suppliers (id, name, contact, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$newId, $name, $contact, $address]);
        }
    }
}

// Handle delete actions
if (isset($_GET['action']) && isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];
    
    try {
        switch ($type) {
            case 'product':
                $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
                break;
            case 'customer':
                $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
                break;
            case 'supplier':
                $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
                break;
            case 'order':
                $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
                break;
            default:
                throw new Exception("Invalid type");
        }
        
        $stmt->execute([$id]);
        echo "<script>alert('Record deleted successfully');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error deleting record: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Fetch data from database
$products = $conn->query("SELECT * FROM products")->fetchAll();
$customers = $conn->query("SELECT * FROM customers")->fetchAll();
$suppliers = $conn->query("SELECT * FROM suppliers")->fetchAll();

// Modified orders query to match your actual database structure
$orders = $conn->query("
    SELECT o.id, o.product_name as product, o.quantity, o.total_price as total_amount, 
           o.order_date, 'Completed' as status
    FROM orders o
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/Admin Dashboard.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Your existing HTML structure -->
    <div class="navbar">
        <div class="main-logo">
            <a href="Customer.html"><img src="Images/LOGO.png" alt="Logo"></a>
        </div>
        <h1>TOOL TITAN</h1>
        <div class="profile">
            <img src="Images/OIP (7).jpeg" alt="Profile Picture">
        </div>
    </div>
    
    <!-- Navigation tabs -->
    <div class="navbar-link">
        <div><a href="#" onclick="showSection('products')">Total Products</a></div>
        <div><a href="#" onclick="showSection('customers')">Total Customers</a></div>
        <div><a href="#" onclick="showSection('suppliers')">Total Suppliers</a></div>
        <div><a href="#" onclick="showSection('orders')">Total Orders</a></div>
    </div>
    
    <!-- Products Section -->
    <div id="products-section" class="main-product">
        <div class="product">
            <h3>Products Details</h3>
            <button type="button" onclick="openProductModal('add')">Add Products</button>
        </div>
        <div class="tbl">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>LKR <?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td>
                            <button class="action" onclick="openProductModal('edit', '<?php echo $product['id']; ?>')">Update</button>
                            <button class="action" onclick="deleteRecord('product', '<?php echo $product['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customers Section -->
    <div id="customers-section" class="main-product" style="display: none;">
        <div class="product">
            <h3>Customer Details</h3>
            <button type="button" onclick="openCustomerModal('add')">Add Customer</button>
        </div>
        <div class="tbl">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['id']); ?></td>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td><?php echo htmlspecialchars($customer['address']); ?></td>
                        <td>
                            <button class="action" onclick="openCustomerModal('edit', '<?php echo $customer['id']; ?>')">Update</button>
                            <button class="action" onclick="deleteRecord('customer', '<?php echo $customer['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Suppliers Section -->
    <div id="suppliers-section" class="main-product" style="display: none;">
        <div class="product">
            <h3>Suppliers Details</h3>
            <button type="button" onclick="openSupplierModal('add')">Add Suppliers</button>
        </div>
        <div class="tbl">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($supplier['id']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['contact']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['address']); ?></td>
                        <td>
                            <button class="action" onclick="openSupplierModal('edit', '<?php echo $supplier['id']; ?>')">Update</button>
                            <button class="action" onclick="deleteRecord('supplier', '<?php echo $supplier['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Orders Section -->
    <div id="orders-section" class="main-product" style="display: none;">
        <div class="product">
            <h3>Orders Details</h3>
        </div>
        <div class="tbl">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['product']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td>LKR <?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <button class="action" onclick="viewOrderDetails('<?php echo $order['id']; ?>')">View</button>
                            <button class="action" onclick="deleteRecord('order', '<?php echo $order['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Product Modal -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="product-modal-title">Add Product</h2>
                <span class="close-btn" onclick="closeModal('product-modal')">&times;</span>
            </div>
            <form id="product-form" method="POST">
                <input type="hidden" name="product-form" value="1">
                <input type="hidden" id="product-id" name="product-id">
                <div class="form-group">
                    <label for="product-name">Name</label>
                    <input type="text" id="product-name" name="product-name" required>
                </div>
                <div class="form-group">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" name="product-description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="product-price">Price</label>
                    <input type="number" id="product-price" name="product-price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="product-stock">Stock Quantity</label>
                    <input type="number" id="product-stock" name="product-stock" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('product-modal')">Cancel</button>
                    <button type="submit" class="btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Modal -->
    <div id="customer-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="customer-modal-title">Add Customer</h2>
                <span class="close-btn" onclick="closeModal('customer-modal')">&times;</span>
            </div>
            <form id="customer-form" method="POST">
                <input type="hidden" name="customer-form" value="1">
                <input type="hidden" id="customer-id" name="customer-id">
                <div class="form-group">
                    <label for="customer-name">Name</label>
                    <input type="text" id="customer-name" name="customer-name" required>
                </div>
                <div class="form-group">
                    <label for="customer-email">Email</label>
                    <input type="email" id="customer-email" name="customer-email" required>
                </div>
                <div class="form-group">
                    <label for="customer-phone">Phone</label>
                    <input type="tel" id="customer-phone" name="customer-phone" required>
                </div>
                <div class="form-group">
                    <label for="customer-address">Address</label>
                    <textarea id="customer-address" name="customer-address" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('customer-modal')">Cancel</button>
                    <button type="submit" class="btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Supplier Modal -->
    <div id="supplier-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="supplier-modal-title">Add Supplier</h2>
                <span class="close-btn" onclick="closeModal('supplier-modal')">&times;</span>
            </div>
            <form id="supplier-form" method="POST">
                <input type="hidden" name="supplier-form" value="1">
                <input type="hidden" id="supplier-id" name="supplier-id">
                <div class="form-group">
                    <label for="supplier-name">Name</label>
                    <input type="text" id="supplier-name" name="supplier-name" required>
                </div>
                <div class="form-group">
                    <label for="supplier-contact">Contact</label>
                    <input type="text" id="supplier-contact" name="supplier-contact" required>
                </div>
                <div class="form-group">
                    <label for="supplier-address">Address</label>
                    <textarea id="supplier-address" name="supplier-address" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('supplier-modal')">Cancel</button>
                    <button type="submit" class="btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="order-modal" class="modal">
        <div class="modal-content" style="width: 700px;">
            <div class="modal-header">
                <h2 id="order-modal-title">Order Details</h2>
                <span class="close-btn" onclick="closeModal('order-modal')">&times;</span>
            </div>
            <div id="order-details-content">
                <!-- Content will be loaded via AJAX -->
                <p>Loading order details...</p>
            </div>
        </div>
    </div>

    <script>
        // Navigation functions
        function showSection(section) {
            document.getElementById('products-section').style.display = 'none';
            document.getElementById('customers-section').style.display = 'none';
            document.getElementById('suppliers-section').style.display = 'none';
            document.getElementById('orders-section').style.display = 'none';
            
            document.getElementById(section + '-section').style.display = 'block';
        }
        
        // Product functions
        function openProductModal(action, productId = null) {
            const modal = document.getElementById('product-modal');
            const form = document.getElementById('product-form');
            
            if (action === 'add') {
                document.getElementById('product-modal-title').textContent = 'Add Product';
                form.reset();
                document.getElementById('product-id').value = '';
            } else {
                document.getElementById('product-modal-title').textContent = 'Edit Product';
                
                // Fetch product details from the table row
                const row = document.querySelector(`#products-table-body tr button[onclick*="'${productId}'"]`).closest('tr');
                
                document.getElementById('product-id').value = productId;
                document.getElementById('product-name').value = row.cells[1].textContent;
                document.getElementById('product-description').value = row.cells[2].textContent;
                document.getElementById('product-price').value = parseFloat(row.cells[3].textContent.replace('LKR ', ''));
                document.getElementById('product-stock').value = parseInt(row.cells[4].textContent);
            }
            
            modal.style.display = 'flex';
        }
        
        // Customer functions
        function openCustomerModal(action, customerId = null) {
            const modal = document.getElementById('customer-modal');
            const form = document.getElementById('customer-form');
            
            if (action === 'add') {
                document.getElementById('customer-modal-title').textContent = 'Add Customer';
                form.reset();
                document.getElementById('customer-id').value = '';
            } else {
                document.getElementById('customer-modal-title').textContent = 'Edit Customer';
                
                // Fetch customer details from the table row
                const row = document.querySelector(`#customers-table-body tr button[onclick*="'${customerId}'"]`).closest('tr');
                
                document.getElementById('customer-id').value = customerId;
                document.getElementById('customer-name').value = row.cells[1].textContent;
                document.getElementById('customer-email').value = row.cells[2].textContent;
                document.getElementById('customer-phone').value = row.cells[3].textContent;
                document.getElementById('customer-address').value = row.cells[4].textContent;
            }
            
            modal.style.display = 'flex';
        }
        
        // Supplier functions
        function openSupplierModal(action, supplierId = null) {
            const modal = document.getElementById('supplier-modal');
            const form = document.getElementById('supplier-form');
            
            if (action === 'add') {
                document.getElementById('supplier-modal-title').textContent = 'Add Supplier';
                form.reset();
                document.getElementById('supplier-id').value = '';
            } else {
                document.getElementById('supplier-modal-title').textContent = 'Edit Supplier';
                
                // Fetch supplier details from the table row
                const row = document.querySelector(`#suppliers-table-body tr button[onclick*="'${supplierId}'"]`).closest('tr');
                
                document.getElementById('supplier-id').value = supplierId;
                document.getElementById('supplier-name').value = row.cells[1].textContent;
                document.getElementById('supplier-contact').value = row.cells[2].textContent;
                document.getElementById('supplier-address').value = row.cells[3].textContent;
            }
            
            modal.style.display = 'flex';
        }
        
        // Order functions
        function viewOrderDetails(orderId) {
            const modal = document.getElementById('order-modal');
            const content = document.getElementById('order-details-content');
            
            content.innerHTML = '<p>Loading order details...</p>';
            
            // Fetch order details via AJAX
            fetch(`get_order_details.php?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    let html = `
                        <div style="margin-bottom: 20px;">
                            <h3>Order Information</h3>
                            <p><strong>Order ID:</strong> ${data.order.id}</p>
                            <p><strong>Product:</strong> ${data.order.product_name}</p>
                            <p><strong>Quantity:</strong> ${data.order.quantity}</p>
                            <p><strong>Total Price:</strong> LKR ${data.order.total_price}</p>
                            <p><strong>Order Date:</strong> ${data.order.order_date}</p>
                        </div>`;
                    
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = `<p>Error loading order details: ${error.message}</p>`;
                });
            
            modal.style.display = 'flex';
        }
        
        // Utility functions
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function deleteRecord(type, id) {
            if (confirm(`Are you sure you want to delete this ${type}?`)) {
                window.location.href = `?action=delete&type=${type}&id=${id}`;
            }
        }
    </script>
</body>
</html>