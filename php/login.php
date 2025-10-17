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
        <p class="error"><?php
                if (!isset($_GET["error"])) exit();
                $error = filter_input(INPUT_GET,"error", FILTER_SANITIZE_STRING);
                switch($error){
                    case 0:
                        echo "Password errata";
                        break;
                    case 1:
                        echo "Email errata";
                        break;
                    case 2:
                        echo "Input non validi";
                        break;
                }
            ?></p>
    </div>
</div>