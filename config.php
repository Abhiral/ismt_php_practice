<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "ismt_website";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection Error!" . $conn->connect_error);
    }
?>