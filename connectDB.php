<?php
define('DBHOST', 'localhost');
define('DBNAME', 'onlinestoreDB');
define('DBUSER', 'root');
define('DBPASS', '');
function db_connect($dbhost = DBHOST, $dbname = DBNAME , $username = DBUSER, $password = DBPASS){
    try {

        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
        // echo 'done connection';
        return $pdo;

    } catch (PDOException $e) {
        die($e->getMessage());
    }
}
?>
