<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/form.css">
    <title>Login Form</title>
</head>
<body>
    <!--navbar-->
    <div class="navbar">
        <div class="main-logo">
            <a href="Homepage.html"><img src="images/LOGO.png"></a>
        </div>
        <h1>TOOL TITAN</h1>
        <div class="navbar-link">
            <a href="#Contact Us">Contact Us</a>
        </div>
    </div>
    
    <div class="container">
        <div class="form-box">   
            <form action="credentials.php" method="post"> 
                <header>Login Form</header>
                <input type="text" required placeholder="Enter your Username" name="name">
                <input type="password" required placeholder="Enter Your Password" name="password">
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
    
    <footer>
        <?php
            include 'DBconnection.php';
        ?>
    </footer>
    <!--footer-->
    <div class="main-footer">
        <div class="footer">
            <div>
                <h1>TOOL TITAN</h1>
                <p id="Contact Us">Contact Us:</p>
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
            <img src="images/927487.webp">
            <img src="images/Husqvarna-Logo.png">
            <img src="images/TTI_logo-1-1-1024x1024.webp">
            <img src="images/R.png">
            <img src="images/kisspng-logo-emblem-robert-bosch-gmbh-brand-trademark-222pro-elektronik-5b6f5733abe0a4.913157591534023475704.jpg">
        </div>

        <p class="footer-p">&copy;2024 TOOL TITAN. All rights reserved. Privacy Policy | Terms of Service</p>
    </div>
</body>
</html>
