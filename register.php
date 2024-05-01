<?php
session_start();

include 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data (implement as needed)
    $pdo = db_connect();
    // Process customer info
    $name = $_POST['name'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $idNumber = $_POST['id_number'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    // Process credit card details
    $creditCardNumber = $_POST['credit_card_number'];
    $creditCardExpirationDate = $_POST['credit_card_expiration_date'];
    $creditCardName = $_POST['credit_card_name'];
    $creditCardBank = $_POST['credit_card_bank'];

    // Process e-account info
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password != $confirmPassword) {
        // Passwords don't match, handle accordingly (e.g., show error message)
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // Check if the username already exists
    $checkUsernameSql = "SELECT * FROM customers WHERE username = ?";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
    $checkUsernameStmt->bindValue(1, $username);
    $checkUsernameStmt->execute();
    $result = $checkUsernameStmt->fetch();

    if ($result) {
        // Username already exists, handle accordingly (e.g., show error message)
        header("Location: register.php?error=username_exists");
        exit();
    }

    // Insert data into the database
    $insertSql = "INSERT INTO customers (name, address, dob, id_number, email, telephone, credit_card_number, credit_card_expiration_date, credit_card_name, credit_card_bank, username, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindParam(1, $name);
    $insertStmt->bindParam(2, $address);
    $insertStmt->bindParam(3, $dob);
    $insertStmt->bindParam(4, $idNumber);
    $insertStmt->bindParam(5, $email);
    $insertStmt->bindParam(6, $telephone);
    $insertStmt->bindParam(7, $creditCardNumber);
    $insertStmt->bindParam(8, $creditCardExpirationDate);
    $insertStmt->bindParam(9, $creditCardName);
    $insertStmt->bindParam(10, $creditCardBank);
    $insertStmt->bindParam(11, $username);
    $insertStmt->bindParam(12, $password);

    // Execute the query
    $insertStmt->execute();

    // // Generate a unique customer ID (you can use a better method)
    // $customerID = mt_rand(1000000000, 9999999999);

    // Store the customer ID in the session
    $_SESSION['customer_name'] = $result['username'];

    // Redirect to confirmation page
    header("Location: index.php?done_register");
    exit();
}

// Close the database connection
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Customer Registration</title>
</head>
<body>
    <div class="containerRegister">
        <h2>Customer Registration</h2>
        <form id="registerForm" method="post">
            <!-- Customer Info -->
            <label for="name">Name:</label>
            <input type="text" name="name" placeholder="Enter your name" required>

            <label for="address">Address:</label>
            <input type="text" name="address" placeholder="Enter your address" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" placeholder="Select your date of birth" required>

            <label for="id_number">ID Number:</label>
            <input type="text" name="id_number" placeholder="Enter your ID number" required>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Enter your email address" required>

            <label for="telephone">Telephone:</label>
            <input type="text" name="telephone" placeholder="Enter your telephone number" required>

            <!-- Credit Card Details -->
            <label for="credit_card_number">Credit Card Number:</label>
            <input type="text" name="credit_card_number" placeholder="Enter your credit card number" required>

            <label for="credit_card_expiration_date">Credit Card Expiration Date:</label>
            <input type="date" name="credit_card_expiration_date" placeholder="Select expiration date" required>

            <label for="credit_card_name">Credit Card Holder Name:</label>
            <input type="text" name="credit_card_name" placeholder="Enter the card holder's name" required>

            <label for="credit_card_bank">Credit Card Issuing Bank:</label>
            <input type="text" name="credit_card_bank" placeholder="Enter the issuing bank" required>

            <!-- E-Account Info -->
            <label for="username">Username (6-13 characters):</label>
            <input type="text" name="username" minlength="6" maxlength="13" placeholder="Choose a username" required>

            <label for="password">Password (8-12 characters):</label>
            <input type="password" name="password" minlength="8" maxlength="12" placeholder="Enter a password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" minlength="8" maxlength="12" placeholder="Confirm your password" required>

            <!-- Submit Button -->
            <button type="submit">Register</button>
            
                <p>Already have an account? <a href="index.php"> Login here</a></p>
            
        
        </form>
    </div>
</body>
</html>
