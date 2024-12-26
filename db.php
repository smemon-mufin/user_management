<?php
// Database connection details
$host = 'localhost'; 
$dbname = 'user_management'; 
$username = 'root'; 
$password = ''; 


try {
    // Create a new PDO instance and set the connection attributes
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, catch the exception and display an error message
    echo "Connection failed: " . $e->getMessage();
}
?>
