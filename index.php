<?php
session_start();
include 'config/db.php';

// Search & Sort
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id=c.id 
        WHERE p.stock > 0 AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";

if($sort == 'low') $sql .= " ORDER BY p.price ASC";
elseif($sort == 'high') $sql .= " ORDER BY p.price DESC";
else $sql .= " ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<h1>Product Catalog</h1>

<!-- Search & Sort Form -->
<form method="GET" action="">
    Search: <input type="text" name="search" value="<?= htmlspecialchars($search); ?>">
    Sort: 
    <select name="sort">
        <option value="newest" <?= $sort=='newest'?'selected':''; ?>>Newest</option>
        <option value="low" <?= $sort=='low'?'selected':''; ?>>Price: Low → High</option>
        <option value="high" <?= $sort=='high'?'selected':''; ?>>Price: High → Low</option>
    </select>
    <button type="submit">Apply</button>
</form>

<hr>

<!-- Display Products -->
<div style="display:flex; flex-wrap: wrap;">
<?php while($row = mysqli_fetch_assoc($result)){ ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px; width:200px;">
        <?php if($row['image']){ ?>
            <img src="assets/images/<?= $row['image']; ?>" width="180">
        <?php } ?>
        <h3><?= $row['name']; ?></h3>
        <p>Category: <?= $row['category_name']; ?></p>
        <p>Price: $<?= $row['price']; ?></p>
        <?php if($row['stock'] == 0){ ?>
            <p style="color:red;">Out of Stock</p>
        <?php } else { ?>
            <a href="product_detail.php?id=<?= $row['id']; ?>">View Details</a>
        <?php } ?>
        <link rel="stylesheet" href="assets/css/style.css">
    </div>
<?php } ?>
</div>
