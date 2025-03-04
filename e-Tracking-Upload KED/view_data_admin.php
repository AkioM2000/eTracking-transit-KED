<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f0f9f4; /* Light green background */
            font-family: 'Arial', sans-serif; /* Set a default font */
            transition: margin-left 0.3s; /* Smooth transition for content */
        }
        .header {
            background-color: #4caf50; /* Darker green header */
            padding: 20px;
            text-align: center;
            color: white;
            position: relative; /* Position relative for absolute positioning of icon */
        }
        .sidebar {
            background-color: #4caf50; /* Sidebar color */
            height: 100vh; /* Full height */
            padding: 20px;
            position: fixed; /* Fixed position */
            width: 250px; /* Width of the sidebar */
            transition: transform 0.3s ease; /* Smooth transition */
            transform: translateX(-100%); /* Hide sidebar by default */
        }
        .sidebar.open {
            transform: translateX(0); /* Show sidebar */
        }
        .content {
            margin-left: 0; /* No margin initially */
            padding: 20px;
            transition: margin-left 0.3s; /* Smooth transition for content */
        }
        .content.shifted {
            margin-left: 270px; /* Space for sidebar when open */
        }
        .toggle-sidebar {
            cursor: pointer; /* Change cursor to pointer */
            position: absolute; /* Position absolute to place it in the header */
            left: 20px; /* Position from the left */
            top: 20px; /* Position from the top */
            font-size: 24px; /* Size of the icon */
            color: white; /* Color of the icon */
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse; /* Collapse table borders */
            width: 100%; /* Full width */
        }
        th, td {
            padding: 12px; /* Add padding to table cells */
            text-align: left; /* Align text to the left */
        }
        th {
            background-color: #c8e6c9; /* Light green background for header */
        }
        tr:nth-child(even) {
            background-color: #f1f8e9; /* Light background for even rows */
        }
        tr:hover {
            background-color: #dcedc8; /* Highlight row on hover */
        }
    </style>
</head>
<body>
    <div class="header">
        <i class="fas fa-user-circle toggle-sidebar" onclick="toggleSidebar()"></i>
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    </div>
    <div class="sidebar" id="sidebar">
        <h2 class="text-xl text-white">Menu</h2>
        <ul class="mt-4">
            <li><a href="upload.php" class="text-white hover:underline">Access Upload File</a></li>
            <li><a href="view_data.php" class="text-white hover:underline">View Uploaded Data by User</a></li>
            <li><a href="admin_download.php" class="text-white hover:underline">Download User Uploads</a></li> <!-- Added link to download page -->
            <li><a href="add_user.php" class="text-white hover:underline">Add User</a></li>
        </ul>
    </div>
    <div class="content" id="content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Overview</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="card">
                <canvas id="activeUsersChart"></canvas>
            </div>
            <div class="card">
                <canvas id="uploadsSummaryChart"></canvas>
            </div>
            <div class="card">
                <canvas id="uploadsByAfdChart"></canvas>
            </div>
        </div>

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
        }

        // Fetch active users count
        $activeUsersQuery = "SELECT COUNT(*) as active_users FROM users";
        $activeUsersResult = $pdo->query($activeUsersQuery);
        $activeUsersCount = $activeUsersResult->fetch(PDO::FETCH_ASSOC)['active_users'];

        // Fetch uploads summary per AFD
        $uploadsByAfdQuery = "SELECT afd, COUNT(*) as upload_count FROM uploads GROUP BY afd";
        $uploadsByAfdResult = $pdo->query($uploadsByAfdQuery);
        $uploadsByAfd = [];
        while ($row = $uploadsByAfdResult->fetch(PDO::FETCH_ASSOC)) {
            $uploadsByAfd[$row['afd']] = $row['upload_count'];
        }

        $uploadsSummaryQuery = "SELECT afd, COUNT(*) as upload_count FROM uploads GROUP BY afd";
        $uploadsSummaryResult = $pdo->query($uploadsSummaryQuery);
        $uploadsSummary = [];
        while ($row = $uploadsSummaryResult->fetch(PDO::FETCH_ASSOC)) {
            $uploadsSummary[$row['afd']] = $row['upload_count'];
        }
        ?>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                sidebar.classList.toggle('open');
                content.classList.toggle('shifted');
            }

            // Update chart data with fetched values
            const activeUsersData = {
                labels: ['Active Users'],
                datasets: [{
                    label: 'Active Users',
                    data: [<?php echo $activeUsersCount; ?>],
                    backgroundColor: 'rgba(0, 255, 0, 0.5)',
                    borderColor: 'rgba(0, 255, 0, 1)',
                    borderWidth: 1
                }]
            };

            const uploadsSummaryData = {
                labels: <?php echo json_encode(array_keys($uploadsSummary)); ?>,
                datasets: [{
                    label: 'Uploads per AFD',
                    data: <?php echo json_encode(array_values($uploadsSummary)); ?>,
                    backgroundColor: 'rgba(0, 0, 255, 0.5)',
                    borderColor: 'rgba(0, 0, 255, 1)',
                    borderWidth: 1
                }]
            };

            const uploadsByAfdData = {
                labels: <?php echo json_encode(array_keys($uploadsByAfd)); ?>,
                datasets: [{
                    label: 'Uploads per AFD',
                    data: <?php echo json_encode(array_values($uploadsByAfd)); ?>,
                    backgroundColor: 'rgba(255, 165, 0, 0.5)',
                    borderColor: 'rgba(255, 165, 0, 1)',
                    borderWidth: 1
                }]
            };

            const activeUsersChart = new Chart(document.getElementById('activeUsersChart'), {
                type: 'bar',
                data: activeUsersData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const uploadsSummaryChart = new Chart(document.getElementById('uploadsSummaryChart'), { 
                type: 'pie',
                data: uploadsSummaryData,
                options: {
                    responsive: true
                }
            });

            const uploadsByAfdChart = new Chart(document.getElementById('uploadsByAfdChart'), {
                type: 'bar',
                data: uploadsByAfdData,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <h2 class="text-2xl font-bold mt-6">Registered Users</h2>
        <p class="text-lg">Active Users: <?php echo $activeUsersCount; ?></p>

        <div class="clearfix"></div>

        <div class="mt-4"></div>

        <table class="min-w-full bg-white border border-gray-300 mt-4" id="userTable">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Username</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Interaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch registered users
                $usersQuery = "SELECT id, username, email FROM users";
                $usersResult = $pdo->query($usersQuery);
                while ($user = $usersResult->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td class='border border-gray-300 px-4 py-2'>{$user['id']}</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>{$user['username']}</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>{$user['email']}</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>";
                    echo "<a href='edit_user.php?id={$user['id']}' class='text-blue-500'><i class='fas fa-pencil-alt'></i></a>&nbsp;&nbsp;";
                    echo "<form action='delete_user.php' method='POST' class='inline'>";
                    echo "<input type='hidden' name='id' value='{$user['id']}'>";
                    echo "<button type='submit' class='bg-red-500 text-white font-bold py-1 px-2 rounded'><i class='fas fa-trash'></i></button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
