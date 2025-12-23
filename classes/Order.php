<?php
class Order {
    private $conn;
    private $table = 'orders';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createOrder($customer_id, $cart_items) {
        try {
            $this->conn->beginTransaction();
            
            // Calculate total
            $total = 0;
            foreach ($cart_items as $item) {
                $total += $item['subtotal'];
            }
            
            // Create order
            $query = "INSERT INTO " . $this->table . " (customer_id, total_amount, status) VALUES (?, ?, 'confirmed')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id, $total]);
            $order_id = $this->conn->lastInsertId();
            
            // Add order items
            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $item_stmt = $this->conn->prepare($item_query);
            
            // Update product stock
            $stock_query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
            $stock_stmt = $this->conn->prepare($stock_query);
            
            foreach ($cart_items as $item) {
                // Add to order items
                $item_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                
                // Update stock
                $stock_stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            $this->conn->commit();
            return $order_id;
            
        } catch(PDOException $e) {
            $this->conn->rollback();
            error_log("Order Creation Error: " . $e->getMessage());
            return false;
        }
    }

    public function getCustomerOrders($customer_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE customer_id = ? ORDER BY order_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id]);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get Orders Error: " . $e->getMessage());
            return [];
        }
    }

    public function getOrderDetails($order_id) {
        try {
            $query = "SELECT o.*, oi.*, p.name as product_name, p.product_image
                      FROM " . $this->table . " o
                      JOIN order_items oi ON o.order_id = oi.order_id
                      JOIN products p ON oi.product_id = p.product_id
                      WHERE o.order_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$order_id]);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get Order Details Error: " . $e->getMessage());
            return [];
        }
    }
}
?>