document.addEventListener('DOMContentLoaded', function () {
    // Modal functionality (existing code)
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

    // Auto-prefix title based on category selection
    const categorySelect = document.getElementById('category');
    const titleInput = document.getElementById('title');
    const typePrefixInput = document.querySelector('input[name="type_prefix"]');

    if (categorySelect && titleInput && typePrefixInput) {
        // Map category values to prefixes
        const prefixes = {
            'Assignment': 'Assignment - ',
            'Project': 'Project - ',
            'Exercise': 'Exercise - '
        };

        // Update title when category changes
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            const currentTitle = titleInput.value;
            const newPrefix = prefixes[selectedCategory] || '';
            
            // Update the hidden type_prefix field
            typePrefixInput.value = newPrefix;
            
            // Only modify the title if:
            // 1. It's empty, or
            // 2. It starts with one of our prefixes (so we can replace it)
            if (!currentTitle || Object.values(prefixes).some(prefix => currentTitle.startsWith(prefix))) {
                titleInput.value = newPrefix;
            }
        });

        // Ensure prefix is maintained when user types (optional)
        titleInput.addEventListener('input', function() {
            const selectedCategory = categorySelect.value;
            const currentTitle = this.value;
            const expectedPrefix = prefixes[selectedCategory] || '';
            
            // If category is selected but title doesn't start with the prefix
            if (selectedCategory && expectedPrefix && !currentTitle.startsWith(expectedPrefix)) {
                // Remove any existing prefixes
                let cleanTitle = currentTitle;
                for (const prefix of Object.values(prefixes)) {
                    if (currentTitle.startsWith(prefix)) {
                        cleanTitle = currentTitle.slice(prefix.length);
                        break;
                    }
                }
                // Add the correct prefix
                this.value = expectedPrefix + cleanTitle;
            }
        });
    }

    // Grading criteria functionality (moved from PHP file)
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
        
        for (let i = 0; i < count; i++) {
            const nameInput = document.getElementById(`criteria_name_${i}`);
            const pointsInput = document.getElementById(`criteria_points_${i}`);
            
            if (!nameInput || !nameInput.value || !pointsInput || !pointsInput.value) {
                isValid = false;
                continue;
            }
            
            criteria.push(`${nameInput.value}:${pointsInput.value}`);
            previewHTML += `<li><strong>${nameInput.value}</strong>: ${pointsInput.value} points</li>`;
        }
        
        previewHTML += "</ul>";
        
        if (isValid && criteria.length > 0) {
            document.getElementById('scoring_criteria').value = criteria.join('|');
            document.getElementById('criteriaPreview').innerHTML = previewHTML;
        } else {
            document.getElementById('scoring_criteria').value = '';
            document.getElementById('criteriaPreview').innerHTML = '<p>No criteria selected yet</p>';
        }
    }
    
    // Initialize criteria fields
    if (document.getElementById('criteria_count')) {
        updateCriteriaFields();
        document.getElementById('criteria_count').addEventListener('change', updateCriteriaFields);
    }
});