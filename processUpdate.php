

<?php
//this class to update the quantity of products;
// Include the database connection file
include 'connectDB.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    try {
        // Validate and sanitize input data (implement as needed)
        $pdo = db_connect();

        // Get the product ID and new quantity from the form
        $productId = isset($_POST['productId']) ? $_POST['productId'] : "";
        $newQuantity = isset($_POST['newQuantity']) ? $_POST['newQuantity'] : "";

        // Update the quantity in the database
        $updateSql = "UPDATE products SET quantity = ? WHERE product_id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(1, $newQuantity);
        $updateStmt->bindParam(2, $_POST["action"]);
        $updateStmt->execute();

        // Display a confirmation message
        echo "Quantity updated successfully.";

        // Close the database connection
        $pdo = null;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
       
    }
}
header("Location: AdminAfter.php");
?>