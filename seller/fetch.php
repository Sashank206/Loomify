<?php
session_start();
require '../db.php';

$seller_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM products WHERE seller_id = $seller_id");

while ($row = $result->fetch_assoc()) {
    echo "<div class='product-card'>";
    echo "<img src='" . $row['image'] . "' width='100'><br>";
    echo "<strong>" . $row['name'] . "</strong><br>";
    echo $row['description'] . "<br>";
    echo "$" . $row['price'] . "<br>";
    echo "<div class='actions'>
        <a href='edit_product.php?id={$row['product_id']}'>Edit</a>
        <a href='delete_product.php?id={$row['product_id']}'>Delete</a>
    </div>";
    echo "</div>";
}
