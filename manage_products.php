<?php
include 'config/db.php';
include 'includes/header.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<p style='color:red;'>Access denied!</p>";
    include 'includes/footer.php';
    exit;
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Add Product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);

    if ($category_id == "") {
        echo "<p style='color:red;'>Please select a category!</p>";
    } else {
        // Insert product data (no image)
        $sql = "INSERT INTO products (category_id, name, price, description, stock)
                VALUES ('$category_id', '$name', '$price', '$description', '$stock')";
        if (mysqli_query($conn, $sql)) {
            echo "<p style='color:green;'>✅ Product added successfully!</p>";
        } else {
            echo "<p style='color:red;'>❌ Error: " . mysqli_error($conn) . "</p>";
        }
    }
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: manage_products.php");
    exit;
}

// Fetch products
$prod_result = mysqli_query($conn, "
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
");

// Fetch categories for dropdown
$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
?>

<link rel="stylesheet" href="assets/css/style.css">

<!-- Navigation Buttons -->
<div style="margin-bottom:15px;">
    <a href="dashboard.php" style="background-color:#4CAF50; color:white; padding:8px 15px; text-decoration:none; border-radius:5px; margin-right:10px;">Go to Dashboard</a>
    <a href="?logout=true" style="background-color:#f44336; color:white; padding:8px 15px; text-decoration:none; border-radius:5px;">Logout</a>
</div>

<h2>🛍️ Manage Products</h2>

<form method="POST" action="" style="margin-bottom:30px;">
    <label>Name:</label>
    <input type="text" name="name" required><br><br>

    <label>Category:</label>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
            <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Stock:</label>
    <input type="number" name="stock" required><br><br>

    <label>Description:</label>
    <textarea name="description" required></textarea><br><br>

    <input type="submit" name="add_product" value="Add Product" 
           style="background-color:#2196F3; color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer;">
</form>

<h3>📦 Existing Products</h3>
<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width:100%;">
    <tr style="background-color:#f2f2f2;">
        <th>ID</th>
        <th>Name</th>
        <th>Categoryyy</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($prod_result)): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['category_name']); ?></td>
            <td>$<?= htmlspecialchars($row['price']); ?></td>
            <td><?= htmlspecialchars($row['stock']); ?></td>
            <td>
                <a href="?delete=<?= $row['id']; ?>" 
                   onclick="return confirm('Delete this product?')" 
                   style="color:red;">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
