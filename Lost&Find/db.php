
<?php

$host = "localhost";
$dbname = "lost_and_find";
$username = "root";
$password = "";

try {

    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e) {

    die("Database Connection Failed: " . $e->getMessage());

}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
