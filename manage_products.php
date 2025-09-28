<?php
include 'config/db.php';
include 'includes/header.php';

// Only admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "<p style='color:red;'>Access denied!</p>";
    include 'includes/footer.php';
    exit;
}

// Add Product
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    if($category_id == ""){
        echo "<p style='color:red;'>Please select a category!</p>";
    } else {
        if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
            $image = time() . "_" . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "assets/images/".$image);
        } else {
            $image = "";
        }

        $sql = "INSERT INTO products (category_id, name, price, description, stock, image) 
                VALUES ('$category_id', '$name', '$price', '$description', '$stock', '$image')";
        if(mysqli_query($conn, $sql)){
            echo "<p style='color:green;'>Product added successfully!</p>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Delete Product
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: manage_products.php");
}

// Fetch products
$prod_result = mysqli_query($conn, "SELECT p.*, c.name AS category_name 
                                   FROM products p 
                                   LEFT JOIN categories c ON p.category_id=c.id
                                   ORDER BY p.id DESC");

// Fetch categories for dropdown
$cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
?>

<h2>Manage Products</h2>

<form method="POST" action="" enctype="multipart/form-data">
    Name: <input type="text" name="name" required>
    Category: 
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php while($cat = mysqli_fetch_assoc($cat_result)): ?>
            <option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
        <?php endwhile; ?>
    </select>
    Price: <input type="number" step="0.01" name="price" required>
    Stock: <input type="number" name="stock" required>
    Description: <textarea name="description" required></textarea>
    Image: <input type="file" name="image">
    <input type="submit" name="add_product" value="Add Product">
</form>

<h3>Existing Products</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($prod_result)): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['category_name']; ?></td>
        <td><?= $row['price']; ?></td>
        <td><?= $row['stock']; ?></td>
        <td>
            <?php if($row['image']): ?>
                <img src="assets/images/<?= $row['image']; ?>" width="50">
            <?php endif; ?>
        </td>
        <td>
            <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
