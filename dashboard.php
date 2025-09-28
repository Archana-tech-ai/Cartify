<?php
// Start session only if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config/db.php';
include 'includes/header.php';

// Only admin can access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    // Redirect non-admin users to login page
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Cartify</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1>Admin Dashboard</h1>

<div style="margin: 20px 0;">
    <a href="manage_categories.php"><button>Manage Categories</button></a>
    <a href="manage_products.php"><button>Manage Products</button></a>
    <a href="manage_orders.php"><button>Manage Orders</button></a>
</div>

<p>Welcome, <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>! Use the buttons above to manage your store.</p>

<footer>
</footer>

</body>
</html>

<?php include 'includes/footer.php'; ?>
