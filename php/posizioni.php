<?php
    $q = "select * from posizioni where rAz = ".$_SESSION["user"]["idAz"];
    $result = mysqli_query($conn,$q) or die()
?>

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
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>

    <section id="posizioni">
        <h1>Posizioni</h1>
        <div class="posizioni-container">
            <?php
                while ($posizione = mysqli_fetch_assoc($result)){
                    echo '<div class="posizione">';
                    echo '<p>'.$posizione['nomePos']."<p>";
                    echo '<p>'.$posizione['descrizionePos']."<p>";
                    echo '<form action="index.php" method="post" class="delete-position-form">';
                    echo '<input type="hidden" name="pag" value="delete_position">';
                    echo '<input type="hidden" name="idPos" value="'.$posizione["idPos"].'">';
                    echo '<input type="submit" class="material-symbols-outlined" value="delete_forever">';
                    echo '</form>';
                    echo "</div>\n";
                }
            ?>
            <div class="posizione">
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