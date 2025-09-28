<?php
include 'config/db.php';

// Create an admin user
$username = "admin";
$email = "admin@test.com";
$password = password_hash("admin123", PASSWORD_DEFAULT); // change password if you want
$role = "admin";

$sql = "INSERT INTO users (username, email, password, role) 
        VALUES ('$username', '$email', '$password', '$role')";

if(mysqli_query($conn, $sql)){
    echo "Admin account created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
