document.addEventListener('DOMContentLoaded', function () {
    const downloadBtn = document.querySelector('.download-btn');

    downloadBtn.addEventListener('click', (e) => {
        e.preventDefault();

        fetch('export_statistics.php')
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'statistics.json';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => console.error('Error:', error));
    });
});