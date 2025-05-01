<?php
session_start();
require '../db.php'; // DB connection file

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$seller_id = $_SESSION['user_id']; // Get logged-in seller ID

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category']) && isset($_FILES['image'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category'];

        // Image upload path
        $upload_dir = "../uploads/products/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $upload_dir . $unique_filename;
        $db_file_path = "uploads/products/" . $unique_filename;
        $uploadOk = 1;

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='error'>File is not an image.</div>";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 5000000) {
            echo "<div class='error'>File too large. Max 5MB.</div>";
            $uploadOk = 0;
        }

        if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<div class='error'>Only JPG, JPEG, PNG, GIF allowed.</div>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // ✅ Include seller_id in product insertion
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, category_id, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $db_file_path, $category_id, $seller_id);

            if ($stmt->execute()) {
                echo "<div class='success'>✅ Product added successfully!</div>";
            } else {
                echo "<div class='error'>❌ Database Error: " . $stmt->error . "</div>";
                unlink($target_file); // Rollback image upload if DB fails
            }
            $stmt->close();
        } else {
            echo "<div class='error'>❌ Failed to upload image.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="./seller.css">
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <header>
        <h1>Add New Product</h1>
    </header>
    <nav>
        <ul>
            <li><a href="sellerdashboard.php">Dashboard</a></li>
            <li><a href="manage.php">Manage Products</a></li>
        </ul>
    </nav>

    <div>
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <label>Product Name:<input type="text" name="name" required></label><br>
            <label>Description:<textarea name="description" required></textarea></label><br>
            <label>Price:<input type="number" name="price" step="0.01" required></label><br>
            <label>Stock:<input type="number" name="stock" min="0" required></label><br>
            <label>Category:
                <select name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category_id']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br>
            <label>Image:<input type="file" name="image" accept="image/*" required></label><br>
            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
