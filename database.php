<?php

$host = 'localhost:3306'; // Database host
$dbname = 'gradproj1190449_graduation_project'; // Database name
$username = 'gradproj1190449'; // Database username
$password = 'edtB9OPWxy'; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exceptions

    return $pdo;
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage()); // Handle connection errors
}
