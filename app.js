document.addEventListener('DOMContentLoaded', function() {
    const bigButton = document.getElementById('big-button');
    const fileInput = document.getElementById('file-input');
    const fileListArea = document.getElementById('file-list-area');
    const fileList = document.getElementById('file-list');

    // Update server time every second
    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('server-time').textContent = timeStr;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Click to browse
    bigButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag and drop events
    bigButton.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        bigButton.classList.add('drag-over');
    });

    bigButton.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        bigButton.classList.remove('drag-over');
    });

    bigButton.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        bigButton.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Prevent default drag behaviors on document
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    document.addEventListener('drop', function(e) {
        e.preventDefault();
    });

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getFileIcon(type) {
        if (type.startsWith('image/')) return '🖼️';
        if (type.startsWith('text/')) return '📄';
        if (type.includes('pdf')) return '📕';
        if (type.includes('excel') || type.includes('sheet') || type.includes('csv')) return '📊';
        if (type.includes('word') || type.includes('document')) return '📝';
        return '📎';
    }

    function handleFiles(files) {
        if (files.length === 0) return;

        fileListArea.style.display = 'block';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const objectUrl = URL.createObjectURL(file);
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>
                    <span style="margin-right: 6px;">${getFileIcon(file.type)}</span>
                    <a href="${objectUrl}" target="_blank" class="file-link" title="Open file locally">${escapeHtml(file.name)}</a>
                </td>
                <td>${file.type || 'Unknown'}</td>
                <td>${formatBytes(file.size)}</td>
                <td><a href="${objectUrl}" target="_blank" class="file-link">View</a></td>
            `;

            fileList.appendChild(row);
        }

        // Reset file input so same files can be selected again
        fileInput.value = '';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
