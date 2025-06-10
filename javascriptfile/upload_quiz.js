// 选项卡切换功能
function openTab(tabName) {
    // 隐藏所有选项卡内容
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // 取消所有选项卡的活动状态
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // 显示选中的选项卡内容
    document.getElementById(tabName).classList.add('active');
    
    // 设置选中选项卡的活动状态
    event.currentTarget.classList.add('active');
}

// 显示弹窗(可自定义消息)
function showPopup(message) {
    document.getElementById('popup-message').textContent = message || 'Operation completed successfully!';
    document.getElementById('popup').style.display = 'flex';
}

// 关闭弹窗
function closePopup() {
    document.getElementById('popup').style.display = 'none';
    setTimeout(function() {
        location.reload();  
    }, 200);  
}

// 删除题目
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

// 编辑题目
function editQuestion(id) {
    // 使用AJAX获取题目数据
    fetch('get_question.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            // 填充编辑表单
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_question').value = data.question;
            document.getElementById('edit_level').value = data.difficult;
            document.getElementById('edit_option1').value = data.option1;
            document.getElementById('edit_option2').value = data.option2;
            document.getElementById('edit_option3').value = data.option3;
            document.getElementById('edit_option4').value = data.option4;
            document.getElementById('edit_answer').value = data.answer;
            
            // 显示编辑表单
            document.getElementById('editForm').style.display = 'block';
            
            // 滚动到编辑表单
            document.getElementById('editForm').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error:', error);
            showPopup('Error loading question data');
        });
}

// 取消编辑
function cancelEdit() {
    document.getElementById('editForm').style.display = 'none';
}

// 表单提交处理
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

// 编辑表单提交处理
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