<div class="home-container">
<div class="navbar">
    <div class="left-side">
        <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset="" ></a>
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

<section id="prossimi-eventi">
    <h1>Prossimi Eventi</h1>
    <div class="scrollable-container">
       <?php 
            $q = "select * from career_day";
            $r = mysqli_query($conn, $q) or die("Errore nella query");
            while ($row = mysqli_fetch_assoc($r)) {
                echo '<a class="element" href="index.php?pag=event&id='.$row["idCd"].'">';
                echo '<img src="../static/default_evento.svg" alt="">';
                echo '<p>'.$row["nameCd"].'</p>';
                echo '</a>';
            }
            if (mysqli_num_rows($r) == 0) {
                echo "<p class='element empty'>Non ancora ci sono eventi disponibili</p>";
            }
        ?>
    </div>
</section>

<section id="colloqui">
    <h1>Prossimi Colloqui</h1>
    <div class="scrollable-container">
        <?php 
            $q = "select * from prenotazioni where rStu = ".$_SESSION["user"]["idStu"];
            $r = mysqli_query($conn, $q) or die("Errore nella query");
            while ($row = mysqli_fetch_assoc($r)) {
                $q2 = "select * from adesioni where idAd = ".$row["rAd"];
                $r2 = mysqli_query($conn, $q2);
                if (mysqli_num_rows($r2) == 0) continue;
                $adesione = mysqli_fetch_assoc($r2);
                $q3 = "select * from aziende where idAz = ".$adesione["rAz"];
                $azQ = mysqli_query( $conn, $q3);
                if (mysqli_num_rows($azQ) == 0) continue;
                $azienda = mysqli_fetch_assoc($azQ);

                echo '<a class="element" href="index.php?pag=adesione&id='.$adesione["idAd"].'">';
                $file = "../static/pfp/azienda-pic/" . $azienda["idAz"] . ".jpeg";
                if (file_exists($file)) {
                    $data = file_get_contents($file); 
                    $base64 = base64_encode($data); 
                    echo '<img src="data:image/jpeg;base64,' . $base64. '" alt="">';
                } else {
                    echo '<img src="../static/default_azienda.svg" alt="">';
                }
                echo '<p>'.$azienda["ragsoc"].'</p>';
                echo '</a>';
            }
            if (mysqli_num_rows($r) == 0) {
                echo "<p class='element empty'>Non hai ancora prenotato colloqui.</p>";
            }
        ?>
    </div>
</section>

</div>