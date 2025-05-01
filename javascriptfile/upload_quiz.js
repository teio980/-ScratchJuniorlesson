function showPopup() {
    document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
    setTimeout(function() {
        location.reload();  
    }, 200);  
}

document.getElementById('quizForm').addEventListener('submit', function(event) {
    event.preventDefault();  

    var formData = new FormData(this);  

    fetch('quizupload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        showPopup();  
    })
    .catch(error => {
        console.error('Error:', error);  
    });
});
