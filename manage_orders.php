<?php
include 'config/db.php';
include 'includes/header.php';

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "<p style='color:red;'>Access denied! Admins only.</p>";
    include 'includes/footer.php';
    exit;
}

// Fetch all orders with user + address details
$sql = "SELECT o.*, 
               u.username, 
               a.address_line1, 
               a.address_line2, 
               a.city, 
               a.state, 
               a.postal_code, 
               a.country
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN addresses a ON o.address_id = a.id
        ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query failed: " . mysqli_error($conn));
}
?>

<h2>Manage Orders</h2>

<?php if(mysqli_num_rows($result) == 0): ?>
    <p>No orders found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:10px;">
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Created At</th>
            <th>Delivery Address</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td>$<?= htmlspecialchars($row['total_amount']); ?></td>
                <td><?= htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <?= htmlspecialchars($row['address_line1']); ?>
                    <?= htmlspecialchars($row['address_line2']); ?>,
                    <?= htmlspecialchars($row['city']); ?>,
                    <?= htmlspecialchars($row['state']); ?>,
                    <?= htmlspecialchars($row['postal_code']); ?>,
                    <?= htmlspecialchars($row['country']); ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
