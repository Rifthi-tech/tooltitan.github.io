<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($name, $description, $price, $stock_quantity, $product_image, $supplier_id) {
        try {
            $query = "INSERT INTO " . $this->table . " (name, description, price, stock_quantity, product_image, supplier_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $description, $price, $stock_quantity, $product_image, $supplier_id]);
        } catch(PDOException $e) {
            error_log("Product Creation Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $query = "SELECT p.*, u.username as supplier_name FROM " . $this->table . " p 
                     LEFT JOIN users u ON p.supplier_id = u.user_id 
                     ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get Products Error: " . $e->getMessage());
            return [];
        }
    }

    public function getBySupplier($supplier_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE supplier_id = ? ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$supplier_id]);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get Supplier Products Error: " . $e->getMessage());
            return [];
        }
    }

    public function getById($product_id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE product_id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$product_id]);
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get Product Error: " . $e->getMessage());
            return false;
        }
    }

    public function update($product_id, $name, $description, $price, $stock_quantity, $product_image = null) {
        try {
            if ($product_image) {
                $query = "UPDATE " . $this->table . " SET name = ?, description = ?, price = ?, stock_quantity = ?, product_image = ? WHERE product_id = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$name, $description, $price, $stock_quantity, $product_image, $product_id]);
            } else {
                $query = "UPDATE " . $this->table . " SET name = ?, description = ?, price = ?, stock_quantity = ? WHERE product_id = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$name, $description, $price, $stock_quantity, $product_id]);
            }
        } catch(PDOException $e) {
            error_log("Product Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($product_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE product_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$product_id]);
        } catch(PDOException $e) {
            error_log("Product Delete Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateStock($product_id, $stock_quantity) {
        try {
            $query = "UPDATE " . $this->table . " SET stock_quantity = ? WHERE product_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$stock_quantity, $product_id]);
        } catch(PDOException $e) {
            error_log("Stock Update Error: " . $e->getMessage());
            return false;
        }
    }
}
?>