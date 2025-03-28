<?php
    include 'Product.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $stock_quantity = $_POST["stock_quantity"];
        
        // Prepare the statement
        $sql = "INSERT INTO `add-products` (name, description, price, stock_quantity) VALUES ('$name', '$description', '$price', '$stock_quantity')";

        try {
            $conn->exec($sql);
        } catch (PDOException $ex) {
            echo $sql . $ex->getMessage();
        }
        $conn = null;
        exit();
    }
?>
