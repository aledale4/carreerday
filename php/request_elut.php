<div class="container_send_mail">
    <div class="container_form_send_mail">
        <div class="title">
            <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
            <h1>Elimina il tuo account</h1>
        </div>
        <p>Account selezionato: <?php
            if($_SESSION["user-type"] == 2){
                echo $_SESSION["user"]["nomeStu"]." ".$_SESSION["user"]["cognomeStu"];
            }else if($_SESSION["user-type"] == 3){
                echo $_SESSION["user"]["nomeRef"]." ".$_SESSION["user"]["cognomeRef"]." - ".$_SESSION["user"]["ragsoc"];
            }
            ?></p>
            <a class="sendreq" href= "index.php?pag=eliminaut">Conferma</a>
        <p class="success">
            <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo "Account eliminato con successo";
                }
            ?>
        </p>
        <p class="error">
            <?php
                if (isset($_GET['error'])) {
                    switch ($_GET['error']) {
                        case 1:
                            echo "Impossibile eliminare l'account selezionato.";
                            break;
                    }
                }
            ?>
        </p>
    </div>
</div>
