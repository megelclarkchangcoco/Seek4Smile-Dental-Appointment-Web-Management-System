<?php 

    // php/connection.php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "seek4smile_database";

    // Create connection
    $connection = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

?>