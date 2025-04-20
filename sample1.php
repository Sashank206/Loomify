<?php 
session_start(); 
include 'db.php';  

$search_query = $_GET['search'] ?? '';
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        WHERE p.stock > 0";

if (!empty($search_query)) {
    $search_query_safe = $conn->real_escape_string($search_query);
    $sql .= " AND (p.name LIKE '%$search_query_safe%' OR p.description LIKE '%$search_query_safe%' OR c.name LIKE '%$search_query_safe%')";
}

$products = $conn->query($sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LOOMify - Handmade Goods</title>
  <script src="https://cdn.tailwindcss.com"></script>
<style>
    body { font-family: 'Poppins', sans-serif; }
    .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            padding: 20px;
    }
    .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1), -5px -5px 15px rgba(255, 255, 255, 0.7);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.2), -10px -10px 20px rgba(255, 255, 255, 0.8);
    }
    .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
            margin: 0 auto 1rem auto;
            display: block;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .product-card:hover img {
            transform: scale(1.05);
    }
    .product-card h3 {
            font-size: 1.3rem;
            margin: 10px 0;
            color: #333;
    }
    .product-card .price {
            font-weight: bold;
            color: #2874f0;
            margin: 10px 0;
    }
    .product-card .stock-info {
            font-size: 0.9rem;
            margin-bottom: 15px;
            color: #555;
    }
    .product-card form button {
            background: #2874f0;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
    }
    .product-card form button:hover {
            background: #1a5bb8;
            transform: scale(1.05);
    }
    .product-card form button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
    }
    .cart-count {
            background: red;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 0.8em;
            margin-left: 4px;
    }
    /* Header container */
    /* Main Header Wrapper */
.header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    /* rgb(244, 202, 171) */
    background: linear-gradient(to right,rgb(249, 169, 108), #fdf0e6);
    padding: 15px 30px;
    border-bottom: 2px solid #ddd;
    position: relative;
    color: white;
}

/* Left logo */
.logo-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ffffffaa;
    background-color: white;
}

/* Center title */
.header-title {
    text-align: center;
    flex-grow: 1;
    transform: translateX(-60px);
}

.header-title h1 {
    font-size: 28px;
    font-weight: bold;
    margin: 0;
    color: white;
}

.header-title p {
    margin: 0;
    font-size: 14px;
    color: #f0f0f0;
}

/* Right side icons */
.header-icons {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Cart styles */
.cart-icon {
    position: relative;
}

.cart-image {
    width: 32px;
    height: 32px;
}

.cart-count {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff3b3b;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: bold;
}



.header-navigation nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to right,rgb(231, 139, 70), #fdf0e6);
    padding: 10px 20px;
    border-radius: 8px;
    margin: 10px 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Navigation links */
nav a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    font-weight: bold;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #ffcc00;
}

/* Search bar styling */
.search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-input {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    background-color: white; /* Ensure the background is visible */
    color: black; /* Ensure the text is visible */
}

.search-button {
    background-color: #ffcc00;
    color: #4e6cf0;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-button:hover {
    background-color: #e6b800;
    transform: scale(1.05);
}


</style>
</head>
<body>

<header>
    <?php include'styles/header.php'?>
  <div class="header-wrapper">
    <div class="logo-section">
      <img src="src/logo.png" alt="Logo" class="logo-circle">
    </div>
    
    <div class="header-title">
      <h1>LOOMify</h1>
      <h2>Handmade Goods</h2>
    </div>

    <div class="header-navigation">
    <!-- your nav code here -->
    <nav>
    <a href="index.php">Home</a>
    <a href="#">Shop</a>
    <a href="productCategory.php">Categories</a>
    <a href="#">About</a>
    <a href="#">Contact</a>

  <div class="search-bar">
    <form action="sample.php" method="GET">
      <input type="text" name="search" class="search-input" 
           placeholder="Search products by name, description, or category" 
           value="<?php echo htmlspecialchars($search_query); ?>">
      <button type="submit" class="search-button">Search</button>
    </form>
  </div>
            </nav>
  </div>
    <div class="header-icons">
      <a href="cart.php" class="cart-icon">
        <img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt="Cart" class="cart-image">
        <span class="cart-count">0</span>
      </a>
      <div class="dropdown">
        <button class="profile-btn" id="profileToggle">
          <img src="https://cdn-icons-png.flaticon.com/128/3177/3177440.png" alt="Profile" class="profile-icon">
        </button>
        <div class="dropdown-content" id="profileDropdown">
          <!-- links -->
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
                <!-- <a href= "admin/index.php>">Admin</a> -->
            <?php endif; ?>
        </div>
      </div>
    </div>
  </div>


