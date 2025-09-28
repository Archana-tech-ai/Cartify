<?php
// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config/db.php';
include 'includes/header.php';

// Make sure user is logged in
if(!isset($_SESSION['user_id'])){
    echo "<p style='color:red;'>Please login first!</p>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user orders with delivery address
$sql_orders = "SELECT o.*, 
                      a.address_line1, 
                      a.address_line2, 
                      a.city, 
                      a.state, 
                      a.postal_code, 
                      a.country
               FROM orders o
               JOIN addresses a ON o.address_id = a.id
               WHERE o.user_id = '$user_id'
               ORDER BY o.created_at DESC";

$orders = mysqli_query($conn, $sql_orders);

if(!$orders){
    die("Query failed: " . mysqli_error($conn));
}
?>

<h2>Your Orders</h2>

<?php if(mysqli_num_rows($orders) == 0): ?>
    <p>You have no orders yet.</p>
<?php else: ?>
    <?php while($order = mysqli_fetch_assoc($orders)): ?>
        <div class="order-card" style="border:1px solid #ccc; padding:15px; margin-bottom:10px;">
            <h3>Order #<?= $order['id']; ?> - <?= htmlspecialchars($order['status']); ?></h3>
            <p>Placed on: <?= htmlspecialchars($order['created_at']); ?></p>
            <p>Delivery Address: 
                <?= htmlspecialchars($order['address_line1']); ?> 
                <?= htmlspecialchars($order['address_line2']); ?>, 
                <?= htmlspecialchars($order['city']); ?>, 
                <?= htmlspecialchars($order['state']); ?>, 
                <?= htmlspecialchars($order['postal_code']); ?>, 
                <?= htmlspecialchars($order['country']); ?>
            </p>

            <?php
            // Fetch items in this order
            $sql_items = "SELECT oi.*, p.name 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id
                          WHERE oi.order_id = '".$order['id']."'";
            $items = mysqli_query($conn, $sql_items);

            if(!$items){
                die("Query failed: " . mysqli_error($conn));
            }
            ?>
            <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%;">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td><?= htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?= htmlspecialchars($item['price']); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="2" style="text-align:right;"><strong>Total:</strong></td>
                    <td><strong>$<?= htmlspecialchars($order['total_amount']); ?></strong></td>
                </tr>
            </table>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
