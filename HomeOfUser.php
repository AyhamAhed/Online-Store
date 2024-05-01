<?php
// Include the database connection file
include 'connectDB.php';


if (!isset($_SESSION)) {
    session_start();
}


// Fetch products from the database
$pdo = db_connect();
$sql = "SELECT * FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display products in a centered box
echo '<div class="product-container">';
foreach ($products as $product) {
    echo '<div class="product-box">';
    
    // Display the image using the stored file path
    if (!empty($product['image_path'])) {
        $imagePath = $product['image_path'];
        // $imageUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
        echo '<img src="' . $imagePath . '" alt="Product Image" class="product-image">';       
    } 
   
    // echo '<p>ID: ' . $product['product_id'] . '</p>';
    echo '<p>Name: ' . $product['name'] . '</p>';
    echo '<p>Description: ' . $product['description'] . '</p>';
    echo '<p>Category: ' . $product['category'] . '</p>';
    echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
    echo '<p>Size: ' . $product['size'] . '</p>';
    echo '<p>Remarks: ' . $product['remarks'] . '</p>';
    // echo '<p>Quantity: ' . $product['quantity'] . '</p>';

    // Add a form with an input field and a button for receiving
        echo '<form action="processReceive.php" method="GET">';
        echo '<input type="hidden" name="customer_id" value="' . $_SESSION['customer_name'] . '">';
        echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
        echo '<button type="submit" name="action" value="receive">Reserve</button>';
        echo '</form>';

    
    // Add other product details as needed
    echo '</div>';
}
echo '</div>';
?>
