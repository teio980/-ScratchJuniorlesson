document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('material_file');
    const fileNameSpan = document.querySelector('.file-upload .file-name');
    const fileLabel = document.querySelector('.file-upload-label');

    if (fileLabel && fileInput) {
        // 点击 label 触发隐藏的 file input
        fileLabel.addEventListener('click', function() {
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                // 显示第一个选中文件名
                fileNameSpan.textContent = fileInput.files[0].name;
            } else {
                fileNameSpan.textContent = 'No file chosen';
            }
        });
    }
});