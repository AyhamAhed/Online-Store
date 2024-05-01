<?php
//this class home of admin!
// Include the database connection file
include 'connectDB.php';

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
        

        echo '<form action="AdminAfter.php" method="POST">';
        // Wrap the image in an anchor tag with a link to a new page
        echo '<a href="product_details.php?product_id=' . $product['product_id'] . '" title="Click on the image if you want to update">';
        echo '<img src="' . $imagePath . '" alt="Product Image" class="product-image">';
        echo '</a>';
        echo '</form>';
    } 
    
    echo '<p>ID: ' . $product['product_id'] . '</p>';
    echo '<p>Name: ' . $product['name'] . '</p>';
    echo '<p>Description: ' . $product['description'] . '</p>';
    echo '<p>Category: ' . $product['category'] . '</p>';
    echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
    echo '<p>Size: ' . $product['size'] . ' Liter</p>';
    echo '<p>Remarks: ' . $product['remarks'] . '</p>';
    echo '<p>Quantity: ' . $product['quantity'] . '</p>';

    //  form with an input field and an update button
    echo '<form action="processUpdate.php" method="POST">';
    echo '<label for="newQuantity">New Quantity:</label>';
    echo '<input type="text" id="newQuantity" name="newQuantity" placeholder="Enter new quantity" size="5" required>';
    echo '<button type="submit" name="action" value="' . $product['product_id'] . '">Update</button>';
    echo '</form>';
    
    echo '</div>';
}
echo '</div>';
?>
