<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$seller_name = $conn->query("SELECT username FROM users WHERE user_id = $seller_id")->fetch_assoc()['username'];

// Fetch categories
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);

// Fetch this seller's products
$products = $conn->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    WHERE p.seller_id = $seller_id
    ORDER BY c.name
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Seller</title>
    <link rel="stylesheet" href="./seller.css">
    <style>
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: rgb(78, 108, 240);
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons input {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .action-buttons .update {
            background-color: #4CAF50;
            color: white;
        }
        .action-buttons .delete {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($seller_name); ?> (Seller)</h1>
    </header>
    <nav>
        <ul>
            <li><a href="sellerdashboard.php">Dashboard</a></li>
            <li><a href="manage.php">Manage Products</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Your Products</h2>

        <?php foreach ($categories as $category): ?>
            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <?php if ($product['category_id'] == $category['category_id']): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td>₨ <?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <?php
                                    $image_path = isset($product['image']) ? '../' . $product['image'] : '';
                                    if (!empty($image_path) && file_exists($image_path)): 
                                ?>
                                    <img src="<?php echo $image_path; ?>" alt="Product Image" class="product-image">
                                <?php else: ?>
                                    <div>Image not found</div>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <form action="edit.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="submit" class="update" value="Update">
                                </form>
                                <form action="delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="submit" class="delete" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>

        <div style="margin-top: 20px;">
            <form action="AddProduct.php" method="POST">
                <input type="submit" value="Add New Product" style="padding: 10px 20px; background-color: #0066cc; color: white; border: none; border-radius: 4px;">
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.product-image').forEach(img => {
            img.onerror = function () {
                this.src = 'placeholder.jpg';
                this.alt = 'Image not found';
            };
        });
    </script>
</body>
</html>
