<div class="container">
    <img src="../static/logo.svg" alt="">
    <div class="centered">
        <p class="user-type-title">Che utente sei?</p>
        <div class="user-type-select">
            <a href="index.php?usertype=1">
                <div class="user-type-button <?php echo $_SESSION["user-type"]==1?"selected":"" ?>">
                    <p>Admin</p>
                </div>
            </a>
            <a href="index.php?usertype=2">
                <div class="user-type-button <?php echo $_SESSION["user-type"]==2?"selected":"" ?>">
                    <p>Studente</p>
                </div>
            </a>
            <a href="index.php?usertype=3">
                <div class="user-type-button <?php echo $_SESSION["user-type"]==3?"selected":"" ?>">
                    <p>Azienda</p>
                </div>
            </a>
        </div>
        <h1>Portale <?php switch($_SESSION["user-type"]){
            case 1:
                echo "Admin";
                break;
            case 2:
                echo "Studenti";
                break;
            case 3:
                echo "Aziende";
                break;
        } ?> - Login</h1>
        <form class="input-form" action="index.php" method="post">
            <input type="hidden" name="pag" value="<?php switch($_SESSION["user-type"]){
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
                if($_SESSION["user-type"] == 1){
                    echo '<input type="text" name="username" id="" placeholder="Username" required>';
                }
                else{
                    echo '<input type="email" name="email" id="" placeholder="Email" required>';
                }
                ?>
            <div>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <div class="occhio">
                    <label for="password"><button id="tasto">no</button></label>
                </div>
            </div>
            <input type="submit" value="Accedi">
            <a href="index.php?pag=request_reset_pwd">Password dimenticata?</a>
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
                        echo "Username/Email errata";
                        break;
                    case 2:
                        echo "Input non validi";
                        break;
                }
            ?></p>
    </div>
</div>