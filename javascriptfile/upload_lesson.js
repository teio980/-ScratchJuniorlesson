document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById("categoryModal");
    const btn = document.getElementById("chooseCategoryBtn");
    const span = document.getElementById("closeModal");

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
