<?php
// Start the session to track user login status
session_start();
// Include the database connection file
include('db.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a query to check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    // Execute the query with the provided email
    $stmt->execute(['email' => $email]);
    // Fetch the user record from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the user exists and the password matches
    if ($user && password_verify($password, $user['password'])) {
        // Store the user ID and username in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        // Redirect the user to the users.php page (dashboard or user area)
        header("Location: users.php");
        exit(); // Ensure that the script stops execution after the redirect
    } else {
        // If login fails, display an error message
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   <!-- Add css  -->
    <style>
        body {
    display: flex;
    align-items: center;
    height: 100vh;
}
.container {
    background-color: #ebebeb;
    padding: 50px 30px;
    border-radius: 20px;
    max-width: 420px;
    width: 100%;
    margin: 0 auto;
}
.all-users .container {
    max-width: 600px;
}
.mb-15{
    margin-bottom: 15px;
}
.main-title{
    text-align: center;
    font-size: 32px;
}
.mb-0{
    margin-bottom: 0 !important;
}
.mt-0{
    margin-top: 0 !important;
}
.d-flex {
    display: flex !important;
    justify-content: space-between;
}
.d-block{
    display: block;
}
.flex-wrap{
    flex-wrap: wrap;
}
.align-center{
    align-items: center;
}
.w-50{
    max-width: calc(50% - 7px);
    width: 100%;
}
.w-100{
    width: 100%;
    max-width: 100%;
}
form input {
    min-height: 46px;
    border-radius: 7px;
    border: 1px solid #000;
    background-color: transparent;
    color: #000;
    padding: 0 10px;
    width: calc(100% - 20px);
}
form input[type="submit"], .custom-btn {
    background: #000;
    color: #fff;
    font-size: 18px;
    width: 100%;
    cursor: pointer;
    display: block;
    border-radius: 7px;
    text-align: center;
    text-decoration: none;
    min-height: 46px;
    line-height: 42px;
}
form label {
    font-size: 14px;
    margin-bottom: 7px !important;
    display: block;
}
form input[type="radio"] {
    min-height: auto;
    width: 17px;
    height: 17px;
}
table {
    border-collapse: collapse;
    width: 100%;
}
table th, table td {
    padding: 10px 5px;
}
    </style>
</head>
<body>
    <div class="container">
    <!-- Display the login form -->
    <form method="POST" action="">
        <h2>Login</h2>
        <!-- Display error message if login failed -->
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        
        <!-- Email input field -->
        <div class="w-100 mb-15">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        </div>
        
        <!-- Password input field -->
        <div class="w-100 mb-15">
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        </div>
        <!-- Submit button to log the user in -->
        <div class="w-100">
        <input type="submit" value="Login">
        </div>
        <!-- Link to the signup page if the user doesn't have an account -->
        <p>Don't have an account? <a href="signup.php">Signup</a></p>
    </form>
</div>
</body>
</html>
