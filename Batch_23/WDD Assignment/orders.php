<?php
// Include the database connection file
include 'DBconnection.php'; // Ensure DB connection details are correct

// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Ensure that database connection variables are properly defined in 'DBconnection.php'
        if (!isset($host) || !isset($dbname) || !isset($username) || !isset($password)) {
            throw new Exception('Database connection details are missing.');
        }

        // Establish the database connection using PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get and sanitize data from the form to avoid SQL injection
        $product_name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $product_description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
        $product_price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;

        // Validate input data
        if (empty($product_name) || empty($product_description) || $product_price <= 0 || $quantity <= 0 || $total_price <= 0) {
            throw new Exception('Invalid input data. Please check your input fields.');
        }

        // Prepare the SQL query to insert order details into the database
        $stmt = $pdo->prepare("INSERT INTO orders (product_name, product_description, product_price, quantity, total_price)
                               VALUES (:product_name, :product_description, :product_price, :quantity, :total_price)");

        // Bind the parameters to the prepared statement
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_description', $product_description);
        $stmt->bindParam(':product_price', $product_price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Order placed successfully!";
        } else {
            echo "Failed to place the order. Please try again.";
        }

    } catch (PDOException $e) {
        // Handle any PDO exceptions (like database connection issues)
        echo "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        // Handle other exceptions (like validation errors)
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle the case when no data is submitted
    echo "No data submitted.";
}
?>
