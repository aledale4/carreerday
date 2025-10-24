<div class="home-container">

    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Aziende</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page">
                <a href="index.php"><p>Eventi</p></a>
            </div>
            <div class="nav-page selected" >
                <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeRef"]; ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"></div>
            </a>
            <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
        </div>
    </div>

    <section id="eventi">
        <h1>Colloqui Prenotati</h1>
            <?php
                $adesioni = [];
                $q = "select * from adesioni where rAz = ".$_SESSION["user"]["idAz"];
                $r = mysqli_query($conn, $q);
                while ($adesione = mysqli_fetch_assoc($r)) {
                    $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
                    $rEvPren = mysqli_query($conn, $qEv) or die();
                    if (mysqli_num_rows($rEvPren) == 0) exit();
                    $q2 = "select * from prenotazioni where rAd=".$adesione["idAd"];
                    $rPren = mysqli_query($conn, $q2) or die();
                    $evento = mysqli_fetch_assoc($rEvPren);
                    echo "<p>".$evento["nameCd"]."</p>";
                    echo "<table><tr><th>Completato</th><th>Nome Studente</th><th>Data prenotazione</th></tr>";
                    while ($prenotazione = mysqli_fetch_assoc($rPren)) {
                        $qStu = "select * from studenti where idStu = ".$prenotazione["rStu"];
                        $rStu = mysqli_query($conn, $qStu) or die();
                        if (mysqli_num_rows($rStu) == 0) continue;
                        $stu = mysqli_fetch_assoc($rStu);
                        $nomeStu = $stu["nomeStu"]." ".$stu["cognomeStu"];
                        echo "<tr class='".($prenotazione["completed"]==1?"checked":">")."'><td>";
                        echo "<form action='index.php' method='post'>";
                        echo '<input type="hidden" name="id" value="'.$prenotazione["idPren"].'">';
                        echo '<input type="hidden" name="pag" value="update_prenotazione">';
                        echo '<input required type="checkbox" name="completed" onchange="this.form.submit()" '.($prenotazione["completed"]==1?"checked >":">");
                        echo '</form>';
                        echo "</td><td>".$nomeStu."</td><td>".$prenotazione["datapren"]."</td></tr>";
                    }
                    echo "</table>";
                }
            ?>
            </table>
    </section>


</div>