<div class="container">
    <img src="../static/logo.svg" alt="">
    <div class="centered">
        <p class="user-type-title">Che utente sei?</p>
        <div class="user-type-select">
            <a href="index.php?pag=register&usertype=2">
                <div class="user-type-button <?php echo $_SESSION["user-type"]==2?"selected":"" ?>">
                    <p>Studente</p>
                </div>
            </a>
            <a href="index.php?pag=register&usertype=3">
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
        } ?> - Registrazione</h1>
            <form class="input-form" action="index.php" method="post">
                <?php
                    if($_SESSION["user-type"]==2){
                        include("register-user-form.php");
                    }else include("register-company-form.php"); 
                ?>
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