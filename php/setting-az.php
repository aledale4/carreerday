<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Aziende</p>
        </div>
    </div>
    <div class="title-bar">
        <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
        <p>Il tuo profilo</p>
    </div>
    <form action="index.php" class="form-set" method="$_POST">
        <div class="card">
            <div class="side">
                <div class="img"><a href=""><?php include("defaultUser-pic.php")  ?>bottone</a></div>
                <div class="inputs">
                    <input type="hidden" name="pag" value="modifiche">
                    <p>Ragione sociale: <input type="text" name="ragionesoc" id="" placeholder="Ragione sociale" value="<?php echo $_SESSION["user"]["ragsoc"]?>" required></p>
                    <p>Indirizzo:<input type="text" name="ind" id="" placeholder="Indirizzo" value="<?php echo $_SESSION["user"]["ind"]?>" required ></p>
                    <p>CAP:<input type="text" name="cap" id="" placeholder="CAP" value="<?php echo $_SESSION["user"]["cap"]?>" required></p>
                    <p>Località:<input type="text" Passwo name="loc" id="" placeholder="Località" value="<?php echo $_SESSION["user"]["loc"]?>" required></p>
                    <p>Provincia:<input type="text" name="prov" id="" placeholder="Provincia" value="<?php echo $_SESSION["user"]["prov"]?>" required>
                    </p>
                    <p>P.iva<input type="text" name="pivs" id="" placeholder="p.iva" value="<?php echo $_SESSION["user"]["piva"]?>" required></p>
                </div>
            </div>
            <div class="side additional">
                <div class="inputs">
                    <p>Nome referente:<input type="texte" placeholder="Nome referente" value="<?php echo $_SESSION["user"]["nomeRef"]?>"></p>
                    <p>Cognome referente:<input type="text" placeholder="Cognome referente" value="<?php echo $_SESSION["user"]["cognomeRef"]?>"></p>
                    <p>Username:<input type="text" placeholder="Username" value="<?php echo $_SESSION["user"]["usernameRef"]?>"></p>
                    <p>Email:<input type="email" name="email" id="" value="<?php echo $_SESSION["user"]["email"]?>"></p>
                    <p>Sito web:<input type="text" name="web" id="" value="<?php echo $_SESSION["user"]["web"]?>" class="web-link"></p>
                </div>
            </div>
        </div>
        <div class="links">

            <div class="links-section">
                <h3>Cambia password</h3>
                
                <div class="link-item">
                    <label>Password attuale:</label>
                    <input type="password">
                </div>
                
                <div class="link-item">
                    <label>Nuova password:</label>
                    <input type="password">
                </div>
            </div>
            <input class="" type="submit" value="Salva modifiche">
        </div>
    </form>
</div>