<?php
include 'config/db.php';
include 'includes/header.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])){
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if($user['role'] == 'admin'){
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "<p style='color:red;'>Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red;'>Email not found!</p>";
    }
}
?>

<h2>Login</h2>
<form method="POST" action="">
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <input type="submit" name="login" value="Login">
</form>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
