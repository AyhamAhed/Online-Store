

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Online Store</title>
</head>
<body>
        <h2 class="containerIndex">Welcome to our Online Store!</h2>

        <?php
        if (!isset($_SESSION)) {
            session_start();
        }
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();
        include 'Login.php';
        ?>

</body>
</html>
