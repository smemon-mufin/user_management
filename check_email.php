<?php
include('db.php'); // Include the database connection file

// Check if the 'email' parameter is provided via a POST request
if (isset($_POST['email'])) {
    $email = $_POST['email']; // Get the email from the POST request

    // Prepare the SQL query to check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    // Execute the query with the provided email
    $stmt->execute(['email' => $email]);
    // Fetch the result as an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the email is already taken
    if ($user) {
        echo "Email already exists."; // Return a message if the email already exists
    } else {
        echo "Email is available."; // Return a message if the email is not found (i.e., available)
    }
}
?>
