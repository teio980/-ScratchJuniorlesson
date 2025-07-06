function downloadAll() {
    try {
        const links = document.querySelectorAll('a.download-link');
        if (links.length === 0) {
            alert('No downloadable files found');
            return;
        }

        let delay = 0;
        links.forEach(link => {
            setTimeout(() => {
                try {
                    const a = document.createElement('a');
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
    fetch(`../phpfile/get_lesson_criteria.php?lesson_id=${lesson_id}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            const modal = document.getElementById('ratingModal');
            const form = modal.querySelector('form');
            
            form.querySelector('#criteriaContainer').innerHTML = '';
            
            if (!data?.grading_criteria) {
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
                           min="0" max="${maxScore}" 
                           value="${data.existing_scores?.[index] || 0}" 
                           onchange="calculateTotalScore(${maxScore}, this)">
                    <span class="max-score">/ ${maxScore}</span>
                `;
                form.querySelector('#criteriaContainer').appendChild(div);
            });
            
            form.querySelector('#criteriaContainer').insertAdjacentHTML('beforeend', `
                <div class="total-score">
                    <strong>Total Score: </strong>
                    <span id="displayTotal">${data.existing_score || 0}</span> / ${totalMaxScore}
                    <input type="hidden" name="total_score" id="total_score" value="${data.existing_score || 0}">
                </div>
            `);

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
            showNotification('Failed to load grading criteria', 'error');
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
    if (confirm('Are you sure you want to delete this submission?')) {
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
                document.querySelector(`tr[data-submit-id="${submit_id}"]`)?.remove();
                showNotification('Submission deleted', 'success');
            } else {
                throw new Error(data.error || 'Delete failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message, 'error');
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function updateSubmissionRow(data) {
    const row = document.querySelector(`tr[data-submit-id="${data.submit_id}"]`);
    if (!row) return;

    const scoreCell = row.querySelector('.score-cell, td:nth-child(9)');
    if (scoreCell) scoreCell.textContent = `${data.newAverage}%`;
    
    const statusCell = row.querySelector('.status-cell, td:nth-child(10)');
    if (statusCell) {
        statusCell.innerHTML = data.newAverage >= 40 ? '✅ Pass' : '❌ Fail';
    }
    
    row.style.backgroundColor = 'rgba(76, 175, 80, 0.3)';
    setTimeout(() => row.style.backgroundColor = '', 1000);
}

function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Saving...';

    const formData = new FormData(form);
    const optimisticData = {
        submit_id: formData.get('submit_id'),
        newAverage: formData.get('total_score')
    };
    updateSubmissionRow(optimisticData);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (!data.success) throw new Error(data.error || 'Update failed');
        
        updateSubmissionRow(data);
        closeRatingModal();
        showNotification('Score updated successfully', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const ratingForm = document.getElementById('ratingForm');
    if (ratingForm) {
        ratingForm.addEventListener('submit', handleFormSubmit);
    }

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteSubmission(this.dataset.submitId);
        });
    });

    const modal = document.getElementById('ratingModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('cancel-btn')) {
                closeRatingModal();
            }
        });
    }

    const style = document.createElement('style');
    style.textContent = `
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2ecc71;
            color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s;
        }
        .notification.error {
            background: #e74c3c;
        }
        .notification.fade-out {
            opacity: 0;
            transform: translateY(-20px);
        }
    `;
    document.head.appendChild(style);
});