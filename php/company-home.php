<div class="home-container">

    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Aziende</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page selected">
                <p>Eventi</p>
            </div>
            <div class="nav-page">
                <p>Colloqui</p>
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
        <h1>Eventi</h1>
        <div class="scrollable-container company">
            <?php
            $q = "select * from adesioni where rAz=".$_SESSION["user"]["idAz"];
            $r = mysqli_query($conn, $q);
            while ($row = mysqli_fetch_assoc($r)) {
                $q2 = "select * from career_day where idCd=".$row["rCd"];
                $event = mysqli_query($conn, $q2) or die("errore nella query");
                $eventRow = mysqli_fetch_assoc($event);
                echo '<a href="index.php?pag=event&id=' . $eventRow["idCd"] . '">';
                echo '<div class="element">';
                echo '<img src="" alt="logo evento">';
                echo '<p>' . $eventRow["nameCd"] . '</p>';
                echo '</div></a>';
            }
            ?>
        </div>
    </section>


</div>