//codice per far apparire l'icona dell'occhio che permette di far vedere la password inserita
occhio = document.getElementById("password");
tasto = document.getElementById("tasto");

toggleButton.addEventListener('click', () => {
    if(password.type === 'password'){
        password.type = 'text';
        tasto.textContent = 'si';
    }
    else {}//da finire
})