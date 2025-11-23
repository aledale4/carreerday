<?php
    $q = "select * from posizioni where rAz = ".$_SESSION["user"]["idAz"];
    $result = mysqli_query($conn,$q) or die()
?>

<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset=""></a>
            <p>Portale Aziende</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page">
                <a href="index.php"><p>Eventi</p></a>
            </div>
            <div class="nav-page" >
                <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
            </div>
            <div class="nav-page selected">
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
        <div class="nav-page">
            <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
        </div>
        <div class="nav-page selected">
            <a href="index.php?pag=posizioni"><p>Posizioni</p></a>
        </div>
    </div>

    <section id="posizioni">
        <h1>Posizioni</h1>
        <div class="posizioni-container">
            <?php
                while ($posizione = mysqli_fetch_assoc($result)){
                    $q = "select * from prenotazioni where rPos = ".$posizione["idPos"];
                    $qRes = mysqli_query($conn, $q) or die("errore");
                    $canDelete = mysqli_num_rows($qRes) == 0;
                    echo '<div class="posizione shadow-m">';
                    echo '<p><strong>'.$posizione['nomePos']."</strong><p>";
                    echo '<p>'.$posizione['descrizionePos']."<p>";
                    echo '<form action="index.php" method="post" class="delete-position-form">';
                    echo '<input type="hidden" name="pag" value="delete_position">';
                    echo '<input type="hidden" name="idPos" value="'.$posizione["idPos"].'">';
                    if($posizione["nomePos"] != "Colloquio Conoscitivo") if ($canDelete) echo '<input type="submit" class="material-symbols-outlined" value="delete_forever">';
                    echo '</form>';
                    echo "</div>\n";
                }
            ?>
            <div class="posizione shadow-m">
                <form action="index.php" method="post" class="add-position-form">
                    <input type="hidden" name="pag" value="add_position">
                    <input type="text" name="nomePos" maxlength="60" placeholder="Nome Posizione" required>
                    <textarea name="descPos" maxlength="255" placeholder="Descrizione posizione (max 255 caratteri)" required></textarea>
                    <input type="submit" class="material-symbols-outlined" value="add">
                </form>
            </div>
        </div>
    </section>


</div>