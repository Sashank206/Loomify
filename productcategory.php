<?php
session_start();
include 'db.php';

// Fetch all categories with their products
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT c.category_id, c.name as category_name, 
          p.product_id, p.name as product_name, p.description, p.price, p.Image, p.stock
          FROM categories c
          LEFT JOIN products p ON c.category_id = p.category_id
          WHERE p.name LIKE '%$search_query%' OR p.description LIKE '%$search_query%' OR c.name LIKE '%$search_query%'
          ORDER BY c.name, p.name";
          
$result = $conn->query($query);

// Organize products by category
$categories = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($categories[$row['category_id']])) {
        $categories[$row['category_id']] = [
            'name' => $row['category_name'],
            'products' => []
        ];
    }
    if ($row['product_id']) {  // Only add if product exists
        $categories[$row['category_id']]['products'][] = [
            'id' => $row['product_id'],
            'name' => $row['product_name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['Image'],
            'stock' => $row['stock']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <link rel="stylesheet" href="Styles/header.css">
    <style>
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #f4f4f4;
        }

        .search-container form {
            display: flex;
            width: 100%;
            max-width: 600px;
        }

        .search-input {
            flex-grow: 1;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        .search-button {
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .category-section {
            margin: 2rem 0;
            padding: 1rem;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .category-title {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ddd;
        }

        .category-products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1rem 0;
        }

        .product-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            transition: transform 0.2s;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-image-container {
            height: 200px;
            overflow: hidden;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .error-image {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            color: #666;
        }

        .stock-info {
            margin: 10px 0;
            font-size: 0.9em;
            color: #666;
        }

        .out-of-stock {
            color: #ff0000;
            font-weight: bold;
        }

        .low-stock {
            color: #ffa500;
        }

        .product-description {
            max-height: 55px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.3s ease;
        }

        .product-more {
            color: blue;
            cursor: pointer;
            display: inline-block;
            margin-left: 5px;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
            margin: 0.5rem 0;
        }

        .no-products {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        .search-results-info {
            text-align: center;
            margin-bottom: 1rem;
            color: #666;
        }
        .cart-form {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-to-cart-btn:hover {
            background-color: #004568;
        }
    </style>
</head>
<body>
<header>
    <?php include 'styles/header.php'; ?>
    <div class="header-content">
        <div class="header-title">
            <h1>Product Categories</h1>
        </div>

        <div class="header-profile">
    <div class="dropdown">
        <button class="profile-btn" id="profileToggle">
            <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Profile" class="profile-icon">
            <?php if(isset($_SESSION['username'])): ?>
                <span class="profile-username"><?php echo "<br>Hello,". htmlspecialchars($_SESSION['username']); ?></span>
            <?php endif; ?>
        </button>
        <div class="dropdown-content" id="profileDropdown">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="UserProfile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="admin/AdminLogin.php">Admin</a>
            <?php endif; ?>
        </div>
    </div>
</div>
        <div class="header-navigation">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a class="logoutbtn" href="logout.php">Products</a></li>
                        <li>
                            <a href="Cart.php" class="cart-icon">
                                My Cart
                                <?php
                                $cartCount = 0;
                                if (isset($_SESSION['user_id'])) {
                                    $userId = $_SESSION['user_id'];
                                    $cartQuery = "SELECT COUNT(*) as count FROM cart WHERE user_id = $userId";
                                    $cartResult = $conn->query($cartQuery);
                                    if ($cartResult && $cartRow = $cartResult->fetch_assoc()) {
                                        $cartCount = $cartRow['count'];
                                    }
                                }
                                if ($cartCount > 0): ?>
                                    <span class="cart-count"><?php echo $cartCount; ?></span>
                                <?php endif; ?>

                                
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="productDetails.php">Products</a></li>
                        <li><a href="contact.php">contact Us</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>

<div class="search-container">
    <form action="productCategory.php" method="GET">
        <input type="text" name="search" class="search-input" 
               placeholder="Search products by name, description, or category" 
               value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<?php if (!empty($search_query)): ?>
    <div class="search-results-info">
        <?php 
        $total_products = 0;
        foreach ($categories as $category) {
            $total_products += count($category['products']);
        }
        ?>
        Showing <?php echo $total_products; ?> results for "<?php echo htmlspecialchars($search_query); ?>"
    </div>
<?php endif; ?>

<div class="container">
    <?php if (empty($categories)): ?>
        <div class="no-products">
            <h2>No products found</h2>
        </div>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <section class="category-section">
                <h2 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h2>
                
                <?php if (empty($category['products'])): ?>
                    <div class="no-products">
                        <p>No products available in this category</p>
                    </div>
                <?php else: ?>
                    <div class="category-products">
                        <?php foreach ($category['products'] as $product): ?>
                            <div class="product-item">
                                <div class="product-image-container">
                                    <?php if(!empty($product['image']) && file_exists($product['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            class="product-image">
                                    <?php else: ?>
                                        <div class="error-image">
                                            No image available
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                                <span class="product-more" data-product-id="<?php echo $product['id']; ?>">More Details</span>

                                <div class="stock-info <?php echo ($product['stock'] <= 0 ? 'out-of-stock' : ($product['stock'] <= 5 ? 'low-stock' : '')); ?>">
                                    <?php
                                    if ($product['stock'] <= 0) {
                                        echo 'Out of Stock';
                                    } elseif ($product['stock'] <= 5) {
                                        echo 'Low Stock: ' . $product['stock'] . ' left';
                                    } else {
                                        echo 'Available: ' . $product['stock'];
                                    }
                                    ?>
                                </div>

                                <div class="product-price">
                                    ₨ <?php echo number_format($product['price'], 2); ?>
                                </div>

                                <form action="Cart.php" method="POST" class="cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="available_stock" value="<?php echo $product['stock']; ?>">
                                    <button type="submit" 
                                            class="add-to-cart-btn" 
                                            <?php echo ($product['stock'] <= 0 ? 'disabled' : ''); ?>>
                                        <?php echo ($product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'); ?>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <footer>
        <?php include 'styles/footer.php'; ?>
    </footer>
<script>
    // Redirect to product details page when "More Details" is clicked
    document.querySelectorAll('.product-more').forEach(item => {
        item.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            window.location.href = `productDetails.php?id=${productId}`;
        });
    });
</script>
</body>
</html>