<div class="home-container">
<div class="navbar">
    <div class="left-side">
        <img src="../static/logo.svg" alt="" srcset="" class="logo">
        <p>Portale Studenti</p>
    </div>
    <div class="right-side">
        <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeStu"]; ?></span></p>
        <a href="index.php?pag=settings"><div class="user-pic"></div></a>
        <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
    </div>
</div>

<section id="prossimi-eventi">
    <h1>Prossimi Eventi</h1>
    <div class="scrollable-container">
        <a href="index.php?pag=event">
            <div class="element">
                <img src="" alt="logo evento">
                <p>Nome Evento</p>
            </div>
        </a>
        <a href="index.php?pag=event">
            <div class="element">
                <img src="" alt="logo evento">
                <p>Nome Evento</p>
            </div>
        </a>
    </div>
</section>

<section id="colloqui">
    <h1>Prossimi Colloqui</h1>
    <div class="scrollable-container">
        <div class="element">
            <img src="" alt="logo evento">
            <p>Nome Azienda</p>
            <p>10 ott - 12:40</p>
        </div>
        <div class="element">
            <img src="" alt="logo evento">
            <p>Nome Azienda</p>
            <p>10 ott - 12:50</p>
        </div>
    </div>
</section>

</div>