<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        try {
            $query = "SELECT user_id, username, password, role FROM " . $this->table . " WHERE username = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                if ($password === $user['password']) { // In production, use password_verify()
                    return $user;
                }
            }
            return false;
        } catch(PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }

    public function create($username, $password, $role) {
        try {
            $query = "INSERT INTO " . $this->table . " (username, password, role) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$username, $password, $role]);
        } catch(PDOException $e) {
            error_log("User Creation Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $query = "SELECT user_id, username, role, created_at FROM " . $this->table . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get Users Error: " . $e->getMessage());
            return [];
        }
    }

    public function update($user_id, $username, $role, $password = null) {
        try {
            if ($password) {
                $query = "UPDATE " . $this->table . " SET username = ?, role = ?, password = ? WHERE user_id = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$username, $role, $password, $user_id]);
            } else {
                $query = "UPDATE " . $this->table . " SET username = ?, role = ? WHERE user_id = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$username, $role, $user_id]);
            }
        } catch(PDOException $e) {
            error_log("User Update Error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($user_id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$user_id]);
        } catch(PDOException $e) {
            error_log("User Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
?>