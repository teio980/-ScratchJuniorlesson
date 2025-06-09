if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

document.getElementById('image').addEventListener('change', function(e) {
    const fileInput = e.target;
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    if (fileInput.files.length > 0) {
        fileNameDisplay.textContent = 'Selected file: ' + fileInput.files[0].name;
        
        const img = new Image();
        img.onload = function() {
            const aspectRatio = this.width / this.height;
            const targetRatio = 16/9;
            const tolerance = 0.1; 
            
            if (Math.abs(aspectRatio - targetRatio) > tolerance) {
                alert('Image must have a 16:9 aspect ratio. Please select another image.');
                fileInput.value = ''; 
                fileNameDisplay.textContent = '';
                imagePreviewContainer.style.display = 'none';
            } else {
                imagePreview.src = URL.createObjectURL(fileInput.files[0]);
                imagePreviewContainer.style.display = 'block';
            }
        };
        img.src = URL.createObjectURL(fileInput.files[0]);
    } else {
        fileNameDisplay.textContent = '';
        imagePreviewContainer.style.display = 'none';
    }
});

document.getElementById('editImage').addEventListener('change', function(e) {
    const fileInput = e.target;
    const fileNameDisplay = document.createElement('div');
    fileNameDisplay.className = 'file-name-display';
    
    if (fileInput.files.length > 0) {
        const img = new Image();
        img.onload = function() {
            const aspectRatio = this.width / this.height;
            const targetRatio = 16/9;
            const tolerance = 0.1;
            
            if (Math.abs(aspectRatio - targetRatio) > tolerance) {
                alert('Image must have a 16:9 aspect ratio. Please select another image.');
                fileInput.value = '';
            } else {
                fileNameDisplay.textContent = 'Selected file: ' + fileInput.files[0].name;
                fileInput.parentNode.insertBefore(fileNameDisplay, fileInput.nextSibling);
            }
        };
        img.src = URL.createObjectURL(fileInput.files[0]);
    }
});

function confirmDelete(gameId) {
    if (confirm('Are you sure you want to delete this mini game?')) {
        window.location.href = 'mini_game_management.php?delete=1&game_id=' + gameId;
    }
}

function openEditModal(gameId, title) {
    document.getElementById('modalGameId').value = gameId;
    document.getElementById('editTitle').value = title;
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
};