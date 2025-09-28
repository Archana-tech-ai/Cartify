<?php
include 'config/db.php';
include 'includes/header.php';

// Handle form submission
if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = 'user'; // Default role

    // Check if email exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        echo "<p style='color:red;'>Email already registered!</p>";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (username,email,password,role) VALUES ('$username','$email','$hash','$role')");
        echo "<p style='color:green;'>Registration successful! <a href='login.php'>Login here</a></p>";
    }
}
?>

<h2>User Registration</h2>

<form method="POST" action="">
    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="register" value="Register">
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
