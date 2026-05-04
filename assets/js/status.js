document.addEventListener('DOMContentLoaded', function() {
    const status = document.getElementById('statusMessage');
    if (!status) {
        return;
    }

    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    const success = params.get('success');
    const message = params.get('message');

    if (error) {
        status.className = 'alert alert-error';
        status.textContent = error;
        return;
    }

    if (success) {
        status.className = 'alert alert-success';
        status.textContent = message || 'Operation completed successfully.';
    }
});
