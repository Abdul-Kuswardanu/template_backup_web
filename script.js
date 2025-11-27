document.addEventListener('DOMContentLoaded', function () {
    var selectAllCheckbox = document.getElementById('select-all');
    var fileCheckboxes = document.querySelectorAll('.file-checkbox');
    var folderLinks = document.querySelectorAll('.folder-link');

    selectAllCheckbox.addEventListener('change', function () {
        fileCheckboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    folderLinks.forEach(function (folderLink) {
        folderLink.addEventListener('click', function (event) {
            event.preventDefault();
            var folderPath = this.getAttribute('data-folder-path');
            window.location.href = 'index.php?path=' + encodeURIComponent(folderPath);
        });
    });
});