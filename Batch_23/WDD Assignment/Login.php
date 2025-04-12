<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'tool_titan';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['login'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM credentials WHERE username = :username");
    $stmt->bindParam(':username', $inputUsername);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($inputPassword, $user['password'])) {
        // Login successful - set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role to exact pages you specified
        switch ($user['role']) {
            case 'admin':
                header('Location: Admin Dashboard.php');
                break;
            case 'customer':
                header('Location: Customer.html');
                break;
            case 'supplier':
                header('Location: Product.php');
                break;
            default:
                header('Location: Homepage.html');
        }
        exit();
    } else {
        // Login failed - stay on login page with error
        $error = "Invalid username or password";
    }
}
?>
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
            <form id="loginForm" action="login.php" method="post">
                <header>Login Form</header>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <input type="text" required placeholder="Enter your Username" name="username" id="username">
                <!-- Changed password field type from text to password -->
                <input type="password" required placeholder="Enter Your Password" name="password" id="password">
                <button type="submit" name="login">Login</button>
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
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorElement = document.querySelector('.error-message') || document.createElement('div');
            
            errorElement.textContent = '';
            errorElement.className = 'error-message';
            
            // Basic validation
            if (!username) {
                errorElement.textContent = 'Please enter your username';
                if (!document.querySelector('.error-message')) {
                    this.insertBefore(errorElement, this.firstChild.nextSibling);
                }
                e.preventDefault();
                return;
            }
            
            if (!password) {
                errorElement.textContent = 'Please enter your password';
                if (!document.querySelector('.error-message')) {
                    this.insertBefore(errorElement, this.firstChild.nextSibling);
                }
                e.preventDefault();
                return;
            }
            
            // If validation passes, allow form submission
        });
    </script>
</body>
</html>