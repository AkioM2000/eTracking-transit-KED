<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Download Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function confirmDownload() {
            if (confirm("Are you sure you want to export all data for the selected date?")) {
                document.getElementById('downloadForm').submit(); // Submit the form if confirmed
            }
        }
    </script>
</head>
<body class="bg-gray-100 p-6">
    <div class="w-full h-full bg-white p-6 rounded shadow">
        <form method="POST" class="mb-4 text-center" id="filterForm">
            <label for="afd" class="mr-2">Select AFD:</label>
            <select id="afd" name="afd" class="border rounded p-2">
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
            <label for="wilayah" class="mr-2">Select Wilayah:</label>
            <select id="wilayah" name="wilayah" class="border rounded p-2">
                <option value="">Select Wilayah</option>
                <option value="Wilayah 1">Wilayah 1</option>
                <option value="Wilayah 2">Wilayah 2</option>
                <option value="Wilayah 3">Wilayah 3</option>
                <option value="Wilayah 4">Wilayah 4</option>
            </select>

            <label for="upload_date" class="mr-2">Select Date:</label>
            <input type="date" id="upload_date" name="upload_date" class="border rounded p-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>
        <h1 class="text-3xl font-bold mb-4 text-center">Download Uploaded Files</h1>
        <p class="mb-4 text-center">Below is the list of files uploaded by users. Click on the links to download.</p>
        <form method="POST" id="downloadForm" action="download_all.php" class="mb-4 text-center">
            <input type="hidden" name="upload_date" value="<?php echo isset($_POST['upload_date']) ? $_POST['upload_date'] : ''; ?>">
            <button type="button" onclick="confirmDownload()" class="bg-green-500 text-white px-4 py-2 rounded">Download All Files for Selected Date</button>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            $directory = 'uploads/'; // Directory where uploaded files are stored
            $files = array_filter(scandir($directory), function($file) use ($directory) {
                if ($file === '.' || $file === '..') return false;
                return true; // Show all files
            }); // Get all files in the directory

            // Initialize selected variables
            $selectedAfd = isset($_POST['afd']) ? $_POST['afd'] : '';
            $selectedWilayah = isset($_POST['wilayah']) ? $_POST['wilayah'] : '';

            // Filter files based on selected date, afd, and wilayah
            if (isset($_POST['upload_date']) && !empty($_POST['upload_date'])) {
                $selectedDate = $_POST['upload_date'];
                $files = array_filter($files, function($file) use ($directory, $selectedDate, $selectedAfd, $selectedWilayah) {
                    $fileDate = date("Y-m-d", filemtime($directory . $file));
                    return $fileDate === $selectedDate && 
                           ($selectedAfd === '' || strpos($file, $selectedAfd) !== false) && 
                           ($selectedWilayah === '' || strpos($file, $selectedWilayah) !== false);
                });
            }

            foreach ($files as $file) {
                echo '<a href="' . $directory . $file . '" class="bg-blue-500 text-white px-4 py-2 rounded text-center" download>' . $file . '</a>';
            }
            ?>
        </div>
    </div>
</body>
</html>
