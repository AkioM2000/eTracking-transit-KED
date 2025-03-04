<?php
// Database connection setup
$host = 'localhost'; // Database host
$db = 'ked-tracking-pnn'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update user details in the database
    $updateQuery = "UPDATE users SET username = :username, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute(['username' => $username, 'email' => $email, 'id' => $userId]);

    // Redirect back to the admin dashboard
    header('Location: admin_dashboard.php');
    exit;
}
?>
