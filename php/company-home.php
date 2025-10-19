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
            <div class="nav-page">
                <p>Impostazioni</p>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeRef"]; ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"></div>
            </a>
        </div>
    </div>

    <div class="company-events">

    </div>


</div>