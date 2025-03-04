<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <nav class="bg-white p-4 rounded shadow mb-6">
        <ul class="flex space-x-4">
            <li><a href="upload.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-200">Upload</a></li>
            <li><a href="view_data.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-200">View Data</a></li>

        </ul>
    </nav>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Upload File</h1>
        <form id="uploadForm" action="upload_handler.php" method="post" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
        <div id="uploadMessage" class="text-red-500 mt-4"></div>


            <div class="mb-4">
                <label for="afd" class="block text-sm font-medium text-gray-700">AFD:</label>
                <select name="afd" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <option value="ALL">ALL</option>
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
            </div>

            <div class="mb-4">
                <label for="wilayah" class="block text-sm font-medium text-gray-700">Wilayah:</label>
                <select name="wilayah" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <option value="ALL">All Wilayah</option>
                    <option value="Wilayah 1">Wilayah 1</option>
                    <option value="Wilayah 2">Wilayah 2</option>
                    <option value="Wilayah 3">Wilayah 3</option>
                    <option value="Wilayah 4">Wilayah 4</option>

                </select>
            </div>

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Select file:</label>
                <input type="file" name="file" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
<button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md transition duration-200">Upload</button>
<script>
    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent the default form submission
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        const messageDiv = document.getElementById('uploadMessage');
        messageDiv.textContent = ''; // Clear previous messages

        // Perform AJAX request to upload_handler.php
        fetch('upload_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Display success or error message based on response
            messageDiv.textContent = data; // Assuming the response contains the message
            form.reset(); // Reset the form after submission
        })
        .catch(error => {
            messageDiv.textContent = 'An error occurred during the upload.';
        });
    }
</script>

<script>
    function validateForm() {
        const fileInput = document.querySelector('input[type="file"]');
        const messageDiv = document.getElementById('uploadMessage');
        messageDiv.textContent = ''; // Clear previous messages

        if (fileInput.files.length === 0) {
            messageDiv.textContent = 'Please select a file to upload.';
            return false; // Prevent form submission
        }

        // If the file is selected, clear any previous messages
        return true; // Allow form submission
    }
</script>


        </form>
    </div>
    <footer class="mt-6 text-center">
        <p class="text-gray-600">© 2023 e-Tracking-Upload KED. All rights reserved.</p>
    </footer>
</body>
</html>
