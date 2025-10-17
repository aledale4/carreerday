<div class="container">
    <img src="../static/logo.svg" alt="">
    <div class="centered">
        <h1>Portale Studenti - Registrazione</h1>
            <form class="input-form" action="index.php" method="post">
                <input type="hidden" name="pag" value="register">
                <input type="text" name="nome" id="" placeholder="Nome" required>
                <input type="text" name="cognome" id="" placeholder="Cognome" required>
                <input type="text" name="username" id="" placeholder="Username" required>
                <input type="email" name="email" id="" placeholder="Email" required>
                <input type="password" name="password" id="" placeholder="Password" required>
                <input type="password" name="password2" id="" placeholder="Conferma Password" required>
                <input type="submit" value="Registrati">
            </form>
            <p class="change-action-link">Oppure <a href="index.php?pag=login">Accedi</a></p>
            <p class="error"><?php
                if (!isset($_GET["error"])) exit();
                $error = filter_input(INPUT_GET,"error", FILTER_SANITIZE_STRING);
                switch($error){
                    case 0:
                        echo "Username già in uso";
                        break;
                    case 1:
                        echo "Email già in uso";
                        break;
                    case 2:
                        echo "Password non corrispondenti";
                        break;
                    case 3:
                        echo "Input non validi";
                        break;
                }
            ?></p>
    </div>
</div>