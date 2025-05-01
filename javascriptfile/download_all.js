function downloadAll() {
    var links = document.querySelectorAll('.download-link');
    var delay = 0;
    links.forEach(function(link) {
        var a = document.createElement('a');
        a.href = link.href;
        a.download = link.getAttribute('data-filename');
        document.body.appendChild(a);
        setTimeout(function() {
            a.click();
            document.body.removeChild(a);
        }, delay);
        delay += 500; 
    });
}
