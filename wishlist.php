<?php
include 'config/db.php';
include 'includes/header.php';

if(!isset($_SESSION['user_id'])){
    echo "<p style='color:red;'>Please login first!</p>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Remove item from wishlist
if(isset($_GET['remove'])){
    $item_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM wishlists WHERE id='$item_id'");
    header("Location: wishlist.php");
}

// Fetch wishlist items
$items = mysqli_query($conn, "SELECT w.id as wishlist_id, p.* 
                             FROM wishlists w 
                             JOIN products p ON w.product_id = p.id 
                             WHERE w.user_id='$user_id'");
?>

<h2>Your Wishlist</h2>

<?php if(mysqli_num_rows($items) == 0): ?>
    <p>Your wishlist is empty.</p>
<?php else: ?>
<div style="display:flex; flex-wrap:wrap;">
    <?php while($item = mysqli_fetch_assoc($items)): ?>
        <div class="product-card">
            <?php if($item['image']): ?>
                <img src="assets/images/<?= $item['image']; ?>" alt="<?= $item['name']; ?>">
            <?php endif; ?>
            <h3><?= $item['name']; ?></h3>
            <p>Price: $<?= $item['price']; ?></p>
            <?php if($item['stock'] > 0): ?>
                <a href="product_detail.php?id=<?= $item['id']; ?>"><button>View</button></a>
            <?php else: ?>
                <span style="color:red;">Out of Stock</span>
            <?php endif; ?>
            <br><br>
            <a href="?remove=<?= $item['wishlist_id']; ?>" onclick="return confirm('Remove from wishlist?')">Remove</a>
        </div>
    <?php endwhile; ?>
</div>
<?php endif; ?>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
