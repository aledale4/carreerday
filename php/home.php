<div class="home-container">
<div class="navbar">
    <div class="left-side">
        <img src="../static/logo.svg" alt="" srcset="" class="logo">
        <p>Portale Studenti</p>
    </div>
    <div class="right-side">
        <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeStu"]; ?></span></p>
        <a href="index.php?pag=settings"><div class="user-pic"><?php include("defaultUser-pic.php") ?></div></a>
        <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
        <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
    </div>
</div>

<section id="prossimi-eventi">
    <h1>Prossimi Eventi</h1>
    <div class="scrollable-container">
       <?php 
            $q = "select * from career_day";
            $r = mysqli_query($conn, $q) or die("Errore nella query");
            while ($row = mysqli_fetch_assoc($r)) {
                echo '<a href="index.php?pag=event&id='.$row["idCd"].'">';
                echo '<div class="element">';
                echo '<img src="" alt="logo evento">';
                echo '<p>'.$row["nameCd"].'</p>';
                echo '</div></a>';
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

                echo '<div class="element">';
                echo '<img src="" alt="logo azienda">';
                echo '<p>'.$azienda["ragsoc"].'</p>';
                echo '</div></a>';
            }
        ?>
    </div>
</section>

</div>