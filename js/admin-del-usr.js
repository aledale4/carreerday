document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll('.delete');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            const confirmed = confirm("Sei sicuro di voler eliminare questo utente? Questa azione non può essere annullata.");
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});