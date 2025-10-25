<?php
    if (!isset($_GET["id"]))
        exit(0);
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    $q = "select * from career_day where idCd = " . $id;
    $result = mysqli_query($conn, $q) or die("errore nella query");
    if (mysqli_num_rows($result) == 0) exit();
    $event = mysqli_fetch_assoc($result);

    $q2 = "select * from adesioni where rCd = " . $id;
    $adesioni_result = mysqli_query($conn, $q2) or die("errore nella query");

    $aziendeQ = "select * from aziende";
    $aziendeRes = mysqli_query($conn, $aziendeQ) or die();
    
?>

<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Admin</p>
        </div>
        <div class="right-side">
            <p>Benvenuto, <span><?php echo $_SESSION["user"]["nomeUt"]; ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php")  ?></div>
            </a>
        </div>
    </div>
    <form action="index.php" method="post">
        <input type="hidden" name="pag" value="edit_event">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="event edit">
            <div class="event-title">
                <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
                <p>Eventi / <input required name="nome" type="text" maxlength="30" value="<?php echo $event["nameCd"] ?>"></p>
                <input type="submit" value="Salva">
            </div>
            <div class="event-info">
                <p class="event-date">
                    <input required name="date" type="date" value="<?php echo date_format(date_create($event["dateCd"]), "Y-m-d"); ?>">
                    /
                    <input required name="start_time" type="time"
                        value="<?php echo date_format(date_create(datetime: $event["fromCd"]), "H:i"); ?>">
                    -
                    <input required name="end_time" type="time" value="<?php echo date_format(date_create(datetime: $event["toCd"]), "H:i"); ?>">
                </p>
                <span class="material-symbols-outlined">location_on</span>
                <p class="event-location"><input required name="pos" type="text" maxlength="30" value="<?php echo $event["locationCd"] ?>">
                </p>
            </div>
            <textarea name="descrizione" class="event-desc" maxlength="256"><?php echo $event["descCd"] ?></textarea>
        </div>
        </form>
        <div class="event-participants">
            <p>Con la partecipazione di:</p>
            <p class="sub">Ricordati di salvare le modifiche all'evento prima di modificare le aziende</p>
            <div class="participants">
                <?php
                $aziende = array();
                while ($adesione = mysqli_fetch_assoc($adesioni_result)) {
                    $q = "select ragsoc,idAz from aziende where idAz = " . $adesione["rAz"];
                    $result = mysqli_query($conn, $q) or die("errore nella query");
                    $azienda = mysqli_fetch_assoc($result);
                    $aziende[$azienda["idAz"]] = 1;
                    echo '<div class="participant">';
                    echo '<p>' . $azienda['ragsoc'] . '</p>';
                    echo '<img src="" alt="">';
                    echo '<form action="index.php" method="post" class="delete-form">';
                    echo '<input type="hidden" name="pag" value="remove_adesione">';
                    echo '<input type="hidden" name="idAd" value="'.$adesione["idAd"].'">';
                    echo '<input type="hidden" name="idEvento" value="'.$id.'">';
                    echo '<input type="submit" class="material-symbols-outlined" value="delete_forever">';
                    echo "</form>";
                    echo '</div>';
                }
                while ($azienda = mysqli_fetch_assoc($aziendeRes)) {
                    if ($aziende[$azienda["idAz"]] == 1) continue;
                    echo $aziende[$azienda["idAz"]];
                    echo '<div class="participant">';
                    echo '<p>' . $azienda['ragsoc'] . '</p>';
                    echo '<img src="" alt="">';
                    echo '<form action="index.php" method="post" class="add-form">';
                    echo '<input type="hidden" name="pag" value="add_adesione">';
                    echo '<input type="hidden" name="idEvento" value="'.$id.'">';
                    echo '<input type="hidden" name="idAz" value="'.$azienda["idAz"].'">';
                    echo '<input type="submit" class="material-symbols-outlined" value="add">';
                    echo '</form>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
</div>