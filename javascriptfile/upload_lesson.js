document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("categoryModal");
    const btn = document.getElementById("chooseCategoryBtn");
    const span = document.getElementById("closeModal");

    if (btn && span && modal) {
        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    const categorySelect = document.getElementById('category');
    const titleInput = document.getElementById('title');
    const typePrefixInput = document.querySelector('input[name="type_prefix"]');

    if (categorySelect && titleInput && typePrefixInput) {
        const prefixes = {
            'Assignment': 'Assignment - ',
            'Project': 'Project - ',
            'Exercise': 'Exercise - '
        };

        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            const currentTitle = titleInput.value;
            const newPrefix = prefixes[selectedCategory] || '';
            
            typePrefixInput.value = newPrefix;
            
            if (!currentTitle || Object.values(prefixes).some(prefix => currentTitle.startsWith(prefix))) {
                titleInput.value = newPrefix;
            }
        });

        titleInput.addEventListener('input', function() {
            const selectedCategory = categorySelect.value;
            const currentTitle = this.value;
            const expectedPrefix = prefixes[selectedCategory] || '';
            
            if (selectedCategory && expectedPrefix && !currentTitle.startsWith(expectedPrefix)) {
                let cleanTitle = currentTitle;
                for (const prefix of Object.values(prefixes)) {
                    if (currentTitle.startsWith(prefix)) {
                        cleanTitle = currentTitle.slice(prefix.length);
                        break;
                    }
                }
                this.value = expectedPrefix + cleanTitle;
            }
        });
    }

    document.getElementById('thumbnail_image')?.addEventListener('change', function(e) {
        const file = this.files[0];
        if (!file) return;
        
        const validImageTypes = ['image/jpeg', 'image/png'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!validImageTypes.includes(file.type) || !['jpg', 'jpeg', 'png'].includes(fileExtension)) {
            alert('Only JPG/JPEG/PNG format images are allowed to be uploaded!');
            this.value = '';
            const label = this.nextElementSibling;
            if (label) label.textContent = 'No file chosen';
            return;
        }
        
        const label = this.nextElementSibling;
        if (label) label.textContent = file.name;
    });

    document.getElementById('lesson_file')?.addEventListener('change', function(e) {
        const file = this.files[0];
        if (!file) return;
        
        const validFileTypes = ['application/pdf', 'application/msword', 
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!validFileTypes.includes(file.type) || !['pdf', 'doc', 'docx'].includes(fileExtension)) {
            alert('Only PDF/DOC/DOCX files are allowed to be uploaded!');
            this.value = ''; 
            const label = this.nextElementSibling;
            if (label) label.textContent = 'No file chosen';
            return;
        }
        
        const label = this.nextElementSibling;
        if (label) label.textContent = file.name;
    });

    const thumbnailInput = document.getElementById('thumbnail_image');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Only JPG/PNG images are allowed for thumbnails!');
                    this.value = '';
                }
            }
        });
    }

    const lessonFileInput = document.getElementById('lesson_file');
    if (lessonFileInput) {
        lessonFileInput.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const validTypes = ['application/pdf', 'application/msword', 
                                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    alert('Only PDF/DOC/DOCX files are allowed for lessons!');
                    this.value = '';
                }
            }
        });
    }

    const presetCriteria = JSON.parse(document.getElementById('presetCriteria').textContent);
    
    function updateCriteriaFields() {
        const count = parseInt(document.getElementById('criteria_count').value);
        const container = document.getElementById('criteriaFieldsContainer');
        container.innerHTML = '';
        
        for (let i = 0; i < count; i++) {
            const row = document.createElement('div');
            row.className = 'criteria-row';
            
            const select = document.createElement('select');
            select.className = 'criteria-select';
            select.id = `criteria_select_${i}`;
            select.onchange = function() {
                const customInput = document.getElementById(`criteria_custom_${i}`);
                if (this.value === 'custom') {
                    customInput.style.display = 'block';
                    customInput.value = '';
                    customInput.focus();
                } else {
                    customInput.style.display = 'none';
                    customInput.value = this.value;
                    document.getElementById(`criteria_name_${i}`).value = this.value;
                }
                updateCriteriaPreview();
            };
            
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select preset or choose custom';
            select.appendChild(defaultOption);
            
            presetCriteria.forEach(criterion => {
                const option = document.createElement('option');
                option.value = criterion;
                option.textContent = criterion;
                select.appendChild(option);
            });
            
            const customOption = document.createElement('option');
            customOption.value = 'custom';
            customOption.textContent = 'Custom...';
            select.appendChild(customOption);
            
            const customInput = document.createElement('input');
            customInput.type = 'text';
            customInput.className = 'criteria-name-input';
            customInput.id = `criteria_custom_${i}`;
            customInput.placeholder = 'Enter custom criteria name';
            customInput.style.display = 'none';
            customInput.required = true;
            customInput.oninput = function() {
                document.getElementById(`criteria_name_${i}`).value = this.value;
                updateCriteriaPreview();
            };
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.id = `criteria_name_${i}`;
            nameInput.name = `criteria_name_${i}`;
            
            const pointsLabel = document.createElement('label');
            pointsLabel.htmlFor = `criteria_points_${i}`;
            pointsLabel.textContent = 'Points:';
            
            const pointsInput = document.createElement('input');
            pointsInput.type = 'number';
            pointsInput.className = 'criteria-points';
            pointsInput.id = `criteria_points_${i}`;
            pointsInput.name = `criteria_points_${i}`;
            pointsInput.min = '1';
            pointsInput.max = '100';
            pointsInput.placeholder = '10';
            pointsInput.required = true;
            pointsInput.oninput = updateCriteriaPreview;
            
            row.appendChild(select);
            row.appendChild(customInput);
            row.appendChild(nameInput);
            row.appendChild(pointsLabel);
            row.appendChild(pointsInput);
            container.appendChild(row);
        }
        
        updateCriteriaPreview();
    }
    
    function updateCriteriaPreview() {
        const count = parseInt(document.getElementById('criteria_count').value);
        let criteria = [];
        let previewHTML = "<h4>Selected Criteria:</h4><ul>";
        let isValid = true;
        let totalpoint = 0;
        
        for (let i = 0; i < count; i++) {
            const nameInput = document.getElementById(`criteria_name_${i}`);
            const pointsInput = document.getElementById(`criteria_points_${i}`);
            
            if (!nameInput || !nameInput.value || !pointsInput || !pointsInput.value) {
                isValid = false;
                continue;
            }
            
            const points = parseInt(pointsInput.value) || 0;
            totalpoint += points;
            criteria.push(`${nameInput.value}:${pointsInput.value}`);
            previewHTML += `<li><strong>${nameInput.value}</strong>: ${pointsInput.value} points</li>`;
        }
        
        previewHTML += `</ul><p><strong>Total: ${totalpoint} points</strong></p>`;
        
        if (isValid && criteria.length > 0) {
            document.getElementById('scoring_criteria').value = criteria.join('|');
            document.getElementById('criteriaPreview').innerHTML = previewHTML;
        } else {
            document.getElementById('scoring_criteria').value = '';
            document.getElementById('criteriaPreview').innerHTML = '<p>No criteria selected yet</p>';
        }
    }
    
    if (document.getElementById('criteria_count')) {
        updateCriteriaFields();
        document.getElementById('criteria_count').addEventListener('change', updateCriteriaFields);
    }
});