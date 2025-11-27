<?php
$custom_css = '
.folder-card {
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 1.5rem;
}
.folder-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
.file-item {
    padding: 0.5rem;
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s;
}
.file-item:hover {
    background-color: #f8f9fa;
}
.file-item:last-child {
    border-bottom: none;
}
.folder-header {
    cursor: pointer;
    user-select: none;
}
.folder-content {
    display: none;
}
.folder-content.show {
    display: block;
}
.file-size {
    color: #6c757d;
    font-size: 0.875rem;
}
.file-date {
    color: #6c757d;
    font-size: 0.875rem;
}
.upload-area {
    border: 3px dashed #dee2e6;
    border-radius: 15px;
    padding: 3rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
    cursor: pointer;
}
.upload-area:hover {
    border-color: #667eea;
    background: #f0f0f0;
}
.upload-area.dragover {
    border-color: #667eea;
    background: #e7f3ff;
    transform: scale(1.02);
}
.file-list-item {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.file-list-item .file-info {
    flex: 1;
}
.file-list-item .file-actions {
    margin-left: 1rem;
}
.progress {
    height: 8px;
    border-radius: 10px;
}
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #667eea;
    border-bottom-color: #667eea;
    font-weight: 600;
}
';

$additional_js = array(
    base_url('assets/js/home.js'),
    base_url('assets/js/upload.js')
);

$custom_js = "const UPLOAD_URL = '" . site_url("home/upload") . "';";

$this->load->view('inc/header', array(
    'title' => $title,
    'user_id' => $user_id,
    'custom_css' => $custom_css
));
?>

<div class="container mt-4">
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-folder"></i> Data Backup</h2>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="download-tab" data-bs-toggle="tab" data-bs-target="#download" type="button" role="tab">
                <i class="bi bi-download"></i> Download File
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                <i class="bi bi-upload"></i> Upload File
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="mainTabContent">
        <!-- Download Tab -->
        <div class="tab-pane fade show active" id="download" role="tabpanel">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-outline-primary" id="toggleAllFolders">
                    <i class="bi bi-arrows-expand"></i> Tampilkan Semua
                </button>
            </div>

            <form action="<?php echo site_url('home/download'); ?>" method="POST" id="downloadForm">
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select_all">
                        <label class="form-check-label fw-bold" for="select_all">
                            Pilih Semua File
                        </label>
                    </div>
                </div>

                <div id="foldersContainer">
                    <?php if (empty($folders)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Tidak ada folder yang tersedia untuk didownload.
                        </div>
                    <?php else: ?>
                        <?php foreach ($folders as $folder): ?>
                            <div class="card folder-card shadow-sm">
                                <div class="card-header bg-light folder-header" onclick="toggleFolder('<?php echo $folder['class']; ?>')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="bi bi-folder-fill text-warning"></i> 
                                            <?php echo htmlspecialchars($folder['name']); ?>
                                        </h5>
                                        <span class="badge bg-secondary" id="badge_<?php echo $folder['class']; ?>">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body folder-content" id="<?php echo $folder['class']; ?>">
                                    <?php
                                    $contents = scandir($folder['path']);
                                    $contents = array_diff($contents, array('..', '.'));
                                    $fileCount = 0;
                                    foreach ($contents as $content):
                                        $filePath = $folder['path'] . '/' . $content;
                                        if (is_file($filePath)):
                                            $fileCount++;
                                            $fileSize = filesize($filePath);
                                            $fileModificationTime = date("F d Y H:i:s", filemtime($filePath));
                                    ?>
                                        <div class="file-item">
                                            <div class="form-check">
                                                <input class="form-check-input file-checkbox" type="checkbox" 
                                                       name="selected_files[]" 
                                                       value="<?php echo htmlspecialchars($filePath); ?>" 
                                                       id="file_<?php echo $folder['class']; ?>_<?php echo $fileCount; ?>">
                                                <label class="form-check-label w-100" for="file_<?php echo $folder['class']; ?>_<?php echo $fileCount; ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="bi bi-file-earmark"></i>
                                                            <a href="<?php echo base_url($filePath); ?>" target="_blank" class="text-decoration-none">
                                                                <?php echo htmlspecialchars($content); ?>
                                                            </a>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="file-size"><?php echo formatBytes($fileSize); ?></div>
                                                            <div class="file-date"><?php echo $fileModificationTime; ?></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php
                                        endif;
                                    endforeach;
                                    if ($fileCount == 0):
                                    ?>
                                        <div class="text-center text-muted py-3">
                                            <i class="bi bi-folder-x"></i> Folder kosong
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mt-4 d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" id="downloadBtn" disabled>
                        <i class="bi bi-download"></i> Download File Terpilih
                    </button>
                </div>
            </form>
        </div>

        <!-- Upload Tab -->
        <div class="tab-pane fade" id="upload" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4"><i class="bi bi-cloud-upload"></i> Upload File</h5>
                    
                    <div class="mb-3">
                        <label for="target_folder" class="form-label">Pilih Folder Tujuan:</label>
                        <select class="form-select" id="target_folder" name="target_folder">
                            <option value="upload_1">upload_1</option>
                            <option value="upload_2">upload_2</option>
                        </select>
                    </div>

                    <div class="upload-area" id="uploadArea">
                        <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #667eea;"></i>
                        <h5 class="mt-3">Drag & Drop file di sini</h5>
                        <p class="text-muted">atau klik untuk memilih file</p>
                        <p class="text-muted small">Maksimal 50MB per file. Format: JPG, PNG, PDF, DOC, XLS, PPT, ZIP, dll</p>
                        <input type="file" id="fileInput" multiple style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                    </div>

                    <div id="fileList" class="mt-4" style="display: none;">
                        <h6 class="mb-3">File yang akan diupload:</h6>
                        <div id="fileListItems"></div>
                        <div class="mt-3 d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" id="uploadBtn">
                                <i class="bi bi-upload"></i> Upload File
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clearFilesBtn">
                                <i class="bi bi-x-circle"></i> Hapus Semua
                            </button>
                        </div>
                    </div>

                    <div id="uploadProgress" class="mt-4" style="display: none;">
                        <div class="progress mb-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        <p class="text-center mb-0" id="progressText">Mengupload...</p>
                    </div>

                    <div id="uploadResult" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

$this->load->view('inc/footer', array(
    'additional_js' => $additional_js,
    'custom_js' => $custom_js
));
?>
