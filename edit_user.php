<?php
session_start(); // Start a new session or resume the existing session
include('db.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if the 'id' parameter is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: users.php"); // Redirect to the users list page if no id is provided
    exit();
}

$user_id = $_GET['id']; // Get the user ID from the URL

// Prepare and execute a query to fetch the user details from the database based on the user ID
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data as an associative array

// Check if the user exists
if (!$user) {
    echo "User not found."; // Display an error if the user is not found
    exit();
}

// Check if the form has been submitted (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Get the username from the form
    $email = $_POST['email']; // Get the email from the form
    $mobile = $_POST['mobile']; // Get the mobile number from the form
    $gender = $_POST['gender']; // Get the gender from the form

    // Server-side validation for user inputs
    if (strlen($username) < 3 || strlen($username) > 20) {
        $error = "Username must be between 3 and 20 characters."; // Error if username length is invalid
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format."; // Error if email format is invalid
    } elseif (!empty($mobile) && strlen($mobile) != 10) {
        $error = "Mobile number must be 10 digits."; // Error if mobile number is not exactly 10 digits
    } else {
        // Check if the email is already taken by another user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND id != :id");
        $stmt->execute(['email' => $email, 'id' => $user_id]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC); // Check if any user has the same email

        if ($existing_user) {
            $error = "Email already exists."; // Error if email is already taken by another user
        } else {
            // Update user details in the database if no errors
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, mobile = :mobile, gender = :gender WHERE id = :id");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'mobile' => $mobile,
                'gender' => $gender,
                'id' => $user_id
            ]);
            header("Location: users.php"); // Redirect to the user list after successful update
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
    <title>Edit User</title>
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
    <h2>Edit User</h2>
    <a   class="mb-15 d-block" href="users.php">Back to User List</a> <!-- Link to go back to the users list -->
    
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?> <!-- Display error message if any -->
    
    <form method="POST" action=""> <!-- Form to update user details -->
      <div class="d-flex mb-15">
        <div class="w-50">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
            </div>
            <div class="w-50">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
        <span id="email-error"></span><br> <!-- Area to show email validation error -->
        </div>
    </div>
    <div class="w-100 mb-15">
        <label for="mobile">Mobile (Optional):</label>
        <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>"><br>
        <span id="mobile-error"></span><br> <!-- Area to show mobile validation error -->
    </div>
    <div class="d-flex flex-wrap mb-15">
                    <div class="w-100">
        <label for="gender">Gender:</label>
        </div>
        <div class="w-100 d-flex">
               <div class="d-flex align-center">Male
                <input type="radio" name="gender" value="Male" <?php echo $user['gender'] == 'Male' ? 'checked' : ''; ?>>
        </div>
            <div class="d-flex align-center">Female
                <input type="radio" name="gender" value="Female" <?php echo $user['gender'] == 'Female' ? 'checked' : ''; ?>>
              </div>  
         <div class="d-flex align-center">Other
            <input type="radio" name="gender" value="Other" <?php echo $user['gender'] == 'Other' ? 'checked' : ''; ?>> 
                 </div>  
        
    </div>
        <input style="margin: 20px 0;" type="submit" value="Update User"> <!-- Submit button -->
    </form>
    </div>
    <!-- jQuery for client-side validation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
     $(document).ready(function() {
        // Email validation on blur (when focus is lost)
        $('#email').on('blur', function() {
            var email = $(this).val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/; // Regular expression for email validation
            if (!emailPattern.test(email)) {
                $('#email-error').text("Invalid email format.").css('color', 'red'); // Show error message
                return;
            }

            // AJAX request to check if the email already exists
            $.ajax({
                url: 'check_email.php', // URL to check email
                method: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response == "Email already exists.") {
                        $('#email-error').text(response).css('color', 'red'); // Show error if email exists
                    } else {
                        $('#email-error').text("Email is available.").css('color', 'green'); // Show success if email is available
                    }
                }
            });
        });

        // Mobile number validation on blur
        $('#mobile').on('blur', function() {
            var mobile = $(this).val();
            var mobilePattern = /^[0-9]{10}$/; // Regular expression for mobile number validation

            if (mobile && !mobilePattern.test(mobile)) {
                $('#mobile-error').text("Mobile number must have exactly 10 digits.").css('color', 'red'); // Show error if mobile number is invalid
            } else {
                $('#mobile-error').text("").css('color', 'green'); // Clear the error message if valid
            }
        });
    });
    </script>

</body>
</html>
