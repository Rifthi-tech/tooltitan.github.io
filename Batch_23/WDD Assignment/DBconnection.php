<?php
    //DATABASE CONNECTION PDO METHOD
    $servername = "localhost";
    $username = "root";
    $password = ""; // add your database password if you have one
    $dbname = "tool_titan";

        try {
            //coonection using PDO method
            $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);

            //set PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            //if connection fails, shoe the error message
            echo "Connection failed:" . $e->getMessage();
        }
?>