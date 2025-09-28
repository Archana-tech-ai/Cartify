<?php
include 'config/db.php';
include 'includes/header.php';

if(!isset($_SESSION['user_id'])){
    echo "<p style='color:red;'>Please login first!</p>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$cart_res = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
$cart = mysqli_fetch_assoc($cart_res);
$cart_id = $cart['id'] ?? 0;

$items = mysqli_query($conn, "SELECT ci.*, p.name, p.price, p.stock 
                             FROM cart_items ci 
                             JOIN products p ON ci.product_id=p.id 
                             WHERE ci.cart_id='$cart_id'");

$total = 0;
while($item = mysqli_fetch_assoc($items)){
    $total += $item['price'] * $item['quantity'];
}

// Fetch user addresses
$addresses = mysqli_query($conn, "SELECT * FROM addresses WHERE user_id='$user_id'");

// Handle order submission
if(isset($_POST['confirm_order'])){
    $address_id = $_POST['address_id'];

    if(empty($address_id)){
        echo "<p style='color:red;'>Please select an address!</p>";
    } else {
        // Insert order
        mysqli_query($conn, "INSERT INTO orders (user_id, address_id, total_amount, status) 
                             VALUES ('$user_id','$address_id','$total','On Process')");
        $order_id = mysqli_insert_id($conn);

        // Insert order items and reduce stock
        $cart_items = mysqli_query($conn, "SELECT * FROM cart_items WHERE cart_id='$cart_id'");
        while($ci = mysqli_fetch_assoc($cart_items)){
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                 VALUES ('$order_id','".$ci['product_id']."','".$ci['quantity']."','".$ci['price']."')");
            mysqli_query($conn, "UPDATE products SET stock = stock - ".$ci['quantity']." WHERE id='".$ci['product_id']."'");
        }

        // Clear cart
        mysqli_query($conn, "DELETE FROM cart_items WHERE cart_id='$cart_id'");
        echo "<p style='color:green;'>Order placed successfully! Your order ID is #$order_id</p>";
    }
}
?>

<h2>Checkout</h2>

<?php if(mysqli_num_rows($items) == 0): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <h3>Select Delivery Address</h3>
    <form method="POST" action="">
        <?php while($addr = mysqli_fetch_assoc($addresses)): ?>
            <input type="radio" name="address_id" value="<?= $addr['id']; ?>"> 
            <?= $addr['address_line']; ?>, <?= $addr['city']; ?>, <?= $addr['state']; ?>, <?= $addr['zip']; ?><br>
        <?php endwhile; ?>
        <br>
        <input type="submit" name="confirm_order" value="Confirm Order">
    </form>
<?php endif; ?>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
