<?php
$host = 'localhost';
$db = 'e_tracking_upload';
$user = 'root';
$pass = ''; // Assuming no password for root

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $pdo->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables in the database '$db':\n";
    foreach ($tables as $table) {
        echo $table . "\n";
    }
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
