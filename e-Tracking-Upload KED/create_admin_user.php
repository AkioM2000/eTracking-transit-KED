<?php
include 'db.php'; // Ensure this points to your database connection file

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ked_tracking_pnn", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare to insert a new admin user
    $email = 'admin@tracking.co.id';
    $password = password_hash('password123', PASSWORD_DEFAULT); // Hash the password
    $role = 'admin';

    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
    $stmt->execute(['email' => $email, 'password' => $password, 'role' => $role]);

    echo "New admin user created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
