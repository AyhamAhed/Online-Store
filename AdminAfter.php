<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Admin Portal</title>
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
            width: 100%; /* Set the width as needed, e.g., 100% for full width */
            height: auto; /* Maintain aspect ratio */
            max-width: 150px; /* Set the maximum width if needed */
            max-height: 80px; /* Set the maximum height if needed */
            display: block; /* Remove any extra spacing */
            margin: 0 auto; /* Center the image within the container */
        }

    </style>
</head>
<body class="admin">

<form method="post">
    <nav>
        <!-- Links for Admin Portal -->
        <!-- <a href="adminAfter.php" class="home">Home</a> -->
        <button type="submit" name="action" value="home">Home</button>
        <button type="submit" name="action" value="add_product">Add Product</button>
        <button type="submit" name="action" value="view_orders">View Orders</button>
        <!-- <button type="submit" name="action" value="manage_inventory">Manage Inventory</button> -->
    
        
        <div class = "InfoUser">
        <?php if(isset($_SESSION)){
            $username =  $_SESSION['customer_name'];
        }
    else{
    session_start();
    $username =  $_SESSION['customer_name'];
    }
       echo"Welcome $username";
       ?>
        </div>
        <a href="Demo.php" class="logout">Logout</a>
    </nav>
</form>
    
<div class="content">
    <?php
    if (!isset($_POST["action"])) {
        include 'home.php';
    }
    // Handle actions
    if (isset($_POST["action"])) {
        $selectedAction = $_POST["action"];

        switch ($selectedAction) {
            case 'home':
                include 'home.php';
                break;
            case 'add_product':
                // Handle Add Product action
                // echo '<p>Add Product action selected.</p>';
                include 'AddProduct.php';
                break;

            case 'view_orders':
                // Handle View Orders action
                include 'viewOrders.php';
                // echo '<p>View Orders action selected.</p>';
                break;

        }
    }
    ?>
</div>




</body>
</html>
