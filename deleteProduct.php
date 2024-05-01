<?php
// Include the database connection file
include 'connectDB.php';

if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Check if the product_id is set
    if (isset($_POST['product_id'])) {
        // Get the product_id from the form
        $productId = $_POST['product_id'];


         

        // Delete the product from the product_receives table
        $pdo = db_connect();
        $deleteSql = "DELETE FROM product_receives WHERE customer_name = ? AND product_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        // Use the customer_id from the session and the product_id from the form
        $deleteStmt->execute([$_SESSION['user_name'], $productId]);

        // Update the quantity by decrementing it by one in the products table
        $updateSql = "UPDATE products SET quantity = quantity + 1 WHERE product_id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$productId]);

        // Redirect back to the original page after deletion
        header("Location: viewAnOrder.php?Done-Deleted");
        // header("Location: customerAfter.php?doneDeleted");
        exit();
    }
}
?>