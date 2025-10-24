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
    <form action="index.php" method="post" class="form-set">
        <div class="card">
            <div class="side">
                <div class="img"><?php include("defaultUser-pic.php")  ?></div>
                <div class="inputs">
                    <input type="hidden" name="pag" value="modifiche">
                    <p>Nome: <input type="text" name="nome" id="" placeholder="Nome" required></p>
                    <p>Cognome:<input type="text" name="cognome" id="" placeholder="Cognome" required></p>
                    <p>Username:<input type="text" name="username" id="" placeholder="Username" required></p>
                    <p>Email:<input type="email" Passwo name="email" id="" placeholder="Email" required></p>
                    <p>Password Esistente:<input type="password" name="password" id="" placeholder="Password" required>
                    </p>
                    <p>Nuova Password<input type="password" name="newpassword" id="" placeholder="Nuova Password"
                            required></p>
                </div>
            </div>
            <div class="side additional">
                <h1>Dati Aggiuntivi</h1>
                <div class="inputs">
                    <p>Numero di Telefono:<input type="tel" placeholder="Numero di Telefono"></p>
                    <p>Località:<input type="text" placeholder="Localita"></p>
                    <p>Biografia:<input type="text" placeholder="Biografia"></p>
                    <p>Curriculum:<input type="file" name="CV" id=""></p>
                </div>
            </div>
        </div>
        <div class="links">

            <div class="links-section">
                <h3>Links</h3>
                
                <div class="link-item">
                    <label>Sito web:</label>
                    <input type="text">
                </div>
                
                <div class="link-item">
                    <label>GitHub:</label>
                    <input type="text">
                </div>
                
                <div class="link-item">
                    <label>LinkedIn:</label>
                    <input type="text">
                </div>
            </div>
            <input class="" type="submit" value="Salva modifiche">
        </div>
    </form>
</div>