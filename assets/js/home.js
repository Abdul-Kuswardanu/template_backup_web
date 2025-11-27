document.addEventListener('DOMContentLoaded', function() {
    // Toggle folder visibility
    window.toggleFolder = function(folderClass) {
        const folderContent = document.getElementById(folderClass);
        const badge = document.getElementById('badge_' + folderClass);
        const icon = badge.querySelector('i');
        
        if (folderContent.classList.contains('show')) {
            folderContent.classList.remove('show');
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        } else {
            folderContent.classList.add('show');
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        }
    };

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select_all');
    const fileCheckboxes = document.querySelectorAll('.file-checkbox');
    const downloadBtn = document.getElementById('downloadBtn');

    selectAllCheckbox.addEventListener('change', function() {
        fileCheckboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateDownloadButton();
    });

    // Individual checkbox change
    fileCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateDownloadButton();
        });
    });

    // Update select all checkbox state
    function updateSelectAllState() {
        const checkedCount = document.querySelectorAll('.file-checkbox:checked').length;
        selectAllCheckbox.checked = checkedCount === fileCheckboxes.length && fileCheckboxes.length > 0;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < fileCheckboxes.length;
    }

    // Update download button state
    function updateDownloadButton() {
        const checkedCount = document.querySelectorAll('.file-checkbox:checked').length;
        downloadBtn.disabled = checkedCount === 0;
        if (checkedCount > 0) {
            downloadBtn.innerHTML = `<i class="bi bi-download"></i> Download (${checkedCount} file)`;
        } else {
            downloadBtn.innerHTML = `<i class="bi bi-download"></i> Download File Terpilih`;
        }
    }

    // Toggle all folders
    let allFoldersExpanded = false;
    const toggleAllBtn = document.getElementById('toggleAllFolders');
    
    toggleAllBtn.addEventListener('click', function() {
        const folderContents = document.querySelectorAll('.folder-content');
        const badges = document.querySelectorAll('[id^="badge_"]');
        
        if (allFoldersExpanded) {
            // Collapse all
            folderContents.forEach(function(content) {
                content.classList.remove('show');
            });
            badges.forEach(function(badge) {
                const icon = badge.querySelector('i');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            });
            toggleAllBtn.innerHTML = '<i class="bi bi-arrows-expand"></i> Tampilkan Semua';
            allFoldersExpanded = false;
        } else {
            // Expand all
            folderContents.forEach(function(content) {
                content.classList.add('show');
            });
            badges.forEach(function(badge) {
                const icon = badge.querySelector('i');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            });
            toggleAllBtn.innerHTML = '<i class="bi bi-arrows-collapse"></i> Sembunyikan Semua';
            allFoldersExpanded = true;
        }
    });

    // Form submission with loading state
    const downloadForm = document.getElementById('downloadForm');
    downloadForm.addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.file-checkbox:checked').length;
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Pilih minimal satu file untuk didownload');
            return false;
        }
        
        downloadBtn.disabled = true;
        downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

