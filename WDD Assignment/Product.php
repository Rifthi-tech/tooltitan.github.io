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
            <form action="add-products.php" method="post"> 
                <h1>Add Products</h1>
                <input type="text" required placeholder="Enter Product Name" name="name">
                <input type="text" placeholder="Enter your Description" name="description" required>
                <input type="number" step="0.01" placeholder="Enter your Price" name="price" required>
                <input type="number" placeholder="Enter your Stock Quantity" name="stock_quantity" required>
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
                <p>LinkedIn</p> <!-- Corrected typo: "Linkein" to "LinkedIn" -->
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
                <p>Safety Guidelines</p> <!-- Corrected typo: "Safty" to "Safety" -->
            </div>
            <div>
                <h3>Legal</h3>
                <p>Privacy Policy</p>
                <p>Terms of Service</p> <!-- Corrected typo: "Terms od Service" to "Terms of Service" -->
                <p>Cookie Policy</p>
                <p>Accessibility Statements</p> <!-- Corrected typo: "Statments" to "Statements" -->
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

    <!-- Include DB connection file -->
    <?php include 'DBconnection.php'; ?>
</body>
</html>
