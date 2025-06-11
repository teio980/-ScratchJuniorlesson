function openTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.getElementById(tabName).classList.add('active');
    
    event.currentTarget.classList.add('active');
}

function showPopup(message) {
    document.getElementById('popup-message').textContent = message || 'Operation completed successfully!';
    document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
    setTimeout(function() {
        location.reload();  
    }, 200);  
}

function deleteQuestion(id) {
    if (confirm('Are you sure you want to delete this question?')) {
        fetch('quizupload.php?delete_id=' + id)
            .then(response => {
                if (response.ok) {
                    showPopup('Question deleted successfully!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showPopup('Error deleting question');
            });
    }
}

function editQuestion(id) {
    fetch('get_question.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_question').value = data.question;
            document.getElementById('edit_level').value = data.difficult;
            document.getElementById('edit_option1').value = data.option1;
            document.getElementById('edit_option2').value = data.option2;
            document.getElementById('edit_option3').value = data.option3;
            document.getElementById('edit_option4').value = data.option4;
            document.getElementById('edit_answer').value = data.answer;
            
            document.getElementById('editForm').style.display = 'block';
            
            document.getElementById('editForm').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error:', error);
            showPopup('Error loading question data');
        });
}

function cancelEdit() {
    document.getElementById('editForm').style.display = 'none';
}

document.getElementById('quizForm')?.addEventListener('submit', function(event) {
    event.preventDefault();  
    var formData = new FormData(this);  

    fetch('quizupload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        showPopup('Question added successfully!');  
    })
    .catch(error => {
        console.error('Error:', error);  
        showPopup('Error adding question');
    });
});

document.getElementById('editQuizForm')?.addEventListener('submit', function(event) {
    event.preventDefault();  
    var formData = new FormData(this);  

    fetch('quizupload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        showPopup('Question updated successfully!');  
    })
    .catch(error => {
        console.error('Error:', error);  
        showPopup('Error updating question');
    });
});