</header>


<!-- hero content -->
<p style="text-align: center; font-size: 1rem;">Our Products</p>
<section class="relative w-full h-[500px] overflow-hidden">

  <div id="banner-carousel" class="flex transition-transform duration-700 ease-in-out h-full" style="transform: translateX(0%)">

<div class="w-full h-full bg-cover bg-center relative shrink-0" style="background-image: url('banner1.jpg')">
  <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white text-center p-6">
    <h2 class="text-3xl md:text-4xl font-bold mb-4">Starting ₹149</h2>
    <p class="text-lg md:text-xl mb-6">Daily Needs | Great Prices | Top Brands</p>
    <a href="productDetails.php" class="bg-[#4e6cf0] hover:bg-[#3d59d9] px-6 py-3 rounded-full text-white font-semibold transition duration-300 shadow-lg">
      Shop Now
    </a>
  </div>
</div> 

    <img src="banner1.jpg" alt="Banner 1" class="w-full h-full object-cover shrink-0" />
    <img src="banner2.jpg" alt="Banner 2" class="w-full h-full object-cover shrink-0" />
    <img src="banner3.jpg" alt="Banner 3" class="w-full h-full object-cover shrink-0" />
  </div>


  <button onclick="prevBanner()" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/70 hover:bg-white p-2 rounded-full shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
  </button>


  <button onclick="nextBanner()" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/70 hover:bg-white p-2 rounded-full shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button>
</section>

<script>
  let currentBanner = 0;
  const bannerCount = 4;
  const intervalTime = 10000; 

  function updateBannerPosition() {
    const carousel = document.getElementById("banner-carousel");
    carousel.style.transform = `translateX(-${currentBanner * 100}%)`;
  }

  function nextBanner() {
    currentBanner = (currentBanner + 1) % bannerCount;
    updateBannerPosition();
  }

  function prevBanner() {
    currentBanner = (currentBanner - 1 + bannerCount) % bannerCount;
    updateBannerPosition();
  }

  // Auto scroll every 4 seconds
  setInterval(() => {
    nextBanner();
  }, intervalTime);
</script>


<!-- main content -->
<main>

  <section class="products-section">
    <p style="text-align: center; font-size: 2.5rem;">Our Products</p>

    <?php if (!empty($search_query)): ?>
        <p style="text-align:center; margin-top:-10px;">Results for "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
    <?php endif; ?>
    <div class="products-grid">
      <?php if ($products->num_rows > 0): ?>
        <?php while($product = $products->fetch_assoc()): ?>
          <div class="product-card">
            <?php if (!empty($product['Image']) && file_exists($product['Image'])): ?>
              <img src="<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <?php else: ?>
              <div class="image-placeholder">No image</div>
            <?php endif; ?>

            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['category_name']); ?></p>
            <p class="stock-info">
              <?php 
                if ($product['stock'] <= 0) echo "<span style='color:red;'>Out of Stock</span>";
                elseif ($product['stock'] <= 5) echo "<span style='color:orange;'>Low Stock: {$product['stock']}</span>";
                else echo "Available: {$product['stock']}";
              ?>
            </p>
            <p class="price">₨ <?php echo number_format($product['price'], 2); ?></p>

            <form action="cart.php" method="POST">
              <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
              <input type="hidden" name="available_stock" value="<?php echo $product['stock']; ?>">
              <button type="submit" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                <?php echo $product['stock'] <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
              </button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="grid-column: 1 / -1; text-align:center;">No products found.</p>
      <?php endif; ?>
    </div>
  </section>
</main>


<footer style="text-align:center; padding:15px; background:#f0f0f0;">
  <?php include 'styles/footer.php'; ?>
</footer>

<?php $conn->close(); ?>
</body>
</html>

