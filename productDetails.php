<?php
session_start();
include 'db.php';

// Check if product ID is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to index page if no product ID is provided
    header("Location: index.php");
    exit();
}

// Sanitize the product ID to prevent SQL injection
$product_id = intval($_GET['id']);

// Fetch detailed product information with category
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.product_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product exists
if ($result->num_rows === 0) {
    // Redirect to index page if product not found
    header("Location: index.php");
    exit();
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <style>
        .product-details-container {
            max-width: 1000px;
            margin: 2rem auto;
            display: flex;
            gap: 2rem;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-details-image {
            flex: 1;
            max-width: 50%;
        }

        .product-details-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-details-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-details-header {
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .product-details-price {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: bold;
            margin: 1rem 0;
        }

        .product-details-description {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .product-details-metadata {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background-color: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
        }

        .product-details-stock {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .back-button {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .add-to-cart-btn {
            background-color:  #28a745;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #004568;
        }

    </style>
</head>
<body>
    <?php include 'styles/header.php'; ?>
    <header>   
    <div class="header-content">         
         <div class="header-title">             
                <h1>Loomify - Product Details</h1>         
            </div>                  

    

<div class="header-profile">
    <div class="dropdown">
        <button class="profile-btn" id="profileToggle">
            <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Profile" class="profile-icon">
            <?php if(isset($_SESSION['username'])): ?>
                <span class="profile-username"><?php echo "<br>". htmlspecialchars($_SESSION['username']); ?></span>
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
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a class="logoutbtn" href="index.php">Home  </a></li>
                            <li>
                                <a href="Cart.php" class="cart-icon">
                                    My Cart
                                    <?php
                                    $cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
                                    if($cartCount > 0): ?>
                                        <span class="cart-count"><?php echo $cartCount; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li><a href="productCategory.php">Product Categories</a></li>     
                        <?php else: ?>      
                            <li><a href="index.php">Home</a></li>                         
                            <li><a href="register.php">Register</a></li>   
                            <li><a href="productCategory.php">Product Categories</a></li>                  
                        <?php endif; ?>                 
                    </ul>             
                </nav>         
            </div>     
        </div> 
    </header>

    <div class="container">
        <a href="index.php" class="back-button">← Back to Products</a>
        
        <div class="product-details-container">
            <div class="product-details-image">
                <?php if(!empty($product['Image']) && file_exists($product['Image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['Image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div class="error-image">No image available</div>
                <?php endif; ?>
            </div>

            <div class="product-details-info">
                <div class="product-details-header">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                </div>

                <div class="product-details-description">
                    <h3>Description</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                </div>

                <div class="product-details-price">
                    ₨ <?php echo number_format($product['price'], 2); ?>
                </div>

                <div class="product-details-metadata">
                    <div>
                        <strong>Category:</strong> 
                        <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?>
                    </div>
                    <div>
                        <strong>Stock Status:</strong>
                        <span class="product-details-stock <?php 
                            echo ($product['stock'] <= 0 ? 'out-of-stock' : 
                                  ($product['stock'] <= 5 ? 'low-stock' : '')); 
                        ?>">
                            <?php
                            if ($product['stock'] <= 0) {
                                echo 'Out of Stock';
                            } elseif ($product['stock'] <= 5) {
                                echo 'Low Stock: ' . $product['stock'] . ' left';
                            } else {
                                echo 'Available: ' . $product['stock'];
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <form action="Cart.php" method="POST" class="cart-form" style="margin-top: 1.5rem;">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="available_stock" value="<?php echo $product['stock']; ?>">
                    <button type="submit" 
                            class="add-to-cart-btn" 
                            <?php echo ($product['stock'] <= 0 ? 'disabled' : ''); ?>>
                        <?php echo ($product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <?php include 'styles/footer.php'; ?>
        </div>
    </footer>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>