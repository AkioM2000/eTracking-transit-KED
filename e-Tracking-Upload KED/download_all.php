<?php
if (isset($_POST['upload_date'])) {
    $selectedDate = $_POST['upload_date'];
    $directory = 'uploads/'; // Directory where uploaded files are stored
    $zip = new ZipArchive();
    $zipFileName = "uploads_" . $selectedDate . ".zip";

    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        $files = array_filter(scandir($directory), function($file) use ($directory, $selectedDate) {
            if ($file === '.' || $file === '..') return false;
            $fileDate = date("Y-m-d", filemtime($directory . $file));
            return $fileDate === $selectedDate;
        }); // Get all files in the directory, filtered by date

        foreach ($files as $file) {
            $zip->addFile($directory . $file, $file);
        }
        $zip->close();

        // Force download the zip file
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipFileName);
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);
        // Delete the zip file after download
        unlink($zipFileName);
        exit;
    } else {
        echo "Failed to create zip file.";
    }
} else {
    echo "No date selected.";
}
?>
