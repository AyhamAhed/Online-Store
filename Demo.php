
<?php
// Include necessary files and start the session
session_start();

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
            background-color: #ff0000;
            padding: 10px;
            border-radius: 10px;
            color: #ffffff;
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
    <div>
        <!-- <form method="post">
            <button type="submit" name="action" value="home">Home</button>
        </form> -->
    </div>

    <div class="search-box">
        <form method="post">
            <input type="text" name="product_search" placeholder="Enter product name or price..." >
            <input type="submit" value="Search">
        </form>
    </div>

    <!-- <div class="InfoUser">
        <?php
        // $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
        // echo "Welcome $username";
        // ?>
    </div> -->
    <a href="index.php">Login</a>
</nav>

<div class="content">
    <?php
    if (!empty($searchResults)) {
        echo '<div class="product-container">';
        foreach ($searchResults as $product) {
            echo '<div class="product-box">';
            
            if (!empty($product['image_path'])) {
                $imagePath = $product['image_path'];
                echo '<img src="' . $imagePath . '" alt="Product Image" class="product-image">';       
            } 
            
            echo '<p>Name: ' . $product['name'] . '</p>';
            echo '<p>Description: ' . $product['description'] . '</p>';
            echo '<p>Category: ' . $product['category'] . '</p>';
            echo '<p>Price: $' . number_format($product['price'], 2) . '</p>';
            echo '<p>Size: ' . $product['size'] . '</p>';
            echo '<p>Remarks: ' . $product['remarks'] . '</p>';

            // echo '<form action="processReceive.php" method="GET">';
            // echo '<input type="hidden" name="customer_name" value="' . $_SESSION['user_name'] . '">';
            // echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
            echo '<button type="submit" name="action" value="receive">Reserve</button>';
            // echo '</form>';
            
            echo '</div>';
        }
        echo '</div>';
    } elseif (isset($_POST['product_search'])) {
        echo '<div style="background-color: #ffcccc; padding: 10px; border: 1px solid #f00;">No results found.</div>';
    }
     else {
        include 'HomeDemo.php';
    }
    ?>
</div>

</body>
</html>




