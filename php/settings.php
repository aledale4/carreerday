<?php reload_user_data(); ?>
<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Studenti</p>
        </div>
    </div>
    <div class="title-bar">
        <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
        <p>Il tuo profilo</p>
    </div>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pag" value="edit_user">
        <div class="card">
            <div class="side">
                <div class="img"><a href="index.php?pag=fotoprofilo"><?php include("defaultUser-pic.php")  ?></a></div>
                <div class="inputs">
                    <p>Nome: <input type="text" name="nome" id="" placeholder="Nome" required value="<?php echo $_SESSION["user"]["nomeStu"]?>"></p>
                    <p>Cognome:<input type="text" name="cognome" id="" placeholder="Cognome" required value="<?php echo $_SESSION["user"]["cognomeStu"]?>"></p>
                    <p>Username:<input type="text" name="username" id="" placeholder="Username" required value="<?php echo $_SESSION["user"]["usernameStu"]?>"></p>
                    <p>Email:<input type="email" name="email" id="" placeholder="Email" required value="<?php echo $_SESSION["user"]["emailStu"]?>"></p>
                    <p>Password Esistente:<input type="password" name="password" id="" placeholder="Password">
                    </p>
                    <p>Nuova Password<input type="password" name="newpassword" id="" placeholder="Nuova Password"></p>
                </div>
            </div>
            <div class="side additional">
                <h1>Dati Aggiuntivi</h1>
                <div class="inputs">
                    <p>Numero di Telefono:<input name="tel" type="tel" placeholder="Numero di Telefono" value="<?php echo $_SESSION["user"]["telStu"]?>"></p>
                    <p>Località:<input name="loc" type="text" placeholder="Localita" value="<?php echo $_SESSION["user"]["locStu"]?>"></p>
                    <p>Biografia:<textarea name="bio" maxlength="255" placeholder="Biografia"><?php echo $_SESSION["user"]["bioStu"]?></textarea></p>
                    <p>Curriculum: <span class="cv-check"><?php echo file_exists("../private/cv/".$_SESSION["user"]["idStu"].".pdf")?"<a target='_blank' href='index.php?pag=mycv'>CV</a> Caricato con successo":"Nessun file caricato" ?></span><input type="file" name="CV" id="" accept=".pdf"></p>
                </div>
            </div>
        </div>
        <div class="links">

            <div class="links-section">
                <h3>Links</h3>
                
                <div class="link-item">
                    <label>Sito web:</label>
                    <input type="text" name="website" value="<?php echo $_SESSION["user"]["websiteStu"];?>">
                </div>
                
                <div class="link-item">
                    <label>GitHub:</label>
                    <input type="text" name="github" value="<?php echo $_SESSION["user"]["urlGithubStu"];?>">
                </div>
                
                <div class="link-item">
                    <label>LinkedIn:</label>
                    <input type="text" name="linkedin" value="<?php echo $_SESSION["user"]["urlLinkedinStu"];?>">
                </div>
            </div>
            <input class="" type="submit" value="Salva modifiche">
        </div>
    </form>
    <p class="error"><?php
                if (isset($_GET["error"])){
                    $error = filter_input(INPUT_GET,"error", FILTER_SANITIZE_NUMBER_INT);
                    switch($error){
                        case 1:
                            echo "Username già usato";
                            break;
                        case 2:
                            echo "Email già usata";
                            break;
                        case 3:
                            echo "File CV troppo grande (>5MB)";
                            break;
                        case 3:
                            echo "File CV deve essere un PDF";
                            break;
                    }
                }
            ?></p>
</div>