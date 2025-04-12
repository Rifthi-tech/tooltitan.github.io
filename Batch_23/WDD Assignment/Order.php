<?php
// Database connection using PDO
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "tool_titan"; // Replace with your database name

// Set DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$dbname";

try {
    // Create a PDO instance (connect to the database)
    $conn = new PDO($dsn, $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there is an error in connection, display an error message
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];

    try {
        // Prepare SQL query to insert order into the database
        $stmt = $conn->prepare("INSERT INTO orders (product_name, product_description, product_price, quantity, total_price) 
                                VALUES (:product_name, :product_description, :product_price, :quantity, :total_price)");

        // Bind parameters to the SQL query
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_description', $product_description);
        $stmt->bindParam(':product_price', $product_price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);

        // Execute the statement
        if ($stmt->execute()) {
            $success_message = "Order successfully placed!";
        } else {
            $error_message = "Error placing order. Please try again.";
        }
    } catch (PDOException $e) {
        // If there is an error in the database query, display an error message
        $error_message = "Error: " . $e->getMessage();
    }

    // Close the connection (optional with PDO as it auto-closes)
    $conn = null;
}

// Get product details from the URL
$product_name = isset($_GET['name']) ? $_GET['name'] : '';
$product_description = isset($_GET['description']) ? $_GET['description'] : '';
$product_price = isset($_GET['price']) ? floatval($_GET['price']) : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/order.css">
    <title>Order Page</title>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <div class="main-logo">
        <a href="Homepage.html"><img src="images/LOGO.png" alt="Tool Titan Logo"></a>
    </div>
    <h1>TOOL TITAN</h1>
    <img class="profile" src="images/OIP (7).jpeg" alt="Profile Picture">
</div>

<div class="pro-ord">
    <h2>Product Order</h2>
    <div id="product-details">
        <form action="" method="post">
            <!-- Display Product Information -->
            <h3>Product Name: <span id="product-name"><?php echo htmlspecialchars($product_name); ?></span></h3>
            <p>Description: <span id="product-description"><?php echo htmlspecialchars($product_description); ?></span></p>
            <p>Price: Rs. <span id="product-price"><?php echo number_format($product_price, 2); ?></span></p>
            
            <!-- Quantity Input -->
            <p>Quantity: <input type="number" id="quantity" value="1" min="1" name="quantity"></p>
            
            <!-- Total Price (updated with JS) -->
            <p>Total Price: Rs. <span id="total-price"><?php echo number_format($product_price, 2); ?></span></p>

            <!-- Hidden Inputs to Submit Data -->
            <input type="hidden" id="hidden-product-name" name="name" value="<?php echo htmlspecialchars($product_name); ?>">
            <input type="hidden" id="hidden-product-description" name="description" value="<?php echo htmlspecialchars($product_description); ?>">
            <input type="hidden" id="hidden-product-price" name="price" value="<?php echo number_format($product_price, 2); ?>">
            <input type="hidden" id="hidden-total-price" name="total_price" value="<?php echo number_format($product_price, 2); ?>">

            <!-- Submit Button -->
            <button type="submit" id="confirm-order">Confirm Order</button>
        </form>

        <?php if (isset($success_message)): ?>
            <div id="success-popup" class="popup" style="display:block;">
                <h2><?php echo $success_message; ?></h2>
                <button class="popup-button" onclick="window.location.href='Customer.html'">OK</button>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div id="error-popup" class="popup" style="display:block;">
                <h2><?php echo $error_message; ?></h2>
                <button class="popup-button" onclick="window.location.href='Customer.html'">OK</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="main-footer">
        <div class="footer">
            <div>
                <h1>TOOL TITAN</h1>
                <p>Contact Us:</p>
                <p>+96 456 456 8524</p>
                <p>Email:</p>
                <p>tooltitan@gmail.com</p>
            </div>
            <div>
                <h3>Social Media</h3>
                <p>Facebook</p>
                <p>Twitter</p>
                <p>Instagram</p>
                <p>LinkedIn</p>
                <p>Newsletter</p>
            </div>
        </div>
    </div>
</footer>

<script>
    // Update total price when quantity changes
    const quantityElement = document.getElementById('quantity');
    const totalPriceElement = document.getElementById('total-price');
    const hiddenTotalPriceElement = document.getElementById('hidden-total-price');

    function updateTotalPrice() {
        const productPrice = parseFloat(document.getElementById('hidden-product-price').value);
        const quantity = quantityElement.value;
        const totalPrice = productPrice * quantity;
        totalPriceElement.textContent = totalPrice.toFixed(2);
        hiddenTotalPriceElement.value = totalPrice.toFixed(2);
    }

    quantityElement.addEventListener('input', updateTotalPrice);
    updateTotalPrice();  // Set initial total price
</script>

</body>
</html>
