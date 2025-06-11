function downloadAll() {
    try {
        var links = document.querySelectorAll('a.download-link');
        if (links.length === 0) {
            alert('No downloadable files found');
            return;
        }

        var delay = 0;
        links.forEach(function(link) {
            setTimeout(function() {
                try {
                    var a = document.createElement('a');
                    a.href = link.href;
                    a.download = link.getAttribute('data-filename') || link.href.split('/').pop();
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } catch (e) {
                    console.error('Error downloading file:', e);
                }
            }, delay);
            delay += 500; 
        });
    } catch (e) {
        console.error('Error in downloadAll:', e);
        alert('An error occurred while trying to download files');
    }
}

function openRatingModal(submit_id, student_id, lesson_id) {
    fetch('../phpfile/get_lesson_criteria.php?lesson_id=' + lesson_id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const modal = document.getElementById('ratingModal');
            const form = modal.querySelector('form');
            
            form.querySelector('#criteriaContainer').innerHTML = '';
            
            if (!data || !data.grading_criteria) {
                throw new Error('Invalid grading criteria data');
            }
            
            let totalMaxScore = 0;
            const criteria = data.grading_criteria.split('|');
            
            criteria.forEach((item, index) => {
                const [name, maxScore] = item.split(':');
                totalMaxScore += parseInt(maxScore);
                
                const div = document.createElement('div');
                div.className = 'criteria-item';
                div.innerHTML = `
                    <label for="criteria_${index}">${name} (0-${maxScore}):</label>
                    <input type="number" name="criteria[]" id="criteria_${index}" 
                           min="0" max="${maxScore}" value="${data.existing_scores ? data.existing_scores[index] || 0 : 0}" 
                           onchange="calculateTotalScore(${maxScore}, this)">
                    <span class="max-score">/ ${maxScore}</span>
                `;
                form.querySelector('#criteriaContainer').appendChild(div);
            });
            
            form.querySelector('#criteriaContainer').innerHTML += `
                <div class="total-score">
                    <strong>Total Score: </strong>
                    <span id="displayTotal">${data.existing_score || 0}</span> / ${totalMaxScore}
                    <input type="hidden" name="total_score" id="total_score" value="${data.existing_score || 0}">
                </div>
            `;

            document.getElementById('feedback').value = data.feedback || '';
            
            if (data.existing_score !== undefined && data.existing_score !== null) {
                document.getElementById('finalScoreDisplay').textContent = data.existing_score;
                document.getElementById('finalScoreContainer').style.display = 'block';
            }

            document.getElementById('submit_id').value = submit_id;
            document.getElementById('student_id').value = student_id;
            document.getElementById('lesson_id').value = lesson_id;
            
            modal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load grading criteria. Please try again.');
        }); 
}

function calculateTotalScore(maxScore, inputElement) {
    const inputs = document.querySelectorAll('input[name="criteria[]"]');
    let total = 0;
    inputs.forEach(input => {
        total += parseInt(input.value) || 0;
    });
    
    const totalMaxElement = document.querySelector('.total-score');
    if (totalMaxElement) {
        const totalMaxScore = parseInt(totalMaxElement.textContent.split('/')[1].trim());
        const percentageScore = Math.round((total / totalMaxScore) * 100);
        const finalScore = Math.min(percentageScore, 100);
        
        document.getElementById('displayTotal').textContent = total;
        document.getElementById('total_score').value = finalScore;
        
        document.getElementById('finalScoreDisplay').textContent = finalScore;
        document.getElementById('finalScoreContainer').style.display = 'block';
    }
}

function closeRatingModal() {
    document.getElementById('ratingModal').style.display = 'none';
}

function deleteSubmission(submit_id) {
    if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
        fetch('../phpfile/delete_submission.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `submit_id=${encodeURIComponent(submit_id)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Submission deleted successfully.');
                location.reload();
            } else {
                alert('Failed to delete submission: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the submission.');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const submit_id = this.dataset.submitId;
            deleteSubmission(submit_id);
        });
    });
});