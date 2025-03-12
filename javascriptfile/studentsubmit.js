
document.getElementById('uploadForm').addEventListener('submit', function(event) {
    var fileInput = document.getElementById('file');
    var filePath = fileInput.value;
    if (!filePath.endsWith('.sjr')) {
        alert('Please upload a valid .sjr file.');
        event.preventDefault();
    }
});
