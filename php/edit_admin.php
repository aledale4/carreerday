<?php
    $q = "select * from admins where idUt = '".mysqli_real_escape_string($conn, $_GET["idUsr"])."'";
    $ris = mysqli_query($conn, $q) or die("errore durante il recupero dei dati dell'admin | ".$q." | ".mysqli_error($conn));
    if(mysqli_num_rows($ris) == 0){
        header("Location: index.php?pag=users&usr-type=1&error=3");
        exit("admin non trovato");
    }
    $row = mysqli_fetch_assoc($ris);
?>
<div class="container">
    <div class="centered">
        <h1>Modifica dell' Admin n. <?php echo $row["idUt"]?></h1>
        <?php
        if(isset($_GET["error"]) && $_GET["error"] == 1){
            echo '<p class="error-message">Le password non coincidono</p>';
        }
        ?>
        <form class="input-form" action="index.php" method="post">
            <input type="text" name="nomeUt" placeholder="Nome" value="<?php echo $row["nomeUt"]?>" maxlength="30" required>
            <input type="text" name="cognomeUt" placeholder="Cognome" value="<?php echo $row["cognomeUt"]?>" maxlength="30" required>
            <div class="password-container">
                <input type="password" name="passwordUt" id="password" placeholder="Password" required>
                <span class="occhio">
                    <span id="tasto" class="material-symbols-outlined">visibility_off</span>
                </span>
            </div>
            <div class="password-container">
                <input type="password" name="passwordUt2" id="password2" placeholder="Conferma password" required>
                <span class="occhio">
                    <span id="tasto2" class="material-symbols-outlined">visibility_off</span>
                </span>
            </div>
            <input type="submit" value="Salva modifiche">
            <input type="hidden" name="idUt" value=" <?php echo $_GET['idUsr'];?> ">
            <input type="hidden" name="pag" value="edit_admin2">
        </form>
    </div>
</div>