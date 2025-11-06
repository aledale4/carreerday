<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset=""></a>
            <p>Portale Admin</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page-selected">
                <a href="index.php"><p>Home</p></a>
            </div>
            <div class="nav-page">
                <a href="index.php?pag=users"><p>Utenti</p></a>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeUt"]; ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php") ?></div>
            </a>
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>
    <div class="sub-menu">
        <div class="sub-menu-item selected">
            //inserire qui il tasti per selezionare il tipo di utente da mostrare
        </div>
    </div>
    //contenuto della pagina, con possibilità di aggiungere gli utenti admin
</div>