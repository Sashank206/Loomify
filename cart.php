<?php 
session_start();
include 'db.php';


if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = [];
$total = 0;
$error_message = '';
$success_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $quantity = 1;


  $conn->begin_transaction();

  try {
  
    $stock_query = "SELECT stock FROM products WHERE product_id = ? FOR UPDATE";
    $stock_stmt = $conn->prepare($stock_query);
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $product_stock = $stock_result->fetch_assoc();

    if ($product_stock && $product_stock['stock'] > 0) {
      
      $check_query = "SELECT * FROM cart WHERE product_id = ? AND user_id = ?";
      $stmt = $conn->prepare($check_query);
      $stmt->bind_param("ii", $product_id, $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $new_quantity = $item['quantity'] + $quantity;
        
        if ($new_quantity <= $product_stock['stock']) {
          $update_query = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
          $update_stmt = $conn->prepare($update_query);
          $update_stmt->bind_param("ii", $new_quantity, $item['cart_id']);
          $update_stmt->execute();
          $update_stmt->close();
          
  
          $new_stock = $product_stock['stock'] - $quantity;
          $update_stock_query = "UPDATE products SET stock = ? WHERE product_id = ?";
          $update_stock_stmt = $conn->prepare($update_stock_query);
          $update_stock_stmt->bind_param("ii", $new_stock, $product_id);
          $update_stock_stmt->execute();
          $update_stock_stmt->close();
          
          $success_message = "Cart updated successfully!";
        } else {
          throw new Exception("Not enough stock available!");
        }
      } else {
    
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
        
       
        $new_stock = $product_stock['stock'] - $quantity;
        $update_stock_query = "UPDATE products SET stock = ? WHERE product_id = ?";
        $update_stock_stmt = $conn->prepare($update_stock_query);
        $update_stock_stmt->bind_param("ii", $new_stock, $product_id);
        $update_stock_stmt->execute();
        $update_stock_stmt->close();
        
        $success_message = "Item added to cart!";
      }
      
   
      $_SESSION['cart_count'] = ($_SESSION['cart_count'] ?? 0) + $quantity;
      
      $conn->commit();
    } else {
      throw new Exception("Item is out of stock!");
    }
  } catch (Exception $e) {
    $conn->rollback();
    $error_message = $e->getMessage();
  }

  
  if ($error_message) {
    $_SESSION['error_message'] = $error_message;
  } else {
    $_SESSION['success_message'] = $success_message;
  }
  header('Location: index.php');
  exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
  $cart_id = $_POST['cart_id'];
  $new_quantity = (int)$_POST['quantity'];
  
  $conn->begin_transaction();
  
  try {
   
    $cart_query = "SELECT c.product_id, c.quantity, p.stock FROM cart c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.cart_id = ? AND c.user_id = ? FOR UPDATE";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("ii", $cart_id, $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    $cart_item = $cart_result->fetch_assoc();
    
    if ($cart_item) {
      $quantity_difference = $new_quantity - $cart_item['quantity'];
      $available_stock = $cart_item['stock'] + $cart_item['quantity']; // Current stock + what's in cart
      
      if ($new_quantity <= $available_stock) {
        if ($new_quantity > 0) {
          
          $update_query = "UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
          $stmt = $conn->prepare($update_query);
          $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
          $stmt->execute();
          
          
          $new_stock = $available_stock - $new_quantity;
          $update_stock_query = "UPDATE products SET stock = ? WHERE product_id = ?";
          $stock_stmt = $conn->prepare($update_stock_query);
          $stock_stmt->bind_param("ii", $new_stock, $cart_item['product_id']);
          $stock_stmt->execute();
          
          $_SESSION['success_message'] = "Cart updated successfully!";
        }
      } else {
        throw new Exception("Not enough stock available!");
      }
    }
    
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
  }
  
  header('Location: cart.php');
  exit();
}

if (isset($_GET['remove'])) {
  $cart_id = $_GET['remove'];
  
  
  $conn->begin_transaction();
  
  try {
   
    $cart_query = "SELECT product_id, quantity FROM cart WHERE cart_id = ? AND user_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("ii", $cart_id, $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    $cart_item = $cart_result->fetch_assoc();
    
    if ($cart_item) {
      
      $update_stock = "UPDATE products SET stock = stock + ? WHERE product_id = ?";
      $stock_stmt = $conn->prepare($update_stock);
      $stock_stmt->bind_param("ii", $cart_item['quantity'], $cart_item['product_id']);
      $stock_stmt->execute();
      
      
      $delete_query = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
      $delete_stmt = $conn->prepare($delete_query);
      $delete_stmt->bind_param("ii", $cart_id, $user_id);
      $delete_stmt->execute();
      
      // Update cart count in session
      $_SESSION['cart_count'] = max(0, ($_SESSION['cart_count'] ?? 0) - $cart_item['quantity']);
      
      $_SESSION['success_message'] = "Item removed from cart!";
    }
    
    $conn->commit();
  } catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
  }
  
  header('Location: cart.php');
  exit();
}

