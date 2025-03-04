<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold">Edit User</h1>
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
        if (isset($_GET['id'])) {
            $userId = $_GET['id'];

            // Fetch user details
            $userQuery = "SELECT id, username, email FROM users WHERE id = :id";
            $stmt = $pdo->prepare($userQuery);
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Display the edit form
                echo '<form action="update_user.php" method="POST">';
                echo '<input type="hidden" name="id" value="' . $user['id'] . '">';
                echo '<div class="mb-4">';
                echo '<label for="username" class="block text-sm font-medium text-gray-700">Username</label>';
                echo '<input type="text" name="username" id="username" value="' . $user['username'] . '" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>';
                echo '</div>';
                echo '<div class="mb-4">';
                echo '<label for="email" class="block text-sm font-medium text-gray-700">Email</label>';
                echo '<input type="email" name="email" id="email" value="' . $user['email'] . '" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>';
                echo '</div>';
                echo '<button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Update User</button>';
                echo '</form>';
            } else {
                echo '<p>User not found.</p>';
            }
        } else {
            echo '<p>No user ID provided.</p>';
        }
        ?>
    </div>
</body>
</html>
