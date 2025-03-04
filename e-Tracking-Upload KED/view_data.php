<?php
$servername = "localhost";
$username = "root";
$password = ""; // No password as per the user's request
$dbname = "test_eTracking_TPN";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$afd = isset($_POST['afd']) ? $_POST['afd'] : '';
$wilayah = isset($_POST['wilayah']) ? $_POST['wilayah'] : '';
$search = isset($_POST['search']) ? $_POST['search'] : ''; // Initialize search variable
$searchWithWildcards = "%$search%"; // Prepare search term with wildcards

// Fetch data from uploads table with filtering
$sql = "SELECT u.id, u.user_id, u.afd, u.wilayah, u.tanggal, u.file_path, 
        CONCAT('TPN-', u.afd, '-', u.wilayah, '-', FLOOR(RAND() * 10000)) AS 'ID FILE' 
        FROM uploads u 
        WHERE (u.afd = ? OR ? = '') AND (u.wilayah = ? OR ? = '') AND (u.file_path LIKE ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}




$afd = isset($_POST['afd']) ? $_POST['afd'] : '';
$wilayah = isset($_POST['wilayah']) ? $_POST['wilayah'] : '';
$search = isset($_POST['search']) ? $_POST['search'] : ''; // Initialize search variable
$searchWithWildcards = "%$search%"; // Prepare search term with wildcards

// Fetch data from uploads table with filtering
$sql = "SELECT u.id, u.user_id, u.afd, u.wilayah, u.tanggal, u.file_path, 
        CONCAT('TPN-', u.afd, '-', u.wilayah, '-', FLOOR(RAND() * 10000)) AS 'ID FILE' 
        FROM uploads u 
        WHERE (u.afd = ? OR ? = '') AND (u.wilayah = ? OR ? = '') AND (u.file_path LIKE ?)";


$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $afd, $afd, $wilayah, $wilayah, $searchWithWildcards);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Error executing query: " . $stmt->error);
}

if (!$result) {
    die("Error executing query: " . $stmt->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Uploaded Data</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <form method="POST" class="mb-4">
        <input type="text" name="search" placeholder="Search..." class="border p-2 mb-4" style="float: right;">
        <select name="afd" class="border p-2 mr-2">
            <option value="">Select AFD</option>
            <option value="OA">OA</option>
            <option value="OB">OB</option>
            <option value="OC">OC</option>
            <option value="OD">OD</option>
            <option value="OE">OE</option>
            <option value="OF">OF</option>
            <option value="OG">OG</option>
            <option value="OH">OH</option>
            <option value="OI">OI</option>
            <option value="OJ">OJ</option>
            <option value="OK">OK</option>
            <option value="OL">OL</option>
            <option value="OM">OM</option>
            <option value="ON">ON</option>
            <option value="OO">OO</option>
            <option value="OP">OP</option>
            <option value="OQ">OQ</option>
            <option value="OR">OR</option>
        </select>
        <select name="wilayah" class="border p-2 mr-2">
            <option value="">Select Wilayah</option>
            <option value="Wilayah 1">Wilayah 1</option>
            <option value="Wilayah 2">Wilayah 2</option>
            <option value="Wilayah 3">Wilayah 3</option>
            <option value="Wilayah 4">Wilayah 4</option>
        </select>
        <button type="submit" class="bg-blue-500 text-white py-2 rounded-md">Search</button>
    </form>
    <div class="max-w-full mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Uploaded Data</h1>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr class="bg-gray-300">
                    <th class="border border-gray-400 px-4 py-2">ID</th>
                    <th class="border border-gray-400 px-4 py-2">ID FILE</th>
                    <th class="border border-gray-400 px-4 py-2">AFD</th>
                    <th class="border border-gray-400 px-4 py-2">Wilayah</th>
                    <th class="border border-gray-400 px-4 py-2">Tanggal</th>
                    <th class="border border-gray-400 px-4 py-2">Nama File</th>
                    <th class="border border-gray-400 px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class='border border-gray-400 px-4 py-2'>{$row['id']}</td>
                                <td class='border border-gray-400 px-4 py-2'>{$row['ID FILE']}</td>
                                <td class='border border-gray-400 px-4 py-2'>{$row['afd']}</td>
                                <td class='border border-gray-400 px-4 py-2'>{$row['wilayah']}</td>
                                <td class='border border-gray-400 px-4 py-2'>{$row['tanggal']}</td>
                                <td class='border border-gray-400 px-4 py-2'>{$row['file_path']}</td>
                                <td class='border border-gray-400 px-4 py-2'>".(isset($row['status']) ? $row['status'] : 'OK')."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='border border-gray-400 px-4 py-2'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="upload.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition duration-200 inline-block mt-4">Back to Upload</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
