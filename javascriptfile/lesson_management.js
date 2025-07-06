document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('tab')) {
        switchTab(urlParams.get('tab'));
    }
    
    document.querySelectorAll('.close-alert').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    const lessonEditForm = document.getElementById('lessonEditForm');
    if (lessonEditForm) {
        lessonEditForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            const thumbnailInput = document.getElementById('thumbnail');
            if (thumbnailInput.files.length > 0) {
                const allowedThumbnailTypes = ['image/jpeg', 'image/png'];
                const file = thumbnailInput.files[0];
                
                if (!allowedThumbnailTypes.includes(file.type)) {
                    document.getElementById('thumbnailError').textContent = 'Only JPG/JPEG/PNG images are allowed';
                    document.getElementById('thumbnailError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('thumbnailError').style.display = 'none';
                }
            }
            
            const lessonFileInput = document.getElementById('lesson_file');
            if (lessonFileInput.files.length > 0) {
                const allowedLessonTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const file = lessonFileInput.files[0];
                const fileExt = file.name.split('.').pop().toLowerCase();
                
                if (!allowedLessonTypes.includes(file.type) && !['pdf','doc','docx'].includes(fileExt)) {
                    document.getElementById('lessonFileError').textContent = 'Only PDF/DOC/DOCX files are allowed';
                    document.getElementById('lessonFileError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('lessonFileError').style.display = 'none';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            return confirm('Are you sure you want to save these changes?');
        });
    }
    
    const materialEditForm = document.getElementById('materialEditForm');
    if (materialEditForm) {
        const materialFileInput = document.getElementById('material_file');
        const materialFileInfo = document.querySelector('#editMaterialModal .file-info');
        const materialFileError = document.getElementById('materialFileError');
        
        if (materialFileInput) {
            materialFileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    const allowedTypes = ['pdf', 'docx', 'pptx'];
                    
                    materialFileInfo.textContent = fileName;
                    
                    if (!allowedTypes.includes(fileExt)) {
                        materialFileError.textContent = 'Only PDF, DOCX, and PPTX files are allowed!';
                        materialFileError.style.color = 'red';
                    } else {
                        materialFileError.textContent = '';
                    }
                } else {
                    materialFileInfo.textContent = 'Current: ' + document.querySelector('#editMaterialModal .file-info').dataset.currentFile;
                    materialFileError.textContent = '';
                }
            });
        }
        
        materialEditForm.addEventListener('submit', function(e) {
            if (materialFileInput.files.length > 0) {
                const file = materialFileInput.files[0];
                const fileName = file.name;
                const fileExt = fileName.split('.').pop().toLowerCase();
                const allowedTypes = ['pdf', 'docx', 'pptx'];
                
                if (!allowedTypes.includes(fileExt)) {
                    e.preventDefault();
                    materialFileError.textContent = 'Only PDF, DOCX, and PPTX files are allowed!';
                    materialFileError.style.color = 'red';
                    return;
                }
            }
            
            if (!confirm('Are you sure you want to save these changes?')) {
                e.preventDefault();
            }
        });
    }
    
    const updateCriteriaBtn = document.getElementById('updateCriteriaBtn');
    if (updateCriteriaBtn) {
        updateCriteriaBtn.addEventListener('click', updateCriteriaFields);
    }
    
    if (document.getElementById('criteria_count')) {
        updateCriteriaFields();
    }
});

function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.getElementById(tabName).classList.add('active');
    
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

function updateCriteriaFields() {
    const count = parseInt(document.getElementById('criteria_count').value);
    const container = document.getElementById('criteriaFieldsContainer');
    container.innerHTML = '';
    
    const presetCriteria = ['Completion', 'Creativity', 'Presentation', 'Originality', 'Technical'];
    const existingCriteria = JSON.parse(document.getElementById('existingCriteriaData').textContent);
    
    for (let i = 0; i < count; i++) {
        const existing = existingCriteria[i] || {};
        const isCustom = existing.name && !presetCriteria.includes(existing.name);
        
        const row = document.createElement('div');
        row.className = 'compact-criteria-row';
        
        const nameSelect = document.createElement('select');
        nameSelect.className = 'criteria-name';
        nameSelect.name = `criteria_name_${i}`;
        nameSelect.required = true;
        
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select criteria';
        nameSelect.appendChild(defaultOption);
        
        presetCriteria.forEach(criterion => {
            const option = document.createElement('option');
            option.value = criterion;
            option.textContent = criterion;
            option.selected = (existing.name === criterion);
            nameSelect.appendChild(option);
        });
        
        const customOption = document.createElement('option');
        customOption.value = 'custom';
        customOption.textContent = 'Custom...';
        customOption.selected = isCustom;
        nameSelect.appendChild(customOption);
        
        const customInput = document.createElement('input');
        customInput.type = 'text';
        customInput.className = 'criteria-custom-name';
        customInput.placeholder = 'Enter custom name';
        customInput.style.display = isCustom ? 'inline-block' : 'none';
        customInput.value = isCustom ? existing.name : '';
        
        const pointsInput = document.createElement('input');
        pointsInput.type = 'number';
        pointsInput.className = 'criteria-points';
        pointsInput.name = `criteria_points_${i}`;
        pointsInput.min = '1';
        pointsInput.max = '100';
        pointsInput.value = existing.points || '10';
        pointsInput.required = true;
        
        row.appendChild(nameSelect);
        row.appendChild(customInput);
        row.appendChild(document.createTextNode(' Points: '));
        row.appendChild(pointsInput);
        
        nameSelect.addEventListener('change', function() {
            customInput.style.display = this.value === 'custom' ? 'inline-block' : 'none';
            if (this.value !== 'custom') {
                customInput.value = this.value;
            }
            updateCriteriaPreview();
        });
        
        customInput.addEventListener('input', updateCriteriaPreview);
        pointsInput.addEventListener('input', updateCriteriaPreview);
        
        container.appendChild(row);
    }
    
    updateCriteriaPreview();
}

function updateCriteriaPreview() {
    const count = parseInt(document.getElementById('criteria_count').value);
    let criteria = [];
    let previewHTML = "<strong>Current Criteria:</strong><ul class='compact-list'>";
    let totalPoints = 0;
    
    for (let i = 0; i < count; i++) {
        const nameSelect = document.querySelector(`select[name="criteria_name_${i}"]`);
        const customInput = document.querySelector(`.criteria-custom-name`);
        const pointsInput = document.querySelector(`input[name="criteria_points_${i}"]`);
        
        if (!nameSelect || !pointsInput) continue;
        
        const name = nameSelect.value === 'custom' 
            ? (customInput ? customInput.value : '') 
            : nameSelect.value;
        const points = pointsInput.value;
        
        if (name && points) {
            criteria.push(`${name}:${points}`);
            previewHTML += `<li>${name}: ${points} points</li>`;
            totalPoints += parseInt(points) || 0;
        }
    }
    
    previewHTML += `</ul><p><strong>Total: ${totalPoints} points</strong></p>`;
    document.getElementById('scoring_criteria').value = criteria.join('|');
    document.getElementById('criteriaPreview').innerHTML = criteria.length > 0 
        ? previewHTML 
        : "<p>No criteria set</p>";
}