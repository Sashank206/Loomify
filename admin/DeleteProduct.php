<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: AdminLogin.php"); 
    exit();
}


if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    
    $delete_cart_query = "DELETE FROM cart WHERE product_id = $product_id";
    $conn->query($delete_cart_query); 

    
    $delete_product_query = "DELETE FROM products WHERE product_id = $product_id";

    if ($conn->query($delete_product_query)) {
        header("Location: manageProduct.php?success=1"); 
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    
    header("Location: manageProduct.php");
    exit();
}
?>