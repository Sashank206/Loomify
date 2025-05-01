<?php
session_start();
require '../db.php'; // Make sure this connects to your DB

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Get seller name
$seller_name = $conn->query("SELECT username FROM users WHERE user_id = $seller_id")->fetch_assoc()['username'];

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Stats specific to seller
$product_query = $conn->query("SELECT COUNT(*) AS total FROM products WHERE seller_id = $seller_id");
$total_products = $product_query ? $product_query->fetch_assoc()['total'] : 0;
$order_query = $conn->query("
    SELECT COUNT(*) AS total 
    FROM orders 
    WHERE order_id IN (
        SELECT DISTINCT order_id 
        FROM order_item 
        WHERE product_id IN (
            SELECT product_id FROM products WHERE seller_id = $seller_id
        )
    )
");
$total_orders = $order_query ? $order_query->fetch_assoc()['total'] : 0;
$sales_query = $conn->query("
    SELECT SUM(oi.quantity * oi.price) AS total
    FROM order_item oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE p.seller_id = $seller_id
");
$total_sales = $sales_query ? $sales_query->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="./seller.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Seller Dashboard</h1>
            <div class="admin-info">
                <span>Welcome, <?php echo htmlspecialchars($seller_name); ?></span>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="add.php">Add Product</a></li>
            <li><a href="manage.php">Manage Products</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Dashboard Overview</h2>
        <div class="dashboard-stats">
            <div class="stat-box">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Sales</h3>
                <p>₹ <?php echo number_format($total_sales, 2); ?></p>
            </div>
        </div>

        <h2>Recent Orders</h2>
        <table class="table">
            <tr>
                <th>Order ID</th>
                <th>Buyer</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
            <?php
            $recent_orders = $conn->query("
            SELECT DISTINCT o.order_id, u.username, o.order_date, o.status, 
                            pay.amount, pay.payment_method
            FROM orders o
            JOIN order_item oi ON o.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            JOIN users u ON o.user_id = u.user_id
            JOIN payments pay ON o.order_id = pay.order_id
            WHERE p.seller_id = $seller_id
              AND pay.status = 'paid'
            ORDER BY o.order_date DESC
            LIMIT 5
        ");        
            if ($recent_orders) {
                while ($order = $recent_orders->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
            <?php 
                endwhile; 
            } else {
                echo '<tr><td colspan="4">No recent orders found or query failed.</td></tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>
