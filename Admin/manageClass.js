function showEditForm() {
    const checkboxes = document.querySelectorAll('input[name="selected_classes[]"]:checked');

    if (checkboxes.length > 1) {
        alert("Please select only ONE class to edit.");
        return;
    }

    if (checkboxes.length < 1) {
        alert("Please select at least ONE class to edit.");
        return;
    }

    const row = checkboxes[0].closest('tr');
    const cells = row.querySelectorAll('td');

    document.getElementById('editClassId').value = cells[1].textContent.trim(); 
    document.getElementById('Class_code').value = cells[2].textContent.trim();
    document.getElementById('Class_name').value = cells[3].textContent.trim();
    document.getElementById('Teacher_name').value = cells[4].textContent.trim();
    document.getElementById('Class_description').value = cells[5].textContent.trim();
    document.getElementById('max_capacity').value = cells[6].textContent.trim();

    document.getElementById('editFormModal').style.display = 'flex';
    $('.select2-edit').select2({
        dropdownParent: $('#editFormModal')
    });
}

function showAddForm() {
    document.getElementById('addClassModal').style.display = 'flex';
    $('.select2-add').select2({
        dropdownParent: $('#addClassModal')
    });
}

function closeModal() {
    document.getElementById('editFormModal').style.display = 'none';
    document.getElementById('addClassModal').style.display = 'none';
    $('.select2-add').select2('destroy');
    $('.select2-edit').select2('destroy');
}

window.onclick = function(event) {
    if (event.target == document.getElementById('editFormModal') || event.target == document.getElementById('addClassModal')) {
        closeModal();
    }
}

function confirmDelete() {
    const checkboxes = document.querySelectorAll('input[name="selected_classes[]"]:checked');
    
    if (checkboxes.length === 0) {
        alert("Please select at least one class to delete.");
        return false;
    }
    
    return confirm(`Are you sure you want to delete ${checkboxes.length} class(es)?`);
}