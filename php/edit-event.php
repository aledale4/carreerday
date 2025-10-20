<?php
if (!isset($_GET["id"]))
    exit(0);
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
$q = "select * from career_day where idCd = " . $id;
$result = mysqli_query($conn, $q) or die("errore nella query");
$event = mysqli_fetch_assoc($result);

$q2 = "select * from adesioni where rCd = " . $id;
$adesioni_result = mysqli_query($conn, $q2) or die("errore nella query");
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
                <div class="user-pic"></div>
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
            <div class="event-participants">
                <p>Con la partecipazione di:</p>
                <div class="participants">
                    <?php
                    while ($adesione = mysqli_fetch_assoc($adesioni_result)) {
                        $q = "select ragsoc from aziende where idAz = " . $adesione["rAz"];
                        $result = mysqli_query($conn, $q) or die("errore nella query");
                        $azienda = mysqli_fetch_assoc($result);
                        echo '<div class="participant">';
                        echo '<p>' . $azienda['ragsoc'] . '</p>';
                        echo '<img src="" alt="">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>