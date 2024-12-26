<?php
session_start(); // Start a new session or resume the existing session
include('db.php'); // Include the database connection file

// Check if the user is logged in by verifying if 'user_id' is set in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the 'id' parameter is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: users.php"); // Redirect to the users list page if 'id' is not provided
    exit();
}

$user_id = $_GET['id']; // Get the user ID from the URL

// Prepare and execute a query to delete the user from the database
$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]); // Execute the delete query using the user ID

// Redirect back to the user list page after successful deletion
header("Location: users.php");
exit();
?>
