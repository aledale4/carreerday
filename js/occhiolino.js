//codice per far vedere la password inserita
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById("password");
    const tasto = document.getElementById("tasto");
    const password2 = document.getElementById("password2");
    const tasto2 = document.getElementById("tasto2");

     if(password2 && tasto2){
        tasto2.addEventListener('click', () => {
            if (password2.type === "password") {
                password2.type = "text";
                tasto2.textContent = "visibility";
            } else if (password2.type === "text") {
                password2.type = "password";
                tasto2.textContent = "visibility_off";
            }
        });
     }

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