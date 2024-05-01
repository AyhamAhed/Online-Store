<?php
//this class for update all info if i want from admin
// Include the database connection file
include 'connectDB.php';

// Check if the product_id parameter is set in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the product details from the database using the product_id
    $pdo = db_connect();
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Display the product details
    echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" href="style.css">';
    echo '<title>Product Details</title>';
    echo '<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        
        h1 {
            color: #333;
        }
        .product-details {
            background-color: azure;
            max-width: 600px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px; /* Add some margin to separate the button and link */
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>';
    echo '</head>';
    echo '<body>';

    if ($product) {
        echo '<div class="product-details">';
        
        // Display the product image if available
        if (!empty($product['image_path'])) {
            $imagePath = $product['image_path'];
            // $imageUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
            echo '<img src="' . $imagePath . '" alt="Product Image">';
        } else {
            echo '<p>No image available</p>';
        }

        echo '<h1>Product Details</h1>';

        // Display individual text fields for updating each piece of information
        echo '<form action="processUpdateProductAdmin.php" method="POST">';
        echo '<input type="hidden" name="product_id" value="' . $product_id . '">';

        echo '<label for="productName">Name:</label>';
        echo '<input type="text" id="productName" name="productName" value="' . $product['name'] . '">';

        echo '<label for="productDescription">Description:</label>';
        echo '<input type="text" id="productDescription" name="productDescription" value="' . $product['description'] . '">';

        // Display the category combo box
        echo '<label for="productCategory">Category:</label>';
        echo '<select id="productCategory" name="productCategory" required>';
        
        $categories = array(
            'New Arrival' => 'New Arrival',
            'On Sale' => 'On Sale',
            'Featured' => 'Featured',
            'High Demand' => 'High Demand',
            'Normal' => 'Normal'
        );

        foreach ($categories as $value => $label) {
            $selected = ($product['category'] == $value) ? 'selected' : '';
            echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }

        echo '</select>';

        echo '<label for="productPrice">Price:</label>';
        echo '<input type="text" id="productPrice" name="productPrice" value="' . $product['price'] . '">';

        echo '<label for="productSize">Size:</label>';
        echo '<input type="text" id="productSize" name="productSize" value="' . $product['size'] . '">';

        echo '<label for="productRemarks">Remarks:</label>';
        echo '<input type="text" id="productRemarks" name="productRemarks" value="' . $product['remarks'] . '">';

        echo '<label for="productQuantity">Quantity:</label>';
        echo '<input type="text" id="productQuantity" name="productQuantity" value="' . $product['quantity'] . '">';

        // Add a button to submit changes to the MySQL table
        echo '<button type="submit">Update Product</button>';
        
        // Add a link to go back to the home
        echo '<a href="adminAfter.php">Go back to Home</a>';
        
        echo '</form>';
        echo '</div>';
    } else {
        echo '<p>Product not found.</p>';
    }

    echo '</body>';
    echo '</html>';
} else {
    echo '<p>Invalid request. Product ID not specified.</p>';
}
?>
