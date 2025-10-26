//codice per far vedere la password inserita
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById("password");
    const tasto = document.getElementById("tasto");

    if(!password || !tasto){
        console.error("Elemento password o tasto non trovato.");
        return;
    }

    tasto.addEventListener('click', () => {
        if (password.type === "password") {
            password.type = "text";
            tasto.textContent = "visibility";
        } else if (password.type === "text") {
            password.type = "password";
            tasto.textContent = "visibility_off";
        }
    });
});