function showEditForm() {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');

    if (checkboxes.length > 1) {
        alert("Please select only ONE user to edit.");
        return;
    }

    const row = checkboxes[0].closest('tr');
    const cells = row.querySelectorAll('td');

    document.getElementById('editUserId').value = cells[1].textContent.trim(); 
    document.getElementById('U_Username').value = cells[2].textContent.trim();
    document.getElementById('U_Email').value = cells[3].textContent.trim();

    const identityValue = cells[4].textContent.trim().toLowerCase();
    if (identityValue === "student") {
        document.getElementById('identity_S').checked = true;
    } else if (identityValue === "teacher") {
        document.getElementById('identity_T').checked = true;
    }

    document.getElementById('editFormModal').style.display = 'block';
}


function closeModal() {
    document.getElementById('editFormModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('editFormModal')) {
        closeModal();
    }
}
