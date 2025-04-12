<?php
session_start();
include 'DBconnection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['error' => 'Unauthorized']));
}

$orderId = $_GET['order_id'] ?? '';
if (empty($orderId)) {
    die(json_encode(['error' => 'Order ID is required']));
}

try {
    // Get order info
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        die(json_encode(['error' => 'Order not found']));
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'order' => $order
    ]);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
}
?>