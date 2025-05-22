<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$typePrefix = '';

switch ($type) {
    case 'assignment':
        $typePrefix = 'Assignment - ';
        break;
    case 'project':
        $typePrefix = 'Project - ';
        break;
    case 'exercise':
        $typePrefix = 'Exercise - ';
        break;
    default:
        $typePrefix = '';
        break;
}

$presetCriteria = [
    'Completion',
    'Creativity',
    'Presentation',
    'Originality',
    'Technical',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <link rel="stylesheet" href="../cssfile/upload_lesson.css">
    <title>Upload Lesson</title>
</head>
<body>
    <div class="container">
        <h1>Upload New Lesson</h1>
        
        <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data" class="lesson-form">
            <div class="form-left">
                <div class="form-group">
                    <label for="category">Category <span style="color:red">*</span></label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Assignment">Assignment</option>
                        <option value="Project">Project</option>
                        <option value="Exercise">Exercise</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Title <span style="color:red">*</span></label>
                    <input type="text" id="title" name="title" required>
                    <input type="hidden" name="type_prefix" value="<?php echo htmlspecialchars($typePrefix); ?>">
                </div>

                <div class="form-group">
                    <label for="description">Lesson Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="thumbnail_image">Thumbnail Image (JPG/PNG):</label>
                    <input type="file" name="thumbnail_image" id="thumbnail_image" accept=".jpg, .jpeg, .png">
                </div>   

                <div class="form-group">
                    <label for="lesson_file">Lesson File (PDF/Word):</label>
                    <input type="file" name="lesson_file" id="lesson_file" accept=".pdf, .doc, .docx">
                </div>
            </div>

            <div class="form-right">
                <div class="form-group">
                    <label>Grading Criteria:</label>
                    <div class="form-group">
                        <label for="criteria_count">Number of Criteria:</label>
                        <select id="criteria_count" name="criteria_count" onchange="updateCriteriaFields()">
                            <?php for($i=1; $i<=10; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div id="criteriaFieldsContainer"></div>
                    <input type="hidden" id="scoring_criteria" name="scoring_criteria">
                    <div id="criteriaPreview"></div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="savebtn" class="submit-btn">Submit Lesson</button>
                <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        const presetCriteria = <?php echo json_encode($presetCriteria); ?>;
        
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
                    customInput.value = this.value;
                    document.getElementById(`criteria_name_${i}`).value = this.value;
                    updateCriteriaPreview();
                };
                
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Select preset or choose custom --';
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
                customInput.style.display = 'inline-block';
                customInput.required = true;
                customInput.oninput = function() {
                    document.getElementById(`criteria_name_${i}`).value = this.value;
                };
                
                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.id = `criteria_name_${i}`;
                nameInput.name = `criteria_name_${i}`;
                
                const pointsInput = document.createElement('input');
                pointsInput.type = 'number';
                pointsInput.className = 'criteria-points';
                pointsInput.id = `criteria_points_${i}`;
                pointsInput.name = `criteria_points_${i}`;
                pointsInput.min = '1';
                pointsInput.placeholder = 'Points';
                pointsInput.required = true;
                pointsInput.oninput = updateCriteriaPreview;
                
                row.appendChild(select);
                row.appendChild(customInput);
                row.appendChild(nameInput);
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
                previewHTML += `<li>${nameInput.value}: ${pointsInput.value} points</li>`;
            }
            
            previewHTML += "</ul>";
            
            if (isValid && criteria.length > 0) {
                document.getElementById('scoring_criteria').value = criteria.join('|');
                document.getElementById('criteriaPreview').innerHTML = previewHTML;
            } else {
                document.getElementById('scoring_criteria').value = '';
                document.getElementById('criteriaPreview').innerHTML = '';
            }
        }
        
        document.addEventListener('DOMContentLoaded', updateCriteriaFields);
    </script>
</body>
    <script src="../javascriptfile/upload_lesson.js"></script>
</html>