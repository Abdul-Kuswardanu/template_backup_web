document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const fileListItems = document.getElementById('fileListItems');
    const uploadBtn = document.getElementById('uploadBtn');
    const clearFilesBtn = document.getElementById('clearFilesBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadResult = document.getElementById('uploadResult');
    const targetFolder = document.getElementById('target_folder');

    let selectedFiles = [];

    // Click to select files
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    function handleFiles(files) {
        const maxSize = 50 * 1024 * 1024; // 50MB
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];

        for (let file of files) {
            // Check file size
            if (file.size > maxSize) {
                showAlert('danger', file.name + ' terlalu besar (max 50MB)');
                continue;
            }

            // Check extension
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                showAlert('warning', file.name + ' - ekstensi tidak diizinkan');
                continue;
            }

            // Add to list if not duplicate
            if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
            }
        }

        updateFileList();
    }

    function updateFileList() {
        if (selectedFiles.length === 0) {
            fileList.style.display = 'none';
            return;
        }

        fileList.style.display = 'block';
        fileListItems.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'file-list-item';
            item.innerHTML = `
                <div class="file-info">
                    <i class="bi bi-file-earmark"></i> 
                    <strong>${escapeHtml(file.name)}</strong>
                    <span class="text-muted ms-2">(${formatBytes(file.size)})</span>
                </div>
                <div class="file-actions">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            fileListItems.appendChild(item);
        });
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileList();
    };

    clearFilesBtn.addEventListener('click', function() {
        selectedFiles = [];
        fileInput.value = '';
        updateFileList();
        uploadResult.innerHTML = '';
    });

    uploadBtn.addEventListener('click', function() {
        if (selectedFiles.length === 0) {
            showAlert('warning', 'Pilih file terlebih dahulu');
            return;
        }

        uploadFiles();
    });

    function uploadFiles() {
        const formData = new FormData();
        formData.append('target_folder', targetFolder.value);

        selectedFiles.forEach((file, index) => {
            formData.append('file_' + index, file);
        });

        // Show progress
        uploadProgress.style.display = 'block';
        progressBar.style.width = '0%';
        progressText.textContent = 'Mengupload...';
        uploadBtn.disabled = true;
        uploadResult.innerHTML = '';

        // Simulate progress (since we can't track real progress with FormData)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 10;
            if (progress <= 90) {
                progressBar.style.width = progress + '%';
            }
        }, 200);

        // Upload using fetch
        const uploadUrl = typeof UPLOAD_URL !== 'undefined' ? UPLOAD_URL : 'home/upload';
        fetch(uploadUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressText.textContent = 'Selesai!';

            setTimeout(() => {
                uploadProgress.style.display = 'none';
                uploadBtn.disabled = false;

                if (data.success) {
                    showAlert('success', data.message);
                    if (data.files) {
                        let filesList = '<ul class="list-unstyled mt-2">';
                        data.files.forEach(file => {
                            filesList += '<li><i class="bi bi-check-circle text-success"></i> ' + escapeHtml(file) + '</li>';
                        });
                        filesList += '</ul>';
                        uploadResult.innerHTML = filesList;
                    }
                    // Clear files after successful upload
                    selectedFiles = [];
                    fileInput.value = '';
                    updateFileList();
                    
                    // Reload page after 2 seconds to show new files
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showAlert('danger', data.message || 'Gagal mengupload file');
                    if (data.errors) {
                        let errorsList = '<ul class="list-unstyled mt-2">';
                        data.errors.forEach(error => {
                            errorsList += '<li><i class="bi bi-x-circle text-danger"></i> ' + escapeHtml(error) + '</li>';
                        });
                        errorsList += '</ul>';
                        uploadResult.innerHTML = errorsList;
                    }
                }
            }, 1000);
        })
        .catch(error => {
            clearInterval(progressInterval);
            uploadProgress.style.display = 'none';
            uploadBtn.disabled = false;
            showAlert('danger', 'Error: ' + error.message);
        });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        uploadResult.insertBefore(alertDiv, uploadResult.firstChild);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});

