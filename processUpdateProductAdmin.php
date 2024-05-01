<?php
// Include the database connection file
include 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['productName'], $_POST['productDescription'], $_POST['productCategory'], $_POST['productPrice'], $_POST['productSize'], $_POST['productRemarks'], $_POST['productQuantity'])) {

        // Get the product ID from the form
        $product_id = $_POST['product_id'];

        // Fetch the product details from the database using the product_id
        $pdo = db_connect();
        $sql = "SELECT * FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the product exists
        if ($product) {
            // Update the product information with the new values from the form
            $newName = $_POST['productName'];
            $newDescription = $_POST['productDescription'];
            $newCategory = $_POST['productCategory'];
            $newPrice = $_POST['productPrice'];
            $newSize = $_POST['productSize'];
            $newRemarks = $_POST['productRemarks'];
            $newQuantity = $_POST['productQuantity'];

            // Perform the update in the database
            $updateSql = "UPDATE products SET 
                name = :newName,
                description = :newDescription,
                category = :newCategory,
                price = :newPrice,
                size = :newSize,
                remarks = :newRemarks,
                quantity = :newQuantity
                WHERE product_id = :product_id";

            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $updateStmt->bindParam(':newDescription', $newDescription, PDO::PARAM_STR);
            $updateStmt->bindParam(':newCategory', $newCategory, PDO::PARAM_STR);
            $updateStmt->bindParam(':newPrice', $newPrice, PDO::PARAM_STR);
            $updateStmt->bindParam(':newSize', $newSize, PDO::PARAM_STR);
            $updateStmt->bindParam(':newRemarks', $newRemarks, PDO::PARAM_STR);
            $updateStmt->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
            $updateStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                // Update successful
                header('Location: adminAfter.php'); // Redirect to the desired page after successful update
                exit();
            } else {
                // Update failed
                echo 'Failed to update the product. Please try again.';
            }
        } else {
            // Product not found
            echo 'Product not found.';
        }
    } else {
        // Not all required fields are set
        echo 'All fields are required.';
    }
} else {
    // Invalid request method
    echo 'Invalid request method.';
}
?>
