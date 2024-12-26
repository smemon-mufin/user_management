<?php
session_start();
include('db.php');  // Include the database connection file

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Process form submission when the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];

    // Validate the form input
    if ($password != $confirm_password) {
        $error = "Passwords do not match.";  // Check if passwords match
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";  // Check if password is at least 8 characters long
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";  // Check if email has a valid format
    } elseif (!empty($mobile) && strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits.";  // Check if mobile number has exactly 10 digits
    } else {
        // Check if the email already exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $error = "Email already exists.";  // If email exists, display error message
        } else {
            // Hash the password before storing it in the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert the new user data into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, mobile, gender) VALUES (:username, :email, :password, :mobile, :gender)");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'mobile' => $mobile,
                'gender' => $gender
            ]);
            // Redirect to users list page after successful registration
            header("Location: users.php");  
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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
    <h2>Add New User</h2>
    <!-- User registration form -->
    <form method="POST" action="">

        <?php 
    // Display any error messages if validation fails
    if (isset($error)) { 
        echo "<p style='color:red;'>$error</p>"; 
    } 
    ?>
        <div class="d-flex mb-15">
        
        <div class="w-50">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        </div>
        
        <div class="w-50">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <span id="email-error"></span><br> <!-- For displaying email validation error -->
        </div>

    </div>

    <div class="d-flex mb-15">
            <div class="w-50">
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
    </div>
        <div class="w-50">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>
        </div>

        </div>
        <div class="w-100 mb-15">
        <label for="mobile">Mobile (Optional):</label>
        <input type="text" id="mobile" name="mobile"><br>
        <span id="mobile-error"></span><br> <!-- For displaying mobile validation error -->
        </div>

        <div class="d-flex flex-wrap mb-15">
            <div class="w-100">
            <label for="gender">Gender:</label>
        </div>
        <div class="w-100 d-flex">
            <div class="d-flex align-center">Male
                        <input type="radio" name="gender" value="Male" required>
                    </div>
                    <div class="d-flex align-center">Female
                        <input type="radio" name="gender" value="Female" required>
                    </div>
                    <div class="d-flex align-center">Other
                        <input type="radio" name="gender" value="Other" required>
                    </div>
        </div>
        

        <input class="custom-btn" style="margin: 20px 0;" type="submit" value="Add User">
        
    
    </form>

    <!-- Link to go back to the users list -->
    <a href="users.php">Back to User List</a>

    <!-- jQuery inclusion for client-side validation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            
            // Email validation and availability check
            $('#email').on('blur', function() {
                var email = $(this).val();
                var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                
                // Check if email is in a valid format
                if (!emailPattern.test(email)) {
                    $('#email-error').text("Invalid email format.").css('color', 'red');
                    return;
                }

                // Check if email already exists by sending an AJAX request to the server
                $.ajax({
                    url: 'check_email.php',
                    method: 'POST',
                    data: { email: email },
                    success: function(response) {
                        if (response == "Email already exists.") {
                            $('#email-error').text(response).css('color', 'red');
                        } else {
                            $('#email-error').text("Email is available.").css('color', 'green');
                        }
                    }
                });
            });

            // Mobile number validation
            $('#mobile').on('blur', function() {
                var mobile = $(this).val();
                var mobilePattern = /^[0-9]{10}$/;

                // Check if mobile number has exactly 10 digits
                if (mobile && !mobilePattern.test(mobile)) {
                    $('#mobile-error').text("Mobile number must have exactly 10 digits.").css('color', 'red');
                } else {
                    $('#mobile-error').text("").css('color', 'green'); // Clear the error message if valid
                }
            });

        });
    </script>
</div>
</body>
</html>
