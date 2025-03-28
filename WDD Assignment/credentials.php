<?php
    include 'Login.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST["name"];
        $password = $_POST["password"];
        
        
        //prepare the statement
        $sql = "INSERT INTO credentials (name, password) VALUES ('$name','$password')";

            try {
                $conn->exec($sql);

            } catch (PDOException $ex) {
                echo $sql . $ex->getMessage();
            }
            $conn = null;
            exit();
}
?>