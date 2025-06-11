if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// 16:9 Aspect Ratio Validation - Unified function for both forms
function validateImage(fileInput, fileNameDisplay, previewContainer, preview) {
    if (!fileInput.files || !fileInput.files[0]) {
        fileNameDisplay.textContent = 'No file chosen';
        previewContainer.style.display = 'none';
        return false;
    }

    const file = fileInput.files[0];
    let originalName = file.name;
    let displayName = originalName.replace(/\s+/g, '_');
    fileNameDisplay.textContent = displayName;

    // Check if it's an image
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
            showAlert(`Image must have a 16:9 aspect ratio! Current ratio: ${this.width}:${this.height}`, 'danger');
            resetFileInput(fileInput, fileNameDisplay, previewContainer);
            return false;
        }

        // Show preview if validation passes
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
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

// Helper function to reset file input
function resetFileInput(fileInput, fileNameDisplay, previewContainer) {
    fileInput.value = '';
    fileNameDisplay.textContent = 'No file chosen';
    previewContainer.style.display = 'none';
}

// Custom alert function to prevent duplicates
function showAlert(message, type) {
    // Check if an alert with the same message already exists
    const existingAlerts = document.querySelectorAll('.alert');
    for (let alert of existingAlerts) {
        if (alert.textContent.includes(message)) {
            return; // Don't show duplicate alert
        }
    }

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const closeBtn = document.createElement('button');
    closeBtn.className = 'close-alert';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        alertDiv.remove();
    };
    
    alertDiv.appendChild(closeBtn);
    document.querySelector('.dashboard-container').prepend(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Main image upload handler
document.getElementById('image')?.addEventListener('change', function(e) {
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    validateImage(this, fileNameDisplay, previewContainer, preview);
});

// Edit form image handler with validation
document.getElementById('editImage')?.addEventListener('change', function(e) {
    const fileNameDisplay = document.getElementById('editFileNameDisplay');
    const previewContainer = document.getElementById('editImagePreviewContainer');
    const preview = document.getElementById('editImagePreview');
    
    if (this.files && this.files[0]) {
        validateImage(this, fileNameDisplay, previewContainer, preview);
    } else {
        fileNameDisplay.textContent = 'No file chosen';
        if (previewContainer) previewContainer.style.display = 'none';
    }
});

// Dismissible alerts functionality
function setupAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Only add close button if not already present
        if (!alert.querySelector('.close-alert')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'close-alert';
            closeBtn.innerHTML = '&times;';
            closeBtn.onclick = function() {
                alert.remove();
            };
            alert.appendChild(closeBtn);
        }
        
        // Auto-close after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    setupAlerts();
    
    // Also setup alerts that might be added later
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

// Game management functions
function confirmDelete(gameId) {
    if (confirm('Are you sure you want to delete this mini game?')) {
        window.location.href = 'mini_game_management.php?delete=1&game_id=' + gameId;
    }
}

function showOriginalName(input) {
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    validateImage(input, fileNameDisplay, previewContainer, preview);
}

function showEditOriginalName(input) {
    const fileNameDisplay = document.getElementById('editFileNameDisplay');
    const previewContainer = document.getElementById('editImagePreviewContainer');
    const preview = document.getElementById('editImagePreview');
    validateImage(input, fileNameDisplay, previewContainer, preview);
}

function openEditModal(gameId, title) {
    document.getElementById('modalGameId').value = gameId;
    document.getElementById('editTitle').value = title;
    
    // Reset edit form file input and preview
    const editImageInput = document.getElementById('editImage');
    const editFileNameDisplay = document.getElementById('editFileNameDisplay');
    const editPreviewContainer = document.getElementById('editImagePreviewContainer');
    editImageInput.value = '';
    editFileNameDisplay.textContent = 'No file chosen';
    editPreviewContainer.style.display = 'none';
    
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