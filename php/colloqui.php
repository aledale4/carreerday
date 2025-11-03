<div class="home-container">

    <div class="navbar">
        <div class="left-side">
            <a href="index.php" class="logo"><img src="../static/logo.svg" alt="" srcset=""></a>
            <p>Portale Aziende</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page">
                <a href="index.php"><p>Eventi</p></a>
            </div>
            <div class="nav-page selected" >
                <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
            </div>
            <div class="nav-page">
                <a href="index.php?pag=posizioni"><p>Posizioni</p></a>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeRef"]; ?></span></p>
             <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php")  ?></div>
            </a>
            <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>

    <div class="mobile-nav">
        <div class="nav-page">
            <a href="index.php"><p>Eventi</p></a>
        </div>
        <div class="nav-page selected">
            <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
        </div>
        <div class="nav-page">
            <a href="index.php?pag=posizioni"><p>Posizioni</p></a>
        </div>
    </div>

    <section id="eventi">
        <h1>Colloqui Prenotati</h1>
        <div class="colloqui">
            <?php
                $adesioni = [];
                $q = "select * from adesioni where rAz = ".$_SESSION["user"]["idAz"];
                $r = mysqli_query($conn, $q);
                while ($adesione = mysqli_fetch_assoc($r)) {
                    $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
                    $rEvPren = mysqli_query($conn, $qEv) or die();
                    if (mysqli_num_rows($rEvPren) == 0) exit();
                    $q2 = "select * from prenotazioni where rAd=".$adesione["idAd"]." order by idPren";;
                    $rPren = mysqli_query($conn, $q2) or die();
                    $evento = mysqli_fetch_assoc($rEvPren);
                    echo "<p class='evento-colloquio'>".$evento["nameCd"]."</p>";
                    echo "<table><tr><th>Completato</th><th>Studente</th><th>Posizione</th><th>Data prenotazione</th></tr>";
                    while ($prenotazione = mysqli_fetch_assoc($rPren)) {
                        $qStu = "select * from studenti where idStu = ".$prenotazione["rStu"];
                        $rStu = mysqli_query($conn, $qStu) or die();
                        if (mysqli_num_rows($rStu) == 0) continue;
                        $stu = mysqli_fetch_assoc($rStu);
                        $nomeStu = $stu["nomeStu"]." ".$stu["cognomeStu"];

                        $posQ = "select * from posizioni where idPos = ".$prenotazione["rPos"];
                        $posPren = mysqli_query($conn, $posQ) or die();
                        $pos = [];
                        if (mysqli_num_rows($posPren) != 0){
                            $pos = mysqli_fetch_assoc($posPren);
                        }
                        echo "<tr class='".($prenotazione["completed"]==1?"checked":"")."'><td>";
                        echo "<form action='index.php' method='post'>";
                        echo '<input type="hidden" name="id" value="'.$prenotazione["idPren"].'">';
                        echo '<input type="hidden" name="pag" value="update_prenotazione">';
                        echo '<input required type="checkbox" name="completed" onchange="this.form.submit()" '.($prenotazione["completed"]==1?"checked >":">");
                        echo '</form>';
                        echo "</td><td>";
                        echo "<div class='stu-name'>";
                        echo $nomeStu;
                        echo "<button class='material-symbols-outlined' onclick='switchInfo(\"".$prenotazione["idPren"]."\")'>expand_circle_down</button>";
                        echo "</div>";
                        echo "<div class='info-stu' id='".$prenotazione["idPren"]."'>";
                        echo "<p>Nome: <span>".$stu["nomeStu"]."</span></p>";
                        echo "<p>Cognome: <span>".$stu["cognomeStu"]."</span></p>";
                        echo "<p>Email: <span>".$stu["emailStu"]."</span></p>";
                        echo "<p>Numero di telefono: <span>".$stu["telStu"]."</span></p>";
                        echo "<p>Località: <span>".$stu["locStu"]."</span></p>";
                        echo "<p>Sito web: <span><a target='_blank' href='".$stu["websiteStu"]."'>".$stu["websiteStu"]."</a></span></p>";;
                        echo "<p>GitHub: <span><a target='_blank' href='".$stu["urlGithubStu"]."'>".$stu["urlGithubStu"]."</a></span></p>";
                        echo "<p>LinkedIn: <span><a target='_blank' href='".$stu["urlLinkedinStu"]."'>".$stu["urlLinkedinStu"]."</a></span></p>";
                        echo "<p>Biografia: <span>".$stu["bioStu"]."</span></p>";
                        echo "<p>CV: ".(file_exists("../private/cv/".$stu["idStu"].".pdf")?("<a href='index.php?pag=viewcv&id=".$stu["idStu"]."'>Apri</a>"):"No CV")."</p>";
                        echo "</div>";
                        echo "</td><td>".$pos["nomePos"]."</td><td>".$prenotazione["datapren"]."</td></tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </section>


</div>

<script>
    function switchInfo(id){
        let info = document.getElementById(id);
        info.classList.toggle("expanded");
    }

</script>