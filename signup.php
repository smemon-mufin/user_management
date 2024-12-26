<?php
// Include the database connection file
include('db.php');
// Start the session to store messages or errors
session_start();

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];

    // Check if the passwords match
    if ($password != $confirm_password) {
        $error = "Passwords do not match.";
    // Check if the password length is at least 8 characters
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    // Validate email format
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    // Validate mobile number length (optional, but must be 10 digits)
    } elseif (!empty($mobile) && strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits.";
    } else {
        // Check if the username or email already exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the user already exists, display an error message
        if ($user) {
            $error = "Email or Username already exists.";
        } else {
            // Hash the password before saving it in the database for security
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
            // Set a session message for success
            $_SESSION['message'] = "User registered successfully!";
            // Redirect to the login page
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Email validation on losing focus (blur event)
        $('#email').on('blur', function() {
            var email = $(this).val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                // Display error if email format is incorrect
                $('#email-error').text("Invalid email format.").css('color', 'red');
                return;
            }

            // AJAX request to check if the email already exists in the database
            $.ajax({
                url: 'check_email.php', // File to check email
                method: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response == "Email already exists.") {
                        // Display error if email already exists
                        $('#email-error').text(response).css('color', 'red');
                    } else {
                        // Display success message if email is available
                        $('#email-error').text("Email is available.").css('color', 'green');
                    }
                }
            });
        });

        // Mobile validation on losing focus (blur event)
        $('#mobile').on('blur', function() {
            var mobile = $(this).val();
            var mobilePattern = /^[0-9]{10}$/;

            // Validate if the mobile number is exactly 10 digits
            if (mobile && !mobilePattern.test(mobile)) {
                $('#mobile-error').text("Mobile number must have exactly 10 digits.").css('color', 'red');
            } else {
                // Clear the error message if the mobile number is valid
                $('#mobile-error').text("").css('color', 'green');
            }
        });
    });
    </script>
</head>
<body>
    <!-- Form for signup -->
    <div class="container">
    <form method="POST" action="">
        <h2>Signup</h2>
        
        <!-- Display error message if any error occurred during validation -->
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <div class="d-flex mb-15">
        <!-- Username input field -->
          <div  class="w-50">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
    </div>
        <!-- Email input field -->
        <div  class="w-50">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <!-- Display error message for invalid or duplicate email -->
        <span id="email-error"></span><br>
        </div>
        </div>
        
        <div class="d-flex mb-15">
        <!-- Password input field -->
        <div class="w-50">
        <label for="password">Password:</label>
        <input type="password" name="password" required>
       </div>
        <!-- Confirm password input field -->
         <div class="w-50">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        </div>
        </div>
        <div class="w-100 mb-15">
        <!-- Mobile number input field (optional) -->
        <label for="mobile">Mobile (Optional):</label>
        <input type="text" id="mobile"  name="mobile">
        <!-- Display error message for invalid mobile number -->
        <span id="mobile-error"></span>
        </div>

        <div class="d-flex flex-wrap mb-15">
            <div class="w-100">
            <!-- Gender selection radio buttons -->
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
                
            </div>
        <!-- Submit button to submit the form -->
        <input type="submit" value="Signup">

        <!-- Link to the login page -->
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>
</body>
</html>
