<?php
// Include necessary files and start the session
if (!isset($_SESSION)) {
    session_start();
}

// Handle "Home" button click
if (isset($_POST["action"]) && $_POST["action"] == "home") {
    header("Location: CustomerAfter.php?fromHome");
    exit();
}

if (isset($_POST["action"]) && $_POST["action"] == "view") {   
    // include  'viewAnOrder.php';
    header("Location: viewAnOrder.php?fromView");
    exit();
}
// Handle search form submission
if (isset($_POST['product_search'])) {
    $searchTerm = $_POST['product_search'];

    include 'connectDB.php';
    $pdo = db_connect();

    // Check if the search term is numeric to determine if it's a price search
    $isPriceSearch = is_numeric($searchTerm);

    if ($isPriceSearch) {
        // Search by price using a range (e.g., within $5 of the specified price)

        // Define the price range, adjust as needed
        $minPrice = $searchTerm - 2;
        $maxPrice = $searchTerm + 2;
        $searchSql = "SELECT * FROM products WHERE price BETWEEN :minPrice AND :maxPrice";
        $searchStmt = $pdo->prepare($searchSql);
        $searchStmt->bindValue(':minPrice', $minPrice);
        $searchStmt->bindValue(':maxPrice', $maxPrice);
    } else {
        // Search by name using LIKE
        $searchSql = "SELECT * FROM products WHERE name LIKE :searchTerm";
        $searchStmt = $pdo->prepare($searchSql);
        $searchStmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
    }

    $searchStmt->execute();
    $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Store Management</title>
    <style>
        .InfoUser {
            background-color: #ff0000; /* Red background color */
            padding: 10px; /* Adjust the padding as needed */
            border-radius: 10px; /* Rounded corners */
            color: #ffffff; /* White text color, you can adjust it based on your design */
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
    </style>
</head>
<body class="afterLogin">

<!-- Navigation Bar -->
<nav>
    <!-- Left Links -->
    <div>
        <form method="post">
            <button type="submit" name="action" value="home">Home</button>
            <button type="submit" name="action" value="view">View an Order</button>
        </form>
    </div>

    <!-- Center: Search Box -->
    <div class="search-box">
        <form method="post">
            <input type="text" name="product_search" placeholder="Enter product name or price...">
            <input type="submit" value="Search">
        </form>
    </div>

    <!-- Right Link -->
    <div class="InfoUser">
        <?php
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
        echo "Welcome $username";
        ?>
    </div>
    <a href="Demo.php">Logout</a>
</nav>

<div class="content">
    <!-- Your admin portal content goes here -->
    <?php
    if (!empty($searchResults)) {
        // Display search results
        echo '<div class="product-container">';
        foreach ($searchResults as $product) {
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
            echo '<input type="hidden" name="customer_name" value="' . $_SESSION['user_name'] . '">';
            echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
            echo '<button type="submit" name="action" value="receive">Reserve</button>';
            echo '</form>';///in the search form ///

            
            echo '</div>';
        }
        echo '</div>';
    } elseif (isset($_POST['product_search'])) {
        // echo '<p>No results found.</p>';
        echo '<div style="background-color: #ffcccc; padding: 10px; border: 1px solid #f00;">No results found.</div>';

    } else {
        // Display default content
        include 'HomeOfUser.php';
    }
    
    ?>
</div>

</body>
</html>
