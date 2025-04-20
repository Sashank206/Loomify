<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Craft Haven</title>
  <link rel="stylesheet" href="styles/home.css" />
</head>
<body>
  <header>
    <div class="logo">LOOMify</div>
    
    <nav>
      <a href="#">Home</a>
      <a href="#">Shop</a>
      <a href="#">Categories</a>
      <a href="#">About</a>
      <a href="#">Contact</a>
    </nav>

    </div>
    <div class="search-bar">
      <form action="index.php" method="GET">
          <input type="text" name="search" class="search-input" 
                 placeholder="Search products by name, description, or category" 
                 value="<?php echo htmlspecialchars($search_query); ?>">
          <button type="submit" class="search-button">🔍</button>
      </form>
  </div>
  <div class="user-icon">
      <a href="login.php">👤</a>
      <a href="cart.php">🛒</a>
  
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
  </header>
  
  <div class="banner">
    <!-- Auto Scrolling Media Container -->
    <!-- <div class="scroll-container">
      <video class="banner-media" src="50% (1).mp4" alt="Banner Image" autoplay muted loop></video>
      <video class="banner-media" autoplay muted loop>
        <source src="50% (1).mp4" type="video/mp4" />
      
      </video> -->
      <div class="banner-media">
        <img src="uploads\6734728b2dcba.jpeg" alt="Banner Image" />
        <img src="uploads\67347238d1224.jpeg.jpeg" alt="Banner Image" />

    </div>

    <!-- Hero Content -->
    <div class="hero-content">
      <h1>Welcome to LOOMify</h1>
      <p>Your one-stop shop for unique handcrafted items</p>
      <button>Shop Now</button>
    </div>
  </div>

  <section class="products-section">
    <h1>Our Products</h1>
    
    <div class="main-content">
      <aside class="filters">
        <h3>Filters</h3>
        <div class="filter-group">
          <strong>Categories</strong><br />
          <label><input type="checkbox" /> Ceramics</label><br />
          <label><input type="checkbox" /> Textiles</label><br />
          <label><input type="checkbox" /> Wooden Crafts</label><br />
          <label><input type="checkbox" /> Jewelry</label><br />
          <label><input type="checkbox" /> Paper Goods</label><br />
          <label><input type="checkbox" /> Home Decor</label>
        </div>
        <div class="price-range">
          <strong>Price Range</strong><br />
          <input type="range" min="0" max="1000" />
          <div class="range-values">0 - 1000</div>
        </div>
      </aside>

      <div class="products-grid">
        <div class="product-card">
          <span class="new-tag">New</span>
          <div class="image-placeholder"></div>
          <h3>home decor</h3>
         
          <button>view more</button>
        </div>

        <div class="product-card">
          <div class="image-placeholder"></div>
          <h3>Hand-woven Wool Throw</h3>
          <button>view more</button>
        </div>

        <div class="product-card">
          <div class="image-placeholder"></div>
      
          <h3>Wooden Serving Board</h3>
          <button>view more</button>
        </div>
        <div class="product-card">
          <div class="image-placeholder"></div>
          <h3>=cups</h3>
          <button>view more</button>
      </div>
        <div class="product-card">
          <div class="image-placeholder"></div>
          <h3>Handmade Pottery</h3>
          <button>view more</button>
        </div>

        <div class="product-card">
          <div class="image-placeholder"></div>
          <h3>Handcrafted Jewelry</h3>
          <button><a href="jwerrly.html" class="btn">View More</a></button>
        </div>

        <div class="product-card">
          <div class="image-placeholder"></div>
          <h3>Artisan Candles</h3>
          <button>view more</button>
        </div>

        <div class="product-card">
          <div class="image-placeholder">
            <img src="p1.jpg" alt="Painting">
          </div>
          
          <h3>Painting</h3>
          <button><a href="painting.html" class="btn">View More</a></button>
        </div>
        
  </section>

  
</body>
</html>
