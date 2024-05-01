<?php
// Include necessary files and start the session
if (!isset($_SESSION)) {
    session_start();
}

// ...


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the action is set and the action is for receiving a product
    if (isset($_GET['action']) && $_GET['action'] === 'receive') {
        // Check if the product_id and customer_id are set
        if (isset($_GET['product_id'], $_GET['customer_id'])) {
            // Include the database connection file
            include 'connectDB.php';
            $pdo = db_connect();

            $product_id = $_GET['product_id'];

            // Fetch the current quantity from the products table
            $selectSql = "SELECT quantity FROM products WHERE product_id = ?";
            $selectStmt = $pdo->prepare($selectSql);
            $selectStmt->execute([$product_id]);
            $current_quantity = $selectStmt->fetchColumn();

            if ($current_quantity >= 1) {
                // Insert a record into the product_receives table
                $customerId = $_SESSION['user_name'];
                $productId = $_GET['product_id'];
                $insertSql = "INSERT INTO product_receives (customer_name, product_id) VALUES (?, ?)";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->bindValue(1, $customerId);
                $insertStmt->bindValue(2, $productId);
                // Execute the query
                $insertStmt->execute();


                // Update the quantity by incrementing it by one in the products table
                $updateSql = "UPDATE products SET quantity = quantity - 1 WHERE product_id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$product_id]);

                // include  'viewAnOrder.php';
                 header("Location: viewAnOrder.php?Done-Reserve");
                // header("Location: CustomerAfter.php?Done-Reserve"); 
                // Close the PDO connection
                $pdo = null;
                
                exit();
            }
            else{
                header("Location: CustomerAfter.php?Does-Not-Reserve");
                // Close the PDO connection
                $pdo = null;
                exit();
            }

        }
    }
}

// If no valid action is found or parameters are missing, redirect to the home page or any other page
            header("Location: CustomerAfter.php?noDoneReserve");
            // Close the PDO connection
            $pdo = null;
            exit();

?>
