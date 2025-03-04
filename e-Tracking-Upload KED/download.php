<?php
session_start();
include 'db.php'; // Ensure this points to your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth.php'); // Redirect to the login page if not logged in
    exit();
}

// Process file download based on the selected date
if (isset($_POST['upload_date'])) {
    $upload_date = $_POST['upload_date'];

    // Fetch files uploaded on the selected date
    $sql = "SELECT file_path FROM uploads WHERE DATE(tanggal) = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$upload_date]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        // Create a zip file to download
        $zip = new ZipArchive();
        $zipFileName = "uploads_$upload_date.zip";
        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            exit("Cannot open <$zipFileName>\n");
        }

        foreach ($result as $row) {
            $filePath = $row['file_path'];
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }
        $zip->close();

        // Set headers to download the zip file
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipFileName);
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);

        // Delete the zip file after download
        unlink($zipFileName);
    } else {
        echo "No files found for the selected date.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Download Files</title>
</head>
<body>
    <h1>Download Files by Date</h1>
    <form method="POST">
        <input type="date" name="upload_date" required>
        <button type="submit" class="bg-green-500 text-white py-2 rounded-md">Download Files</button>
    </form>
</body>
</html>
