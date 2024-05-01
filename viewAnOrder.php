<style>
    
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: url('./image/Image.jpg') no-repeat center center fixed;
    background-size: cover;
}
    body {
        text-align: center;
    }

    .product-container {
        max-width: 1000px;
        margin: 20px auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .product-box {
        background-color: aquamarine;
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px;
        border-radius: 8px;
        width: 180px;
        box-sizing: border-box;
        text-align: center;
    }

    .product-box img {
        background-color: yellowgreen;
        max-width: 100%;
        height: auto;
        margin-top: 10px;
        border-radius: 4px;
    }

    .product-image {
        width: 100%;
        height: auto;
        max-width: 150px;
        max-height: 100px;
        display: block;
        margin: 0 auto;
    }

    a.center-anchor {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .delete-button {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<?php
// Include the database connection file
include 'connectDB.php';

if (!isset($_SESSION)) {
    session_start();
}

// Fetch received products from the database
$pdo = db_connect();
$sql = "SELECT product_id, COUNT(*) AS product_count FROM product_receives WHERE customer_name = ? GROUP BY product_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_name']]);
$receivedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Anchor link to go back to the home page
echo '<a href="customerAfter.php" class="center-anchor">Go back to Home</a>';

// Display received products in a centered box
echo '<div class="product-container">';
foreach ($receivedProducts as $receivedProduct) {
    $productId = $receivedProduct['product_id'];
    $productCount = $receivedProduct['product_count'];

    // Fetch product details from the products table based on product_id
    $productSql = "SELECT * FROM products WHERE product_id = ?";
    $productStmt = $pdo->prepare($productSql);
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo '<div class="product-box">';
        
        // Display the image using the stored file path
        if (!empty($product['image_path'])) {
            $imagePath = $product['image_path'];
            // $imageUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
            echo '<img src="' . $imagePath . '" alt="Product Image" class="product-image">';
        }

        echo '<p>Name: ' . $product['name'] . '</p>';
        echo '<p>Description: ' . $product['description'] . '</p>';
        echo '<p>Category: ' . $product['category'] . '</p>';
        echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
        echo '<p>Size: ' . $product['size'] . '</p>';
        echo '<p>Remarks: ' . $product['remarks'] . '</p>';
        echo '<p>Status: ' . $product['status'] . '</p>';

        // Add a section for the count of the product
       
            echo '<p>Count: ' . $productCount . '</p>';
        

        // Add a form with a styled button to delete the product from received products
        echo '<form action="deleteProduct.php" method="POST">';
        echo '<input type="hidden" name="product_id" value="' . $productId . '">';
        echo '<button type="submit" name="action" value="delete" class="delete-button">Delete</button>';
        echo '</form>';

        // Close the product box
        echo '</div>';
    }
}
echo '</div>';
?>
