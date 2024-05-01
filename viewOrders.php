<?php
// Include the database connection file
include 'connectDB.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'updateStatus') {
    // Fetch orders from the product_receives table along with customer and product details
    $pdo = db_connect();

    $productSql = "UPDATE products SET status = ? WHERE product_id = ?";
    $productStmt = $pdo->prepare($productSql);
    $productStmt->execute([$_POST['status'], $_POST["product_id"]]);

    header("Location: AdminAfter.php?Done-Update-Status");
    exit();
}

// Fetch orders from the product_receives table along with customer and product details
$pdo = db_connect();
$sql = "SELECT rp.*, c.name AS customer_name, p.name AS product_name, p.description AS product_description, p.image_path, p.status
FROM product_receives rp
JOIN customers c ON rp.customer_name = c.username
JOIN products p ON rp.product_id = p.product_id";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>View Orders</title>
    <style>
        .order-container {
            max-width: 1000px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .order-box {
            background-color: #e0e0e0;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 8px;
            width: 300px;
            box-sizing: border-box;
            text-align: left;
        }

        .product-image {
            max-width: 100%;
            height: 100%;
            border-radius: 4px;
            margin-top: 10px;
        }

        .status-select {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- Display received orders with customer and product details in a centered box -->
<div class="order-container">
    <?php foreach ($orders as $order) : ?>
        <div class="order-box">
            <?php if (!empty($order['image_path']) && file_exists($order['image_path'])) : ?>
                <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents($order['image_path'])) ?>" alt="Product Image" class="product-image">
            <?php else : ?>
                <p><em>No Image Available</em></p>
            <?php endif; ?>
            <p><strong>Order ID:</strong> <?= $order['receive_id'] ?></p>
            <p><strong>Customer:</strong> <?= $order['customer_name'] ?></p>
            <p><strong>Product:</strong> <?= $order['product_name'] ?></p>
            <p><strong>Description:</strong> <?= $order['product_description'] ?></p>
            <p><strong>Receive Date:</strong> <?= $order['receive_date'] ?></p>

            <form action="viewOrders.php" method="POST">
                <!-- Add hidden input for product_id -->
                <input type="hidden" name="product_id" value="<?= $order['product_id'] ?>">

                <!-- Add combo box for status -->
                <label for="status">Status:</label>
                <select id="status" name="status" class="status-select">
                    <option value="waiting" <?= ($order['status'] == 'waiting') ? 'selected' : '' ?>>Waiting</option>
                    <option value="accept" <?= ($order['status'] == 'accept') ? 'selected' : '' ?>>Accept</option>
                    <option value="refuse" <?= ($order['status'] == 'refuse') ? 'selected' : '' ?>>Refuse</option>
                </select>
                <button type="submit" name="action" value="updateStatus">Update Status</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
