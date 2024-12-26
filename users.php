<?php
// Start a session to manage user authentication and data
session_start();

// Include the database connection file
include('db.php');

// Check if the user is logged in by verifying the 'user_id' session variable
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // End the script to prevent further execution
}

// Fetch all users from the 'users' table
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the result as an associative array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        /* Styling for the table */
        table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* Remove space between table borders */
        }
        table, th, td {
            border: 1px solid black; /* Add borders to the table, headers, and cells */
        }
        th, td {
            padding: 8px; /* Add padding inside table cells */
            text-align: left; /* Align text to the left */
        }
        th {
            background-color: #f2f2f2; /* Light background color for the header */
        }
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
<body class="all-users">
    <div class="container">
    <!-- Display a welcome message with the logged-in user's name -->
    <h2 class="main-title">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
    
    <!-- Provide a link to logout -->
    <a style="float: right;" href="logout.php">Logout</a>
    
    
    <!-- Provide a link to add a new user -->
    <a class="mb-15 d-block" href="add_user.php">Add New User</a>
    
    <!-- Create a table to display the users' information -->
    <table class="mb-15" border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Gender</th>
                <th>Actions</th> <!-- Column for action links (Edit/Delete) -->
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop through each user and display their information in a table row
            foreach ($users as $user) { 
            ?>
                <tr>
                    <!-- Display user details in respective columns -->
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($user['gender']); ?></td>
                    <td>
                        <!-- Links for editing or deleting a user, with confirmation on delete -->
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> |
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
