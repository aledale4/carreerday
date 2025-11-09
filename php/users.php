<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset=""></a>
            <p>Portale Admin</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page">
                <a href="index.php"><p>Home</p></a>
            </div>
            <div class="nav-page selected">
                <a href="index.php?pag=users"><p>Utenti</p></a>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeUt"]; ?></span></p>
            <a>
                <div class="user-pic"><?php include("defaultUser-pic.php") ?></div>
            </a>
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>
    <div class="sub-menu">
        <div>
            <a class="sub-menu-item <?php if($_GET['usr-type'] == 1) echo 'selected'; ?>" href="index.php?pag=users&usr-type=1">
                <p>Admin</p>
            </a>
        </div>
        <div>
            <a class="sub-menu-item <?php if($_GET['usr-type'] == 2) echo 'selected'; ?>" href="index.php?pag=users&usr-type=2"><p>Studenti</p></a>
        </div>
        <div>
            <a class="sub-menu-item <?php if($_GET['usr-type'] == 3) echo 'selected'; ?>" href="index.php?pag=users&usr-type=3"><p>Aziende</p></a>
        </div>
    </div>
    <div class="users-container">
        <div class="table-container">
            <?php
                $usr_type = mysqli_real_escape_string($conn, $_GET['usr-type']);
                switch($usr_type){
                    case 1:
                        $q = "select idUt,nomeUt,cognomeUt from admins";
                        $ris = mysqli_query($conn, $q)or die("Errore nella query: ".mysqli_error($conn));
                        $num = mysqli_num_rows($ris);
                        echo '<div>';
                        if(isset($_GET["error"]) && $_GET["error"] == 2){
                            echo '<p class="error-message">Username già in uso</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 3){
                            echo '<p class="error-message">Admin non trovato</p>';
                        }
                        else if(isset($_GET["success"]) && $_GET["success"] == 2){
                            echo '<p class="success-message">Profilo admin modificato con successo.</p>';
                        }
                        echo '</div>';
                        echo '<p class="counter">Trovati '.$num.' utenti</p>';
                        echo '<table>';
                        echo '<tr><th>ID</th><th>Nome</th><th>Cognome</th><th>Modifica</th></tr>';
                        while ($row = mysqli_fetch_assoc($ris)) {
                            echo '<tr>';
                            echo '<td>'.$row["idUt"].'</td>';
                            echo '<td>'.$row["nomeUt"].'</td>';
                            echo '<td>'.$row["cognomeUt"].'</td>';
                            echo '<td><a class="modify" href="index.php?pag=edit_admin&idUsr='.$row["idUt"].'"><span class="material-symbols-outlined">edit</span></a></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                        echo '<div>';
                        if(isset($_GET["error"]) && $_GET["error"] == 1){
                            echo '<p class="error-message">Errore: username già esistente.</p>';
                        }
                        if(isset($_GET["success"]) && $_GET["success"] == 1){
                            echo '<p class="success-message">Nuovo admin aggiunto con successo.</p>';
                        }
                        echo '</div>';
                        echo '<div class="container">';
                        echo '<div class="centered">';
                        echo '<h1>Aggiungi un nuovo admin</h1>';
                        echo '<form class="input-form" action="index.php" method="post" class="add-admin-form">';
                        echo '<input type="text" name="nomeUt" placeholder="nome" maxlength="30" required>';
                        echo '<input type="text" name="cognomeUt" placeholder="cognome" maxlength="30" required>';
                        echo '<input type="text" name="usernameUt" placeholder="username" maxlength="30" required>';
                        echo '<div class="password-container">';
                        echo '<input type="password" name="passwordUt" id="password" placeholder="Password*" autocomplete="off" required>';
                        echo '<div class="occhio">';
                        echo '<span class="material-symbols-outlined" id="tasto">visibility_off</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '<input type="submit" value="aggiungi nuovo admin">';
                        echo '<input type="hidden" name="pag" value="add_admin">';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                        break;
                    case 2:
                        $q = "select idStu,nomeStu,cognomeStu,emailStu,verificato from studenti order by verificato desc";
                        $ris = mysqli_query($conn, $q)or die("Errore nella query: ".mysqli_error($conn));
                        $num = mysqli_num_rows($ris);
                        echo '<div>';
                        if(isset($_GET["error"]) && $_GET["error"] == 2){
                            echo '<p class="error-message">Errore nell\'eliminazione dello studente.</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 3){
                            echo '<p class="error-message">Errore nella richiesta di eliminazione</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 4){
                            echo '<p class="error-message">Impossibile eliminare lo studente perché ha delle prenotazioni attive.</p>';
                        }
                        else if(isset($_GET["success"]) && $_GET["success"] == 2){
                            echo '<p class="success-message">Utente eliminato con successo.</p>';
                        }
                        echo '</div>';
                        echo '<p class="counter">Trovati '.$num.' utenti</p>';
                        echo '<table>';
                        echo '<tr><th>ID</th><th>Nome</th><th>Cognome</th><th>Email</th><th>Verificato</th><th>Elimina</th></tr>';
                        while ($row = mysqli_fetch_assoc($ris)) {
                            echo '<tr>';
                            echo '<td>'.$row["idStu"].'</td>';
                            echo '<td>'.$row["nomeStu"].'</td>';
                            echo '<td>'.$row["cognomeStu"].'</td>';
                            echo '<td>'.$row["emailStu"].'</td>';
                            echo '<td>'.($row["verificato"] ? '<p class="verified">si</p>':'<p class="not-verified">no').'</td>';
                            echo '<td>';
                            echo '<form action="index.php" method="post">';
                            echo '<input type="hidden" name="idUsr" value="'.$row["idStu"].'">';
                            echo '<input type="hidden" name="usr-type" value="2">';
                            echo '<input type="hidden" name="pag" value="admin_del_usr">';
                            echo '<button type="submit" class="delete"><span class="material-symbols-outlined">delete_forever</span></button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                        break;
                    case 3:
                        $q = "select idAz,ragsoc,email,nomeRef,cognomeRef from aziende";
                        $ris = mysqli_query($conn, $q)or die("Errore nella query: ".mysqli_error($conn));
                        $num = mysqli_num_rows($ris);
                        echo '<div>';
                        if(isset($_GET["error"]) && $_GET["error"] == 2){
                            echo '<p class="error-message">Errore nell\'eliminazione dell\'azienda.</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 3){
                            echo '<p class="error-message">Errore nella richiesta di eliminazione</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 4){
                            echo '<p class="error-message">Impossibile eliminare l\'azienda perché ha aderito a uno o più eventi.</p>';
                        }
                        else if(isset($_GET["error"]) && $_GET["error"] == 5){
                            echo '<p class="error-message">Impossibile eliminare l\'azienda perché ha dei colloqui prenotati.</p>';
                        }
                        else if(isset($_GET["success"]) && $_GET["success"] == 2){
                            echo '<p class="success-message">Azienda eliminata con successo.</p>';
                        }
                        echo '</div>';
                        echo '<p class="counter">Trovati '.$num.' utenti</p>';
                        echo '<table>';
                        echo '<tr><th>ID</th><th>Nome azienda</th><th>Email azienda</th><th>Nome referente</th><th>Cognome refefrente</th><th>Elimina</th></tr>';
                        while ($row = mysqli_fetch_assoc($ris)) {
                            echo '<tr>';
                            echo '<td>'.$row["idAz"].'</td>';
                            echo '<td>'.$row["ragsoc"].'</td>';
                            echo '<td>'.$row["email"].'</td>';
                            echo '<td>'.$row["nomeRef"].'</td>';
                            echo '<td>'.$row["cognomeRef"].'</td>';
                            echo '<td>';
                            echo '<form action="index.php" method="post">';
                            echo '<input type="hidden" name="idUsr" value="'.$row["idAz"].'">';
                            echo '<input type="hidden" name="usr-type" value="3">';
                            echo '<input type="hidden" name="pag" value="admin_del_usr">';
                            echo '<button type="submit" class="delete"><span class="material-symbols-outlined">delete_forever</span></button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                        break;
                    default:
                        die("Errore nel caricamento degli utenti.");
                        break;
                }
            ?>
    </div>
</div>