<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../SellerLogin.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Ensure the product belongs to the logged-in seller
    $check_query = $conn->prepare("SELECT product_id FROM products WHERE product_id = ? AND seller_id = ?");
    $check_query->bind_param("ii", $product_id, $seller_id);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        // First, delete from cart where product is referenced
        $delete_cart_query = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
        $delete_cart_query->bind_param("i", $product_id);
        $delete_cart_query->execute();

        // Then delete the product
        $delete_product_query = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $delete_product_query->bind_param("i", $product_id);

        if ($delete_product_query->execute()) {
            header("Location: manage.php?deleted=1");
            exit();
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    } else {
        header("Location: manage.php?error=unauthorized");
        exit();
    }
} else {
    header("Location: manage.php");
    exit();
}
?>
