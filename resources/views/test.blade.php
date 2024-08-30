<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Preview Example</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">
    <div class="flex justify-center items-center w-full">
        <div class="relative w-full max-w-md" id="content">
            <form id="upload-form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-end space-x-2 p-4 bg-white rounded-lg shadow-lg">
                    <div class="flex flex-col flex-1 bg-gray-200 rounded-lg">
                        <div class="flex flex-wrap">
                            <div id="image-container">
                                <!-- Image will be inserted here -->
                            </div>
                        </div>
                        <div class="flex flex-col p-2">
                            <input type="text" id="message-input" class="bg-gray-200 w-full rounded-lg focus:outline-none" placeholder="Aa">
                            <input type="hidden" id="image-data" name="image-data" />
                        </div>
                    </div>
                    <input type="file" id="fileInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                    <button type="button" class="text-xl px-2 py-2 hover:text-gray-300 text-white" onclick="document.getElementById('fileInput').click();">
                        <i class="fa-solid fa-image"></i>
                    </button>
                    <!-- FontAwesome (if needed) -->
                    <script src="{{ asset('asset/js/ajax.min.js') }}"></script>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-800 text-white rounded-full whitespace-nowrap">Send</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            
            // Check if file is selected and is an image
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-40', 'image-preview', 'rounded-lg', 'shadow', 'm-2');
                    document.getElementById('image-container').innerHTML = ''; 
                    document.getElementById('image-container').appendChild(img);

                    // Set the base64 data URL to the hidden input
                    document.getElementById('image-data').value = e.target.result;
                };
                
                reader.readAsDataURL(file);
            } else {
                alert('Please select a valid image file.');
            }
        }

        // Function to generate a unique identifier
        function generateUniqueId() {
            return 'xxxxxx'.replace(/[x]/g, function() {
                var r = Math.random() * 16 | 0, v = r.toString(16);
                return v;
            });
        }

        // Handle paste event for image files
        document.getElementById('content').addEventListener('paste', function(event) {
            event.preventDefault();

            if (event.clipboardData && event.clipboardData.items) {
                const items = event.clipboardData.items;

                for (const item of items) {
                    if (item.type.startsWith('image/')) {
                        const file = item.getAsFile();

                        if (file) {
                            // Generate a unique identifier
                            const uniqueId = generateUniqueId();

                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('w-40', 'image-preview', 'rounded-lg', 'shadow', 'm-2');
                                document.getElementById('image-container').innerHTML = ''; 
                                document.getElementById('image-container').appendChild(img);

                                // Set the base64 data URL to the hidden input
                                document.getElementById('image-data').value = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
