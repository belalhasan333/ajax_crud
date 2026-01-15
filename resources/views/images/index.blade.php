<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Multiple Image Upload</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #f8f9ff;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .upload-area:hover {
            border-color: #764ba2;
            background: #f0f2ff;
        }

        .upload-area.dragover {
            border-color: #4caf50;
            background: #e8f5e9;
        }

        .upload-icon {
            font-size: 50px;
            color: #667eea;
            margin-bottom: 15px;
        }

        #imageInput {
            display: none;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .preview-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f44336;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .remove-btn:hover {
            background: #d32f2f;
            transform: scale(1.1);
        }

        .file-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            font-size: 12px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .file-count {
            margin-top: 15px;
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
        }

        /* Added styles for gallery */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .del {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f44336;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .del:hover {
            background: #d32f2f;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Multiple Image Upload with Preview</h2>

        <div id="alertContainer"></div>

        <div class="upload-area" id="uploadArea">
            <div class="upload-icon">☁️</div>
            <p style="font-size: 18px; color: #333; margin-bottom: 10px;">
                Click to upload or drag and drop
            </p>
            <p style="color: #666; font-size: 14px;">
                PNG, JPG, GIF, WEBP up to 2MB (Max 10 images)
            </p>
            <input type="file" id="imageInput" name="images[]" multiple accept="image/*">
            <button type="button" class="btn" onclick="document.getElementById('imageInput').click()">
                Choose Files
            </button>
        </div>

        <div class="file-count" id="fileCount"></div>

        <div class="preview-container" id="previewContainer"></div>

        <div style="text-align: center; margin-top: 30px;">
            <button type="button" class="btn" id="uploadBtn" disabled>
                Upload Images
            </button>
            <button type="button" class="btn" id="clearBtn" style="background: #f44336; margin-left: 10px;"
                disabled>
                Clear All
            </button>
        </div>

        <h3>📁 Image Gallery (<span id="count">{{ $images->count() }}</span>)</h3>

        <div class="gallery" id="gallery">
            @foreach ($images as $img)
                <div class="card" data-id="{{ $img->id }}">
                    <img src="{{ asset($img->path) }}">
                    <button class="del">×</button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="loading" id="loading">
        <div class="spinner"></div>
        <p style="margin-top: 10px; color: #667eea;">Uploading...</p>
    </div>

    <script>
        let selectedFiles = [];

        const imageInput = document.getElementById('imageInput');
        const uploadArea = document.getElementById('uploadArea');
        const previewContainer = document.getElementById('previewContainer');
        const uploadBtn = document.getElementById('uploadBtn');
        const clearBtn = document.getElementById('clearBtn');
        const fileCount = document.getElementById('fileCount');
        const loading = document.getElementById('loading');
        const alertContainer = document.getElementById('alertContainer');
        const gallery = document.getElementById('gallery');
        const countSpan = document.getElementById('count');

        // File input change
        imageInput.addEventListener('change', handleFiles);

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files);
            handleFilesArray(files);
        });

        function handleFiles(e) {
            const files = Array.from(e.target.files);
            handleFilesArray(files);
        }

        function handleFilesArray(files) {
            // Validate file types
            const validFiles = files.filter(file => {
                return file.type.startsWith('image/');
            });

            if (validFiles.length !== files.length) {
                showAlert('Some files were skipped (only images allowed)', 'error');
            }

            // Check total file count
            if (selectedFiles.length + validFiles.length > 10) {
                showAlert('Maximum 10 images allowed', 'error');
                return;
            }

            validFiles.forEach(file => {
                // Check file size (2MB)
                if (file.size > 2048 * 1024) {
                    showAlert(`${file.name} is too large (max 2MB)`, 'error');
                    return;
                }

                selectedFiles.push(file);
                displayPreview(file);
            });

            updateUI();
        }

        function displayPreview(file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.dataset.filename = file.name;

                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <button class="remove-btn" onclick="removeFile('${file.name}')">&times;</button>
                    <div class="file-info">
                        ${file.name.substring(0, 20)}${file.name.length > 20 ? '...' : ''}<br>
                        ${(file.size / 1024).toFixed(1)} KB
                    </div>
                `;

                previewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }

        function removeFile(filename) {
            selectedFiles = selectedFiles.filter(file => file.name !== filename);
            const previewItem = document.querySelector(`[data-filename="${filename}"]`);
            if (previewItem) {
                previewItem.remove();
            }
            updateUI();
        }

        function updateUI() {
            const count = selectedFiles.length;
            fileCount.textContent = count > 0 ? `${count} file${count > 1 ? 's' : ''} selected` : '';
            uploadBtn.disabled = count === 0;
            clearBtn.disabled = count === 0;
        }

        function updateGalleryCount() {
            countSpan.textContent = document.querySelectorAll('.card').length;
        }

        // Upload button
        uploadBtn.addEventListener('click', async () => {
            if (selectedFiles.length === 0) return;

            const formData = new FormData();
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            loading.style.display = 'block';
            uploadBtn.disabled = true;
            clearBtn.disabled = true;

            try {
                const response = await fetch('{{ route('image.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                    data.images.forEach(img => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.dataset.id = img.id;
                        card.innerHTML = `
                            <img src="{{ asset('') }}${img.path}">
                            <button class="del">×</button>
                        `;
                        gallery.appendChild(card);
                    });
                    clearAll();
                    initDeleteButtons();
                    updateGalleryCount();
                } else {
                    showAlert(data.message || 'Upload failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Upload failed: ' + error.message, 'error');
            } finally {
                loading.style.display = 'none';
                updateUI();
            }
        });

        // Clear button
        clearBtn.addEventListener('click', clearAll);

        function clearAll() {
            selectedFiles = [];
            previewContainer.innerHTML = '';
            imageInput.value = '';
            updateUI();
        }

        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertContainer.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Delete functionality
        function initDeleteButtons() {
            document.querySelectorAll('.del').forEach(btn => {
                if (!btn.dataset.listenerAdded) {
                    btn.dataset.listenerAdded = 'true';
                    btn.addEventListener('click', async () => {
                        const card = btn.closest('.card');
                        const id = card.dataset.id;

                        try {
                            const deleteUrl = '{{ route('image.destroy', ['id' => ':id']) }}'.replace(
                                ':id', id);
                            const response = await fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                showAlert(data.message, 'success');
                                card.remove();
                                updateGalleryCount();
                            } else {
                                showAlert(data.message || 'Delete failed', 'error');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showAlert('Delete failed: ' + error.message, 'error');
                        }
                    });
                }
            });
        }

        // Initialize delete buttons on page load
        initDeleteButtons();
    </script>
</body>

</html>
