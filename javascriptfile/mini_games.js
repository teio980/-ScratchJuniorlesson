if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

document.addEventListener('DOMContentLoaded', function() {
    setupAlerts();
    setupFormValidation();
    setupModal();
    
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                setupAlerts();
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

function setupAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (!alert.querySelector('.close-alert')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'close-alert';
            closeBtn.innerHTML = '&times;';
            closeBtn.onclick = function() {
                alert.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            };
            alert.appendChild(closeBtn);
        }
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    });
}

function setupFormValidation() {
    const mainImageInput = document.getElementById('image');
    const editImageInput = document.getElementById('editImage');
    
    if (mainImageInput) {
        mainImageInput.addEventListener('change', function() {
            validateImage(this, document.getElementById('fileNameDisplay'), 
                         document.getElementById('imagePreviewContainer'), 
                         document.getElementById('imagePreview'));
        });
    }
    
    if (editImageInput) {
        editImageInput.addEventListener('change', function() {
            validateImage(this, document.getElementById('editFileNameDisplay'), 
                         document.getElementById('editImagePreviewContainer'), 
                         document.getElementById('editImagePreview'));
        });
    }
}

function setupModal() {
    const modal = document.getElementById('editModal');
    if (!modal) return;
    
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    };
}

function validateImage(fileInput, fileNameDisplay, previewContainer, preview) {
    if (!fileInput.files || !fileInput.files[0]) {
        if (fileNameDisplay) fileNameDisplay.textContent = 'No file chosen';
        if (previewContainer) previewContainer.style.display = 'none';
        return false;
    }

    const file = fileInput.files[0];
    const originalName = file.name;
    const displayName = originalName.replace(/\s+/g, '_');
    if (fileNameDisplay) fileNameDisplay.textContent = displayName;

    if (!file.type.startsWith('image/')) {
        showAlert('Please select an image file!', 'danger');
        resetFileInput(fileInput, fileNameDisplay, previewContainer);
        return false;
    }

    const img = new Image();
    img.onload = function() {
        const aspectRatio = this.width / this.height;
        const expectedRatio = 16/9;
        const tolerance = 0.05;

        if (Math.abs(aspectRatio - expectedRatio) > tolerance) {
            showAlert(`Image must have a 16:9 aspect ratio (Current: ${this.width}:${this.height})`, 'danger');
            resetFileInput(fileInput, fileNameDisplay, previewContainer);
            return false;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) preview.src = e.target.result;
            if (previewContainer) previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
        return true;
    };

    img.onerror = function() {
        showAlert('Invalid image file!', 'danger');
        resetFileInput(fileInput, fileNameDisplay, previewContainer);
        return false;
    };

    img.src = URL.createObjectURL(file);
}

function resetFileInput(fileInput, fileNameDisplay, previewContainer) {
    fileInput.value = '';
    if (fileNameDisplay) fileNameDisplay.textContent = 'No file chosen';
    if (previewContainer) previewContainer.style.display = 'none';
}

function showAlert(message, type) {
    const existingAlerts = document.querySelectorAll('.alert');
    for (let alert of existingAlerts) {
        if (alert.textContent.includes(message)) {
            return;
        }
    }

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'close-alert';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        alertDiv.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    };
    
    alertDiv.appendChild(closeBtn);
    document.querySelector('.dashboard-container').prepend(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        }
    }, 5000);
}

function confirmDelete(gameId) {
    if (confirm('Are you sure you want to delete this mini game? This action cannot be undone.')) {
        window.location.href = 'mini_game_management.php?delete=1&game_id=' + gameId;
    }
}

function showOriginalName(input) {
    validateImage(input, document.getElementById('fileNameDisplay'), 
                 document.getElementById('imagePreviewContainer'), 
                 document.getElementById('imagePreview'));
}

function showEditOriginalName(input) {
    validateImage(input, document.getElementById('editFileNameDisplay'), 
                 document.getElementById('editImagePreviewContainer'), 
                 document.getElementById('editImagePreview'));
}

function openEditModal(gameId, title) {
    document.getElementById('modalGameId').value = gameId;
    document.getElementById('editTitle').value = title;
    
    const editImageInput = document.getElementById('editImage');
    const editFileNameDisplay = document.getElementById('editFileNameDisplay');
    const editPreviewContainer = document.getElementById('editImagePreviewContainer');
    if (editImageInput) editImageInput.value = '';
    if (editFileNameDisplay) editFileNameDisplay.textContent = 'No file chosen';
    if (editPreviewContainer) editPreviewContainer.style.display = 'none';
    
    document.getElementById('editModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
`;
document.head.appendChild(style);