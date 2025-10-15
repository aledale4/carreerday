<div class="container">
    <img src="../static/logo.svg" alt="">
    <div class="centered">
        <h1>Portale Studenti - Login</h1>
        <form class="input-form" action="index.php" method="post">
            <input type="hidden" name="pag" value="login">
            <input type="email" name="email" id="" placeholder="Email" required>
            <input type="password" name="password" id="" placeholder="Password" required>
            <input type="submit" value="Accedi">
        </form>
        <p class="change-action-link">Oppure <a href="index.php?pag=register">Registrati</a></p>
    </div>
</div>