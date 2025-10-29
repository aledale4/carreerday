<div class="center-body">
    <div class="container">
        <div class="logo-container">
            <img src="../static/logo.svg" alt="">
        </div>
        <div class="centered">
            <p class="user-type-title">Che utente sei?</p>
            <div class="user-type-select">
                <a href="index.php?pag=register&usertype=2">
                    <div class="user-type-button <?php echo $_SESSION["user-type"] == 2 ? "selected" : "" ?>">
                        <p>Studente</p>
                    </div>
                </a>
                <a href="index.php?pag=register&usertype=3">
                    <div class="user-type-button <?php echo $_SESSION["user-type"] == 3 ? "selected" : "" ?>">
                        <p>Azienda</p>
                    </div>
                </a>
            </div>
            <h1>Registrazione <?php switch ($_SESSION["user-type"]) {
                case 1:
                    echo "Admin";
                    break;
                case 2:
                    echo "Studenti";
                    break;
                case 3:
                    echo "Aziende";
                    break;
            } ?></h1>
            <form class="input-form" action="index.php" method="post">
                <?php
                if ($_SESSION["user-type"] == 2) {
                    include("register-user-form.php");
                } else
                    include("register-company-form.php");
                ?>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password*" required>
                    <div class="occhio">
                        <span class="material-symbols-outlined" id="tasto">visibility_off</span>
                    </div>
                </div>
                <div class="password-container">
                    <input type="password" name="password2" id="password" placeholder="Conferma Password*" required>
                    <div class="occhio">
                        <span class="material-symbols-outlined" id="tasto">visibility_off</span>
                    </div>
                </div>
                <p class="p_di_register">
                <input type="checkbox" id="policy-privacy" name="policy-privacy" value="mio cachbox" required>
                <label  class="" for="policy-privacy">Leggi e accetta la politica della privacy <a href="politica_privacy.php" style="color: blue; text-decoration:underline">qui</a>.</label>
                </p>
                <input type="submit" value="Registrati">
            </form>
            <p class="change-action-link">Oppure <a href="index.php?pag=login">Accedi</a></p>
            <p class="error"><?php
            if (isset($_GET["error"])) {
                $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRING);
                switch ($error) {
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
            }
            ?></p>
        </div>
    </div>
</div>