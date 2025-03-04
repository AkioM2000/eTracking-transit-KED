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

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $afd = isset($_POST['afd']) ? $_POST['afd'] : '';
    $wilayah = isset($_POST['wilayah']) ? $_POST['wilayah'] : '';
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;

    // Validate inputs
    if (empty($afd) || empty($wilayah) || !$file) {
        die("Error: All fields are required.");
    }


    // File upload path
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);

    // Check for upload errors
    if ($file["error"] === UPLOAD_ERR_OK) {
        // Move uploaded file to target directory
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO uploads (afd, wilayah, tanggal, file_path) VALUES (?, ?, NOW(), ?)");


            $stmt->bind_param("sss", $afd, $wilayah, $file["name"]);

            if ($stmt->execute()) {
                echo "File uploaded and data saved successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }


    // File upload path
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);

    // Check for upload errors
    if ($file["error"] === UPLOAD_ERR_OK) {
        // Move uploaded file to target directory
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO uploads (afd, wilayah, tanggal, file_path) VALUES (?, ?, NOW(), ?)");


            $stmt->bind_param("sss", $afd, $wilayah, $file["name"]);

                echo "File uploaded and data saved successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

                echo "File uploaded and data saved successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file. Please try again.";

        }
    } else {
        echo "File upload error: " . $file["error"];
    }
}

$conn->close();
?>
