<div class="container_reset_pwd">
    <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset="" ></a>
    <h1>
        Reset Password
    </h1>
    <div class="form_container_reset_pwd">
        <form action="index.php" method="post" class="form_reset_pwd">
            <p>Password Temporanea</p>
            <input type="password" id="password" name="password_temp" placeholder="Password ricevuta nell'email" maxlength="32" required>
            <p>Inserisci una password:</p>
            <input type="password" id="password" name="password1" placeholder="La tua nuova password" required>
            <p>Inserisci di nuovo la password:</p>
            <input type="password" id="password-confirm" name="password2" placeholder="La tua nuova password" required>
            <input type="hidden" name="pag" value="reset_pwd">
            <input type="hidden" name="token" value="<?php echo $_GET["token"]; ?>">
            <input type="submit" value="Reset Password">
        </form>
        <p class="error">
            <?php
            if (isset($_GET["error"]) && $_GET["error"] != "") {
                switch ($_GET["error"]) {
                    case 3:
                        echo "Nuove password inserite diverse.";
                        break;
                    case 4:
                        echo "La password temporanea inserita è errata.";
                        break;
                    case 5:
                        echo "Sono passati troppi giorni dalla richiesta del cambio password.";
                        break;
                    case 6:
                        echo "Richiesta di reset non valida.";
                        break;
                    default:
                        echo "Errore durante la richiesta di reset della password.";
                        break;
                    };
                }
            ?>
        </p>
        <p class="success">
            <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo "Password resettata con successo. Ora puoi effettuare il login con la tua nuova password.";
                    echo '<br><a href="index.php" class="accedi_success">Vai alla pagina di login</a>';
                }
            ?>
        </p>
    </div>
</div>