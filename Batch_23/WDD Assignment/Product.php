<?php
session_start();
include 'DBconnection.php';

// Check if user is logged in and is supplier
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock = $_POST['stock_quantity'] ?? 0;

    try {
        // Generate a new product ID
        $newId = 'P' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        // Insert new product
        $stmt = $conn->prepare("INSERT INTO products (id, name, description, price, stock_quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$newId, $name, $description, $price, $stock]);
        
        $success_message = "Product added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding product: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Supplier Form</title>
</head>
<body>
    <!--navbar-->
    <div class="navbar">
        <div class="main-logo">
            <a href="Homepage.html"><img src="images/LOGO.png" alt="Tool Titan Logo"></a>
        </div>
        <h1>TOOL TITAN</h1>
        <img class="profile" src="images/OIP (8).jpeg" alt="Profile Picture">
    </div>

    <!-- Form Container -->
    <div class="container">
        <div class="form-box">
            <form action="Product.php" method="post"> 
                <h1>Add Products</h1>
                
                <?php if (isset($success_message)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <input type="text" required placeholder="Enter Product Name" name="name">
                <textarea placeholder="Enter Product Description" name="description" required></textarea>
                <input type="number" step="0.01" placeholder="Enter Price" name="price" required>
                <input type="number" placeholder="Enter Stock Quantity" name="stock_quantity" required>
                <button type="submit">Add Product</button> 
            </form>
        </div>
    </div>
    
    <!--footer-->
    <div class="main-footer">
        <div class="footer">
            <div>
                <h1>TOOL TITAN</h1>
                <p id="Contact-Us">Contact Us:</p>
                <p>+96 456 456 8524</p>
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
            <div>
                <h3>Customer Service</h3>
                <p>FAQs</p>
                <p>Shipping & Returns</p>
                <p>Warranty Information</p>
                <p>24/7 Customer Support</p>
            </div>
            <div>
                <h3>Resources</h3>
                <p>Blog</p>
                <p>Tutorials</p>
                <p>Product Manuals</p>
                <p>Safety Guidelines</p>
            </div>
            <div>
                <h3>Legal</h3>
                <p>Privacy Policy</p>
                <p>Terms of Service</p>
                <p>Cookie Policy</p>
                <p>Accessibility Statements</p>
            </div>
        </div>

        <div class="partners">
            <h1 class="name">Partners</h1>
            <img src="images/927487.webp" alt="Partner 1">
            <img src="images/Husqvarna-Logo.png" alt="Partner 2">
            <img src="images/TTI_logo-1-1-1024x1024.webp" alt="Partner 3">
            <img src="images/R.png" alt="Partner 4">
            <img src="images/kisspng-logo-emblem-robert-bosch-gmbh-brand-trademark-222pro-elektronik-5b6f5733abe0a4.913157591534023475704.jpg" alt="Partner 5">
        </div>
        <p class="footer-p">&copy;2024 TOOL TITAN. All rights reserved. &nbsp;&nbsp;Privacy Policy&nbsp;&nbsp;Terms of Service</p>
    </div>

    <script>
        // Client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = this.elements['name'].value.trim();
            const description = this.elements['description'].value.trim();
            const price = parseFloat(this.elements['price'].value);
            const stock = parseInt(this.elements['stock_quantity'].value);
            
            let error = '';
            
            if (!name) {
                error = 'Please enter a product name';
            } else if (!description) {
                error = 'Please enter a product description';
            } else if (isNaN(price) || price <= 0) {
                error = 'Please enter a valid price';
            } else if (isNaN(stock) || stock < 0) {
                error = 'Please enter a valid stock quantity';
            }
            
            if (error) {
                e.preventDefault();
                alert(error);
            }
        });
    </script>
</body>
</html>