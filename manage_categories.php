<?php
include 'config/db.php';
include 'includes/header.php';

// Only admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    echo "<p style='color:red;'>Access denied!</p>";
    include 'includes/footer.php';
    exit;
}

// Add Category
if(isset($_POST['add_category'])){
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO categories (name, description) VALUES ('$name','$description')";
    if(mysqli_query($conn, $sql)){
        echo "<p style='color:green;'>Category added successfully!</p>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete Category
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
    header("Location: manage_categories.php");
}

// Fetch categories
$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
?>

<h2>Manage Categories</h2>

<form method="POST" action="">
    Name: <input type="text" name="name" required>
    Description: <textarea name="description" required></textarea>
    <input type="submit" name="add_category" value="Add Category">
</form>

<h3>Existing Categories</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['description']; ?></td>
        <td>
            <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<link rel="stylesheet" href="assets/css/style.css">
<?php include 'includes/footer.php'; ?>