// Fetch cart items for the current user with stock information
$query = "SELECT c.*, p.name, p.price, p.Image, p.stock 
      FROM cart c 
      JOIN products p ON c.product_id = p.product_id 
      WHERE c.user_id = ?";
if ($stmt = $conn->prepare($query)) {
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $cart_items = $result->fetch_all(MYSQLI_ASSOC);
  
  // Calculate total
  foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Cart</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .cart-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1,h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .header-navigation {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-bottom: 20px;
    }

    .header-navigation a {
      background-color:grey; 
      color: white;
      font-weight: 400;
      padding: 8px 16px;
      border-radius: 9999px; /* pill shape */
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease;
    }
    .header-navigation a:hover {
      background-color:black;
    }
    a {
  text-decoration: none;}

    .cart-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
    }
    header{
      background:#fef2e6;
      color: black;
      padding: 0px;
      text-align: center;
    }
    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    } 

    .item-details {
      flex: 1;
      margin-left: 20px;
    }

    .item-title {
      font-size: 18px;
      font-weight: 600;

    }

    .quantity-controls {
      color: #555;
      margin-top: 5px;
      gap:2px;
    }

    .remove-btn {
      background: #ff4d4d;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .remove-btn:hover {
      background: #e60000;
    }

    .cart-total {
      text-align: right;
      font-size: 20px;
      margin-top: 20px;
      font-weight: bold;
    }

    .checkout-btn {
      display: inline;
      width: 30%;
      background: #28a745;
      color: white;
      padding: 12px;
      font-size: 18px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 20px;
      text-align: center;'
      float : right;
    }

    .checkout-btn:hover {
      background: #218838;
    }
  </style>
</head>
<body>
<header>
  <div>
<h1>🛒 Your Cart</h1>
  <h2>Manage your items below</h2></div>

  <div class="header-navigation">

    <a href="index.php">Home</a>
    <a href="productDetails.php">Products</a>
    <a href="productcategory.php">Category</a>
    <a href="logout.php">Logout</a>
</header>
  <div class="cart-container">
  <?php if (isset($_SESSION['error_message'])): ?>
            <div class="message error">
                <?php 
                echo htmlspecialchars($_SESSION['error_message']);
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="message success">
                <?php 
                echo htmlspecialchars($_SESSION['success_message']);
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
          <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="index.php" class="checkout-btn">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
    <div class="cart-item">
    <img src="<?php echo htmlspecialchars($item['Image']); ?>" 
     alt="<?php echo htmlspecialchars($item['name']); ?>">
                    
     <div class="item-details">
      <h3><?php echo htmlspecialchars($item['name']); ?></h3>
      <p>Price: ₨<?php echo number_format($item['price'], 2); ?></p>
      <p class="stock-info">Available Stock: <?php echo $item['stock'] + $item['quantity']; ?></p>
    </div>
    <form method="POST" class="quantity-controls">
    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
     min="1" max="<?php echo $item['stock'] + $item['quantity']; ?>">
     <button type="submit" name="update_quantity" class="update-btn">Update</button>
    </form>
                    
    <p>Subtotal: ₨ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                    
    <a href="?remove=<?php echo $item['cart_id']; ?>" class="remove-btn" 
     onclick="return confirm('Are you sure you want to remove this item?')">
      Remove
    </a>
    </div>
    <?php endforeach; ?>
            
    <div class="cart-total">
      <p>Total: ₨<?php echo number_format($total, 2); ?></p>
      <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>
     <?php endif; ?>
    </div>
    <script>
        document.querySelectorAll('.quantity-controls form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const quantity = this.querySelector('input[name="quantity"]').value;
                if (quantity < 1) {
                    e.preventDefault();
                    alert('Quantity must be at least 1');
                }
            });
        });
    </script>
    <footer>
      <?php include 'styles/footer.php'; ?>
      </footer>
</body>
</html>
