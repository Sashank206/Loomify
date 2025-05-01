<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../SellerLogin.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Fetch product only if it belongs to the seller
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = ? AND p.seller_id = ?");
    $stmt->bind_param("ii", $product_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        header("Location: manage.php?error=unauthorized");
        exit();
    }
} else {
    header("Location: manage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);

    $update_query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', category_id='$category_id' WHERE product_id=$product_id AND seller_id=$seller_id";
    
    if ($conn->query($update_query)) {
        header("Location: manage.php?success=1");
        exit();
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link rel="stylesheet" href="seller.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<header>
    <h1>Update Your Product</h1>
</header>
<nav>
    <ul>
        <li><a href="sellerdashboard.php">Dashboard</a></li>
        <li><a href="add.php">Add Product</a></li>
        <li><a href="manage.php">View Products</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="form-container">
    <form action="" method="POST">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

        <label for="category">Category:</label>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Current Image:</label><br>
        <?php 
        $image_path = '../' . $product['Image']; 
        if (!empty($product['Image']) && file_exists($image_path)): ?>
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Current Product Image" class="product-image">
        <?php else: ?>
            <div>No image available</div>
        <?php endif; ?>

        <!-- Optional: Add image update support later -->

        <input type="submit" name="update_product" value="Update Product">
    </form>
</div>
</body>
</html>
