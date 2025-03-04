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

// Get user ID from URL
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Delete user from the database
    $deleteQuery = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($deleteQuery);
    if ($stmt->execute(['id' => $userId])) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID provided.";
}

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit;
?>
