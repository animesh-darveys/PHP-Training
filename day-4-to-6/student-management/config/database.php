<?php

$servername = "localhost";
$username = "root";
$password = null;
$dbname = "student_management";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $error) {
    die("Connection failed: " . $error->getMessage());
} 