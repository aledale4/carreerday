<?php
    if (!isset($_GET["id"]))
        exit(0);
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    $q = "select * from career_day where idCd = " . $id;
    $result = mysqli_query($conn, $q) or die("errore nella query");
    if (mysqli_num_rows($result) == 0)
        exit(0);
    $event = mysqli_fetch_assoc($result);

    $q2 = "select * from adesioni where rCd = " . $id;
    $adesioni_result = mysqli_query($conn, $q2) or die("errore nella query");

    if ($_SESSION["user-type"] == 3) {
        $q = "select * from adesioni where rCd = '" . $id . "' and rAz = '" . $_SESSION["user"]["idAz"] . "'";
        $r = mysqli_query($conn, $q) or die("errore nella query");
        $ad = mysqli_fetch_assoc($r);
    }
?>

<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale <?php
            switch ($_SESSION["user-type"]) {
                case 1:
                    echo "Admin";
                    break;
                case 2:
                    echo "Studenti";
                    break;
                case 3:
                    echo "Aziende";
                    break;
            } ?></p>
        </div>
        <div class="right-side">
            <p>Benvenuto, <span><?php
            switch ($_SESSION["user-type"]) {
                case 1:
                    echo $_SESSION["user"]["nomeUt"];
                    break;
                case 2:
                    echo $_SESSION["user"]["nomeStu"];
                    break;
                case 3:
                    echo $_SESSION["user"]["nomeRef"];
                    break;
            } ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"></div>
            </a>
        </div>
    </div>

    <div class="event <?php if ($_SESSION["user-type"] == 3) {echo "company";} ?>">
        <div class="event-main">
            <div class="event-title">
                <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
                <p>Eventi / <?php echo $event["nameCd"] ?></p>
                <?php if ($_SESSION["user-type"] == 1) {
                    echo '<a href="index.php?pag=edit_event&id=' . $id . '"><span class="material-symbols-outlined">edit</span></a>';
                } ?>
            </div>
            <div class="event-info">
                <p class="event-date"><?php echo date_format(date_create($event["dateCd"]), "d F Y"); ?> /
                    <?php echo date_format(date_create(datetime: $event["fromCd"]), "H:i"); ?> -
                    <?php echo date_format(date_create(datetime: $event["toCd"]), "H:i"); ?>
                </p>
                <span class="material-symbols-outlined">location_on</span>
                <p class="event-location"><?php echo $event["locationCd"] ?></p>
            </div>
            <p class="event-desc"><?php echo $event["descCd"] ?></p>
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
        <?php
        if ($_SESSION["user-type"] == 3) {
            echo '<div class="event-qr">';
            echo '<img src="../static/qrcodes/' . $ad["idAd"] . '.png">';
            echo '<form action="index.php">';
            echo '<input type="hidden" name="pag" value="download_qr">';
            echo '<input type="hidden" name="id" value="' . $ad["idAd"] . '">';
            echo '<input type="submit" value="Download">';
            echo '</form>';
            echo '</div>';
        }
        ?>
    </div>

</div>