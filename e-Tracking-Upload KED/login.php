<?php
session_start();
include 'db.php'; // Ensure this points to your database connection file

// Change database connection to the correct database
$host = 'localhost'; // Database host
$db = 'ked_tracking_pnn'; // Correct database name
$user = 'root'; // Database username
$pass = ''; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php'); // Redirect to the admin dashboard
        } else {
            $error = "You do not have admin access.";
        }

        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            background-image: url('asset/9564417.jpg'); /* Ganti dengan path gambar latar belakang */
            background-size: cover; /* Mengatur ukuran gambar agar menutupi seluruh latar belakang */
            background-position: center; /* Memusatkan gambar */
            height: 100vh; /* Tinggi penuh */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background-color:rgba(255, 255, 255, 0.76); /* White background */
            padding: 50px; /* Increased padding for a more spacious look */
            border-radius: 20px; /* Slightly larger border radius */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
            width: 85%; /* Full width */
            max-width: 450px; /* Increased maximum width */
            text-align: center; /* Center text alignment */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Space between elements */
        }
        h1 {
            font-size: 24px; /* Increased font size */
            margin-bottom: 30px; /* Increased margin for spacing */
            color: #333; /* Darker text color for better readability */
        }
        input {
            margin-bottom: 15px;
            padding: 10px; /* Added padding for input fields */
            border: 1px solid #ccc; /* Light border for input fields */
            border-radius: 5px; /* Rounded corners for input fields */
            width: 100%; /* Full width for input fields */
        }
        button {
            background-color: #007bff; /* Primary button color */
            color: white; /* White text color */
            padding: 8px; /* Reduced padding for a smaller button */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners for buttons */
            cursor: pointer; /* Pointer cursor on hover */
            margin-top: auto; /* Push button to the bottom */
            width: 80%; /* Set button width to 80% */
        }
        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="text-2xl font-bold">eTransit-Tracking panen PT KED</h1> <!-- Added title above the login container -->
        <h1 class="text-2xl font-bold">Login</h1>
        <form method="POST">
            <input type="text" name="email" placeholder="Email" required class="border p-2 w-full">
            <input type="password" name="password" placeholder="Password" required class="border p-2 w-full">
            <button type="submit" name="login" class="bg-blue-500 text-white py-2 rounded-md w-full">Login</button>
        </form>
        <?php if (isset($error)) echo "<p class='text-red-500 text-center mt-2'>$error</p>"; ?>
    </div>
</body>
</html>
