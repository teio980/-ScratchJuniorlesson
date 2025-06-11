const navbarButtons = document.querySelectorAll('.navbar button');
const dynamicContent = document.getElementById('dynamic-content');

function loadPage(url, transitionType) {
    dynamicContent.classList.remove('active');
    dynamicContent.classList.add('fade-out');

    fetch(url)
        .then(response => response.text())
        .then(data => {
            dynamicContent.innerHTML = data;

            dynamicContent.classList.remove('fade-out');
            dynamicContent.classList.add('active', transitionType);
        });
}

navbarButtons.forEach(button => {
    button.addEventListener('click', function() {
        const targetPage = this.getAttribute('data-target');
        const transitionType = this.getAttribute('data-transition');
        loadPage(targetPage, transitionType);
    });
});
