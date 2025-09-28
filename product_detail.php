<?php
include 'config/db.php';
include 'includes/header.php';

// Get product ID
if(!isset($_GET['id'])){
    echo "<p style='color:red;'>Product not found!</p>";
    include 'includes/footer.php';
    exit;
}

$id = $_GET['id'];
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id=c.id
        WHERE p.id='$id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "<p style='color:red;'>Product not found!</p>";
    include 'includes/footer.php';
    exit;
}

$product = mysqli_fetch_assoc($result);

// Handle Add to Cart
if(isset($_POST['add_cart'])){
    if(!isset($_SESSION['user_id'])){
        echo "<p style='color:red;'>Please login first!</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $qty = $_POST['quantity'];

        // Check if user has cart
        $cart_check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
        if(mysqli_num_rows($cart_check) == 0){
            mysqli_query($conn, "INSERT INTO cart (user_id) VALUES ('$user_id')");
        }
        $cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'");
        $cart_data = mysqli_fetch_assoc($cart);
        $cart_id = $cart_data['id'];

        // Add to cart_items
        $item_check = mysqli_query($conn, "SELECT * FROM cart_items WHERE cart_id='$cart_id' AND product_id='$id'");
        if(mysqli_num_rows($item_check) > 0){
            mysqli_query($conn, "UPDATE cart_items SET quantity = quantity + $qty WHERE cart_id='$cart_id' AND product_id='$id'");
        } else {
            mysqli_query($conn, "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ('$cart_id','$id','$qty')");
        }
        echo "<p style='color:green;'>Product added to cart!</p>";
    }
}

// Handle Add to Wishlist
if(isset($_POST['add_wishlist'])){
    if(!isset($_SESSION['user_id'])){
        echo "<p style='color:red;'>Please login first!</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $wishlist_check = mysqli_query($conn, "SELECT * FROM wishlists WHERE user_id='$user_id' AND product_id='$id'");
        if(mysqli_num_rows($wishlist_check) == 0){
            mysqli_query($conn, "INSERT INTO wishlists (user_id, product_id) VALUES ('$user_id','$id')");
            echo "<p style='color:green;'>Added to wishlist!</p>";
        } else {
            echo "<p style='color:orange;'>Already in wishlist!</p>";
        }
    }
}
?>

<h2><?= $product['name']; ?></h2>
<p>Category: <?= $product['category_name']; ?></p>
<p>Price: $<?= $product['price']; ?></p>
<p>Stock: <?= $product['stock'] > 0 ? $product['stock'] : 'Out of Stock'; ?></p>
<?php if($product['image']): ?>
    <img src="assets/images/<?= $product['image']; ?>" width="300">
<?php endif; ?>
<p><?= $product['description']; ?></p>

<?php if($product['stock'] > 0): ?>
<form method="POST" action="">
    Quantity: <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock']; ?>">
    <input type="submit" name="add_cart" value="Add to Cart">
    <input type="submit" name="add_wishlist" value="Add to Wishlist">
</form>
<?php else: ?>
<p style="color:red;">Product is out of stock!</p>
<?php endif; ?>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
