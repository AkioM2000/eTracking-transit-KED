<?php
include 'db.php'; // Ensure this points to your database connection file

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ked_tracking_pnn", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete existing admin users
    $stmt = $pdo->prepare("DELETE FROM users WHERE role = :role");
    $stmt->execute(['role' => 'admin']);
    echo "Admin users deleted.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
