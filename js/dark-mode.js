document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.dark-mode-toggle');
    const body = document.body;

    // Check for saved preference in localStorage
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode === 'enabled') {
        body.classList.add('darkmode');
    }

    toggle.addEventListener('click', () => {
        body.classList.toggle('darkmode');
        // Save preference to localStorage
        if (body.classList.contains('darkmode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
});