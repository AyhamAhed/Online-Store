<?php
include 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data (implement as needed)

    // Process login credentials
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $pdo = db_connect();
    $sql = "SELECT * FROM customers WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    

    if ($user) {
        // Verify the password
        if ( $password == $user['password_hash']) {
            // Login successful
            session_start();
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['customer_name'] = $user['name'];
            header("Location: CustomerAfter.php?done_login&customer_id=" . $_SESSION['user_name']);
            exit();
        } else {
            // Incorrect password
            header("Location: index.php?error=invalid_login");
            exit();
        }
    }
     else {
        // User not found
        header("Location: index.php?error=user_not_found");
        
    }
    
    $sql = "SELECT * FROM administrators WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if ( $password == $user['password']) {
            // Login successful
            session_start();
            $_SESSION['customer_name'] = $user['username'];
            header("Location: adminAfter.php?done_login"); // Redirect to the dashboard 
            exit();
        } else {
            // Incorrect password
            header("Location: index.php?error=invalid_login");
            exit();
        }
    }
    

}
?>

<div class="containerLogin">
    
    <form action="Login.php" id="LoginForm" method="post">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" name="username" placeholder="Enter your username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <p>As a Demo? <a href="Demo.php">Demo Account</a></p>
</div>
