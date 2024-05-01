<?php
                               //this class for add new products!!!
// Start a session (if not already started)
if (!isset($_SESSION)) {
    session_start();
}

// Include the database connection file
include("connectDB.php");

// Initialize variables with default values or empty strings
$productName = $productDescription = $productCategory = $productPrice = $productSize = $productRemarks = $productQuantity = $productImage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "addProduct") {
    try {
        // Validate and sanitize input data (implement as needed)
        $pdo = db_connect();

        // Process product info
        $productName = isset($_POST['productName']) ? $_POST['productName'] : "";
        $productDescription = isset($_POST['productDescription']) ? $_POST['productDescription'] : "";
        $productCategory = $_POST['productCategory'];
        $productPrice = isset($_POST['productPrice']) ? $_POST['productPrice'] : "";
        $productSize = isset($_POST['productSize']) ? $_POST['productSize'] : "";
        $productRemarks = isset($_POST['productRemarks']) ? $_POST['productRemarks'] : "";
        $productQuantity = isset($_POST['productQuantity']) ? $_POST['productQuantity'] : "";

       
        // Get the original image name and temporary file path
        $img_name = $_FILES['productImage']['name'];
        $tmp_name = $_FILES['productImage']['tmp_name'];

        // Construct the new filename using student_id
        $newFileName = $productName . '.jpeg';

        // Specify the destination directory
        // $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . '/assThree/images/';

        // Move the uploaded file to the destination with the new filename
        move_uploaded_file($tmp_name, "image/$newFileName");

        //  path to the saved image
        $savedImagePath = "image/".$newFileName;

         
            // Insert data into the database
            $insertSql = "INSERT INTO products (name, description, category, price, size, remarks, quantity, image_path)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(1, $productName);
            $insertStmt->bindParam(2, $productDescription);
            $insertStmt->bindParam(3, $productCategory);
            $insertStmt->bindParam(4, $productPrice);
            $insertStmt->bindParam(5, $productSize);
            $insertStmt->bindParam(6, $productRemarks);
            $insertStmt->bindParam(7, $productQuantity);
            $insertStmt->bindParam(8, $savedImagePath);

            // Execute the query
            $insertStmt->execute();

            // Display a confirmation message
            echo "Product added successfully.";

            // Close the database connection
            $pdo = null;
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("Location: AdminAfter.php?ThisProductExists");
        exit();
    }
    header("Location: AdminAfter.php?DoneAddNewProduct");
    exit();
}
?>

<!-- HTML and CSS -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input, textarea, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Product</h2>

    <form action="AddProduct.php" method="POST" enctype="multipart/form-data">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" required>

        <label for="productDescription">Product Description:</label>
        <textarea id="productDescription" name="productDescription" rows="3" required></textarea>

        <label for="productCategory">Category:</label>
        <select id="productCategory" name="productCategory">
            <option value="New Arrival">New-Arrival</option>
            <option value="On Sale">On-Sale</option>
            <option value="Featured">Featured</option>
            <option value="High Demand">High-Demand</option>
            <option value="Normal" selected>Normal</option>
        </select>

        <label for="productPrice">Price:</label>
        <input type="number" id="productPrice" name="productPrice" step="0.01" required>

        <label for="productSize">Size:</label>
        <input type="text" id="productSize" name="productSize">

        <label for="productRemarks">Remarks:</label>
        <textarea id="productRemarks" name="productRemarks" rows="3"></textarea>

        <label for="productQuantity">Quantity:</label>
        <textarea id="productQuantity" name="productQuantity"></textarea>

        <label for="productImage">Upload Image (JPG, JPEG, PNG only):</label>
        <input type="file" id="productImage" name="productImage" accept=".jpg,.jpeg,.png" required>

        <button type="submit" name="action" value="addProduct">Add Product</button>
    </form>
</div>

</body>
</html>
