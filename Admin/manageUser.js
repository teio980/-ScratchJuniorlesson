function showEditForm() {
    const checkboxes = document.querySelectorAll('input[name="selected_users[]"]:checked');

    if (checkboxes.length > 1) {
        alert("Please select only ONE user to edit.");
        return;
    }

    const row = checkboxes[0].closest('tr');
    const cells = row.querySelectorAll('td');

    document.getElementById('editUserId').value = cells[1].textContent.trim(); 
    document.getElementById('U_Username').value = cells[2].textContent.trim();
    document.getElementById('U_Email').value = cells[3].textContent.trim();
    document.getElementById('editIdentity').value = cells[4].textContent.trim().toLowerCase();

    document.getElementById('editFormModal').style.display = 'flex';
}


function closeModal() {
    document.getElementById('editFormModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('editFormModal')) {
        closeModal();
    }
}

function confirmDelete() {
    const checkboxes = document.querySelectorAll('input[name="selected_users[]"]:checked');
    
    if (checkboxes.length === 0) {
        alert("Please select at least one user to delete.");
        return false;
    }
    
    return confirm(`Are you sure you want to delete ${checkboxes.length} user(s)?`);
}