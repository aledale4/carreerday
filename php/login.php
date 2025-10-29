<div class="center-body">
    <div class="container">
        <div class="logo-container">
            <img src="../static/logo.svg" alt="">
        </div>
        <div class="centered">
            <p class="user-type-title">Che utente sei?</p>
            <div class="user-type-select">
                <a href="index.php?usertype=1">
                    <div class="user-type-button <?php echo $_SESSION["user-type"] == 1 ? "selected" : "" ?>">
                        <p>Admin</p>
                    </div>
                </a>
                <a href="index.php?usertype=2">
                    <div class="user-type-button <?php echo $_SESSION["user-type"] == 2 ? "selected" : "" ?>">
                        <p>Studente</p>
                    </div>
                </a>
                <a href="index.php?usertype=3">
                    <div class="user-type-button <?php echo $_SESSION["user-type"] == 3 ? "selected" : "" ?>">
                        <p>Azienda</p>
                    </div>
                </a>
            </div>
            <h1>Login <?php switch ($_SESSION["user-type"]) {
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
                <input type="hidden" name="pag" value="<?php switch ($_SESSION["user-type"]) {
                    case 1:
                        echo "login_admin";
                        break;
                    case 2:
                        echo "login";
                        break;
                    case 3:
                        echo "login_soc";
                        break;
                } ?>">
                <?php
                if ($_SESSION["user-type"] == 1) {
                    echo '<input type="text" name="username" id="" placeholder="Username" maxlength="30" required>';
                } else {
                    echo '<input type="email" name="email" id="" placeholder="Email" maxlength="100" required>';
                }
                ?>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <div class="occhio">
                        <span class="material-symbols-outlined" id="tasto">visibility_off</span>
                    </div>
                </div>
                <input type="submit" value="Accedi">
            </form>
            <?php
            if ($_SESSION["user-type"] == 2 || $_SESSION["user-type"] == 3) {
                echo '<a href="request_reset_pwd.php" class="a_pwd_dimenticata">Password dimenticata?</a>';
                echo '<p class="change-action-link">Oppure <a href="index.php?pag=register">Registrati</a></p>';
            }
            ?>

            <p class="error"><?php
            if (isset($_GET["error"])) {
                $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRING);
                switch ($error) {
                    case 0:
                        echo "Password errata";
                        break;
                    case 1:
                        echo "Username/Email errata";
                        break;
                    case 2:
                        echo "Input non validi";
                        break;
                }
            }
            ?></p>
        </div>
    </div>
</div>