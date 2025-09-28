<?php
include 'config/db.php';
include 'includes/header.php';

if(!isset($_SESSION['user_id'])){
    echo "<p style='color:red;'>Please login first!</p>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's cart
$cart_res = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
$cart = mysqli_fetch_assoc($cart_res);
$cart_id = $cart['id'] ?? 0;

// Handle update quantity
if(isset($_POST['update_cart'])){
    foreach($_POST['quantities'] as $item_id => $qty){
        mysqli_query($conn, "UPDATE cart_items SET quantity='$qty' WHERE id='$item_id'");
    }
    echo "<p style='color:green;'>Cart updated!</p>";
}

// Handle remove item
if(isset($_GET['remove'])){
    $item_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart_items WHERE id='$item_id'");
    header("Location: cart.php");
}

// Fetch cart items
$items = mysqli_query($conn, "SELECT ci.*, p.name, p.price, p.stock 
                             FROM cart_items ci 
                             JOIN products p ON ci.product_id=p.id 
                             WHERE ci.cart_id='$cart_id'");

$total = 0;
?>

<h2>Your Cart</h2>

<?php if(mysqli_num_rows($items) == 0): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
<form method="POST" action="">
<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>
    <?php while($item = mysqli_fetch_assoc($items)): 
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
    ?>
    <tr>
        <td><?= $item['name']; ?></td>
        <td>$<?= $item['price']; ?></td>
        <td>
            <input type="number" name="quantities[<?= $item['id']; ?>]" value="<?= $item['quantity']; ?>" min="1" max="<?= $item['stock']; ?>">
        </td>
        <td>$<?= $subtotal; ?></td>
        <td><a href="?remove=<?= $item['id']; ?>" onclick="return confirm('Remove this item?')">Remove</a></td>
    </tr>
    <?php endwhile; ?>
    <tr>
        <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
        <td colspan="2"><strong>$<?= $total; ?></strong></td>
    </tr>
</table>
<input type="submit" name="update_cart" value="Update Cart">
<a href="checkout.php"><button>Proceed to Checkout</button></a>
</form>
<?php endif; ?>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
