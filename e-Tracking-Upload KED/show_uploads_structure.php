<?php
$host = 'localhost';
$db = 'e_tracking_upload';
$user = 'root';
$pass = ''; // Assuming no password for root

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $pdo->query("DESCRIBE uploads");
    $structure = $query->fetchAll(PDO::FETCH_ASSOC);

    echo "Structure of the 'uploads' table:\n";
    foreach ($structure as $column) {
        echo "Field: " . $column['Field'] . ", Type: " . $column['Type'] . ", Null: " . $column['Null'] . ", Key: " . $column['Key'] . ", Default: " . $column['Default'] . ", Extra: " . $column['Extra'] . "\n";
    }
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
