function downloadAll() {
    try {
        var links = document.querySelectorAll('a.download-link');
        if (links.length === 0) {
            alert('No downloadable files found');
            return;
        }

        var delay = 0;
        links.forEach(function(link) {
            setTimeout(function() {
                try {
                    var a = document.createElement('a');
                    a.href = link.href;
                    a.download = link.getAttribute('data-filename') || link.href.split('/').pop();
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } catch (e) {
                    console.error('Error downloading file:', e);
                }
            }, delay);
            delay += 500; // 0.5 second delay between downloads
        });
    } catch (e) {
        console.error('Error in downloadAll:', e);
        alert('An error occurred while trying to download files');
    }
}