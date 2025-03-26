function downloadAll() {
    var links = document.querySelectorAll('.download-link');
    links.forEach(function(link) {
        var a = document.createElement('a');
        a.href = link.href;
        a.download = link.getAttribute('data-filename'); 
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
}