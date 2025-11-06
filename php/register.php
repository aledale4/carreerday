<!-- <div class="dark-mode-toggle">
    <span class="material-symbols-outlined" id="dark_mode">dark_mode</span>
</div> -->
<div class="center-body">
    <div class="container">
        <div class="logo-container">
            <a href="index.php"><img src="../static/logo.png" alt=""></a>
        </div>
        <div class="centered">
            <?php if(!isset($_GET["success"])|| $_GET["success"]!="1") : ?>
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
            <?php endif; ?>
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
            <?php if((($env["ENABLE_STUDENT_REGISTER"] == '1' && $_SESSION['user-type'] == 2) || ($env["ENABLE_COMPANY_REGISTER"] == '1' && $_SESSION['user-type'] == 3)) && $_GET["success"] != '1') : ?>
            <form class="input-form" action="index.php" method="post">
                <?php
                if ($_SESSION["user-type"] == 2) {
                    include("register-user-form.php");
                } else
                    include("register-company-form.php");
                ?>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password*" autocomplete="off" required>
                    <div class="occhio">
                        <span class="material-symbols-outlined" id="tasto">visibility_off</span>
                    </div>
                </div>
                <div class="password-container">
                    <input type="password" name="password2" id="password2" placeholder="Conferma Password*" autocomplete="off" required>
                    <div class="occhio">
                        <span class="material-symbols-outlined" id="tasto2">visibility_off</span>
                    </div>
                </div>
                <p class="p_di_register">
                    <input type="checkbox" id="policy-privacy" name="policy-privacy" value="" required>
                    <label  class="" for="policy-privacy">Leggi e accetta la politica della privacy <a href="politica_privacy.php" style="color: blue; text-decoration:underline">qui</a>.</label>
                </p>
              <input type="submit" value="Registrati">
            </form>
            <p class="change-action-link">Oppure <a href="index.php?pag=login">Accedi</a></p>
            <?php endif; ?>
            <?php
                if($_GET["success"] !="1" && ( ( $env["ENABLE_STUDENT_REGISTER"] != '1' && $_SESSION['user-type'] == 2) || ( $env["ENABLE_COMPANY_REGISTER"] != '1' && $_SESSION['user-type'] == 3 ) ) ){
                    echo "<p class=\"error\"> Al momento non è possibile registrarsi</p>";
                    echo "<a href='index.php?pag=login'>Torna al login</a>";
                }
            ?>
            <?php
                if(isset($_GET["success"]) && $_GET["success"]=="1"){
                    echo "<p class=\"success\">Account creato con successo</p>";
                    echo "<a href='index.php?pag=login'>Torna al login</a>";
                }
            ?>
            
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
                    case 4:
                        echo "Al momento non è possibile registrarsi";
                        break;
                    case 5:
                        echo "Email non autorizzata";
                        break;
                }
            }
            ?></p>
        </div>
    </div>
</div>