<?php
    if (!isset($_GET["id"])) exit(0);
    $id = filter_input(INPUT_GET,"id", FILTER_SANITIZE_NUMBER_INT);
    $q = "select * from adesioni where idAd = ".$id;
    $result = mysqli_query($conn, $q) or die("errore nella query"); 
    if (mysqli_num_rows($result) == 0) exit();
    $adesione = mysqli_fetch_assoc($result);

    $qAz = "select * from aziende where idAz = ".$adesione["rAz"];
    $result = mysqli_query($conn, $qAz) or die();
    if (mysqli_num_rows($result) == 0) exit();
    $azienda = mysqli_fetch_assoc($result);

    $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
    $result = mysqli_query($conn, $qEv) or die();
    if (mysqli_num_rows($result) == 0) exit();
    $evento = mysqli_fetch_assoc($result);

    $already_signed = true;
    $qCheck = "select * from prenotazioni where rAd = ".$id." and rStu = ".$_SESSION["user"]["idStu"];
    $resultCheck = mysqli_query($conn, $qCheck) or die("");
    if (mysqli_num_rows($resultCheck) == 0) $already_signed = false;

    $positionsQ = "select * from posizioni where rAz = ".$azienda["idAz"];
    $resPosQ = mysqli_query($conn, $positionsQ) or die("");

    $enabled = $adesione["enablePren"] == 1;

    $countQ = "select * from prenotazioni where rAd = ".$id." order by idPren";
    $countRes = mysqli_query($conn, $countQ) or die("");
    $count = 0;
    while ($position = mysqli_fetch_assoc($countRes)) {
        $count++;
        if ($position["rStu"] == $_SESSION["user"]["idStu"]) break;
    }

?>

<div class="home-container">
<div class="navbar">
    <div class="left-side">
        <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset=""></a>
        <p>Portale Studenti</p>
    </div>
    <div class="right-side">
        <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeStu"]; ?></span></p>
         <div class="profile">
                <a href="index.php?pag=settings">
                    <div class="user-pic"><?php include("defaultUser-pic.php") ?>
                        <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
                    </div>
                </a>
                <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
            </div>
    </div>
</div>
<div class="prenotazione-container">
    <div class="prenotazione">
        <h1 class="nome-evento"><?php echo $evento["nameCd"]?></h1>
        <p class="subtitle">Prenota il tuo colloquio con <span class="nome-azienda"><?php echo $azienda["ragsoc"]?></span></p>
        <form action="index.php" method="post">
            <input type="hidden" name="pag" value="prenotazione">
            <input type="hidden" name="id" value="<?php echo $id;?>">
            <div class="posizione-lavorativa">
                <label for="posizione">Posizione:</label>
                <select name="posizione" <?php echo $already_signed?"disabled":""?>>
                    <?php
                    $selected = null;
                    if ($already_signed) {
                        $selected = mysqli_fetch_assoc($resultCheck)["rPos"];
                    }
                    while($pos = mysqli_fetch_assoc($resPosQ)){
                        if ($pos["idPos"] == $selected){
                            echo "<option value='".$pos["idPos"]."' selected >".$pos["nomePos"]."</option>\n";
                        }else{
                            echo "<option value='".$pos["idPos"]."' >".$pos["nomePos"]."</option>\n";
                        }
                    }
                    ?>
                </select>
                <?php
                    mysqli_data_seek($resPosQ,0);
                    mysqli_data_seek($resultCheck,0);
                    $selected = mysqli_fetch_assoc($resPosQ)["idPos"];
                    mysqli_data_seek($resPosQ,0);
                    if ($already_signed) {
                        $selected = mysqli_fetch_assoc($resultCheck)["rPos"];
                    }
                    while($position = mysqli_fetch_assoc($resPosQ)){
                        if ($position["idPos"] == $selected){
                            echo "<p class='selected'>".$position["descrizionePos"]."</p>\n";
                        }else {
                            echo "<p>".$position["descrizionePos"]."</p>\n";
                        }
                    }
                ?>
            </div>
            <?php
                if($already_signed) {
                    echo "<p>Hai già prenotato un colloquio</p>";
                    echo "<p>Progressivo prenotazione: #".$count."</p>";
                }else if (!$enabled) echo "<p class='error'>Al momento non è possibile prenotare un colloquio</p>";
            ?>
            <input type="submit" value="Prenotati ora" <?php echo ($already_signed||!$enabled)?"disabled title='Già prenotato'":""?> >
        </form>
    </div>
</div>

<script>
    const select = document.querySelector(".posizione-lavorativa select");
    const descrizioni = document.querySelectorAll(".posizione-lavorativa p");

    select.addEventListener("change", (e) => {
        descrizioni.forEach((desc) => {
            desc.classList.remove("selected");
            if (desc.innerText === descrizioni[select.selectedIndex].innerText) {
                desc.classList.add("selected");
            }
        });
    });
</script>