if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

function confirmDelete(gameId) {
    if (confirm('Are you sure you want to delete this mini game?')) {
        window.location.href = 'mini_game_management.php?delete=1&game_id=' + gameId;
    }
}

function openEditModal(gameId, title, difficulty) {
    document.getElementById('modalGameId').value = gameId;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDifficulty').value = difficulty;
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