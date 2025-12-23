<?php
class Cart {
    private $conn;
    private $table = 'cart_items';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addItem($customer_id, $product_id, $quantity = 1) {
        try {
            // Validate inputs
            if (!$customer_id || !$product_id || $quantity <= 0) {
                return false;
            }
            
            // Check if product exists
            $product_check = $this->conn->prepare("SELECT product_id, stock_quantity FROM products WHERE product_id = ?");
            $product_check->execute([$product_id]);
            $product = $product_check->fetch();
            
            if (!$product) {
                return false;
            }
            
            if ($product['stock_quantity'] < $quantity) {
                return false;
            }
            
            // Check if item already exists in cart
            $query = "SELECT cart_id, quantity FROM " . $this->table . " WHERE customer_id = ? AND product_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id, $product_id]);
            
            if ($stmt->rowCount() > 0) {
                // Update existing item
                $existing = $stmt->fetch();
                $new_quantity = $existing['quantity'] + $quantity;
                
                // Check if new quantity exceeds stock
                if ($new_quantity > $product['stock_quantity']) {
                    return false;
                }
                
                $update_query = "UPDATE " . $this->table . " SET quantity = ? WHERE cart_id = ?";
                $update_stmt = $this->conn->prepare($update_query);
                return $update_stmt->execute([$new_quantity, $existing['cart_id']]);
            } else {
                // Add new item
                $query = "INSERT INTO " . $this->table . " (customer_id, product_id, quantity) VALUES (?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$customer_id, $product_id, $quantity]);
            }
        } catch(PDOException $e) {
            return false;
        } catch(Exception $e) {
            return false;
        }
    }

    public function getItems($customer_id) {
        try {
            $query = "SELECT c.cart_id, c.product_id, c.quantity, c.added_at,
                             p.name, p.price, p.product_image, p.stock_quantity,
                             (c.quantity * p.price) as subtotal
                      FROM " . $this->table . " c 
                      JOIN products p ON c.product_id = p.product_id 
                      WHERE c.customer_id = ? 
                      ORDER BY c.added_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id]);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            return [];
        }
    }

    public function updateQuantity($cart_id, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeItem($cart_id);
            }
            
            // Check stock availability
            $check_query = "SELECT p.stock_quantity FROM " . $this->table . " c 
                           JOIN products p ON c.product_id = p.product_id 
                           WHERE c.cart_id = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$cart_id]);
            $stock_info = $check_stmt->fetch();
            
            if (!$stock_info || $stock_info['stock_quantity'] < $quantity) {
                return false;
            }
            
            $query = "UPDATE " . $this->table . " SET quantity = ? WHERE cart_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$quantity, $cart_id]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function removeItem($cart_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE cart_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$cart_id]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function clearCart($customer_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE customer_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$customer_id]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getCartTotal($customer_id) {
        try {
            $query = "SELECT SUM(c.quantity * p.price) as total 
                      FROM " . $this->table . " c 
                      JOIN products p ON c.product_id = p.product_id 
                      WHERE c.customer_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id]);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch(PDOException $e) {
            return 0;
        }
    }

    public function getCartCount($customer_id) {
        try {
            $query = "SELECT SUM(quantity) as count FROM " . $this->table . " WHERE customer_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$customer_id]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch(PDOException $e) {
            return 0;
        }
    }
}
?>