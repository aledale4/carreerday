<?php
$provinciaSelezionata = $_SESSION["user"]["prov"] ?? ""; // es: 'MI'

// elenco province organizzate per regione
$province = [
  "Abruzzo" => [
    "AQ" => "L'Aquila",
    "CH" => "Chieti",
    "PE" => "Pescara",
    "TE" => "Teramo"
  ],
  "Basilicata" => [
    "MT" => "Matera",
    "PZ" => "Potenza"
  ],
  "Calabria" => [
    "CZ" => "Catanzaro",
    "CS" => "Cosenza",
    "KR" => "Crotone",
    "RC" => "Reggio Calabria",
    "VV" => "Vibo Valentia"
  ],
  "Campania" => [
    "AV" => "Avellino",
    "BN" => "Benevento",
    "CE" => "Caserta",
    "NA" => "Napoli",
    "SA" => "Salerno"
  ],
  "Emilia-Romagna" => [
    "BO" => "Bologna",
    "FE" => "Ferrara",
    "FC" => "Forlì-Cesena",
    "MO" => "Modena",
    "PR" => "Parma",
    "PC" => "Piacenza",
    "RA" => "Ravenna",
    "RE" => "Reggio Emilia",
    "RN" => "Rimini"
  ],
  "Friuli-Venezia Giulia" => [
    "GO" => "Gorizia",
    "PN" => "Pordenone",
    "TS" => "Trieste",
    "UD" => "Udine"
  ],
  "Lazio" => [
    "FR" => "Frosinone",
    "LT" => "Latina",
    "RI" => "Rieti",
    "RM" => "Roma",
    "VT" => "Viterbo"
  ],
  "Liguria" => [
    "GE" => "Genova",
    "IM" => "Imperia",
    "SP" => "La Spezia",
    "SV" => "Savona"
  ],
  "Lombardia" => [
    "BG" => "Bergamo",
    "BS" => "Brescia",
    "CO" => "Como",
    "CR" => "Cremona",
    "LC" => "Lecco",
    "LO" => "Lodi",
    "MN" => "Mantova",
    "MI" => "Milano",
    "MB" => "Monza e della Brianza",
    "PV" => "Pavia",
    "SO" => "Sondrio",
    "VA" => "Varese"
  ],
  "Marche" => [
    "AN" => "Ancona",
    "AP" => "Ascoli Piceno",
    "FM" => "Fermo",
    "MC" => "Macerata",
    "PU" => "Pesaro e Urbino"
  ],
  "Molise" => [
    "CB" => "Campobasso",
    "IS" => "Isernia"
  ],
  "Piemonte" => [
    "AL" => "Alessandria",
    "AT" => "Asti",
    "BI" => "Biella",
    "CN" => "Cuneo",
    "NO" => "Novara",
    "TO" => "Torino",
    "VB" => "Verbano-Cusio-Ossola",
    "VC" => "Vercelli"
  ],
  "Puglia" => [
    "BA" => "Bari",
    "BT" => "Barletta-Andria-Trani",
    "BR" => "Brindisi",
    "FG" => "Foggia",
    "LE" => "Lecce",
    "TA" => "Taranto"
  ],
  "Sardegna" => [
    "CA" => "Cagliari",
    "NU" => "Nuoro",
    "OR" => "Oristano",
    "SS" => "Sassari",
    "SU" => "Sud Sardegna"
  ],
  "Sicilia" => [
    "AG" => "Agrigento",
    "CL" => "Caltanissetta",
    "CT" => "Catania",
    "EN" => "Enna",
    "ME" => "Messina",
    "PA" => "Palermo",
    "RG" => "Ragusa",
    "SR" => "Siracusa",
    "TP" => "Trapani"
  ],
  "Toscana" => [
    "AR" => "Arezzo",
    "FI" => "Firenze",
    "GR" => "Grosseto",
    "LI" => "Livorno",
    "LU" => "Lucca",
    "MS" => "Massa-Carrara",
    "PI" => "Pisa",
    "PT" => "Pistoia",
    "PO" => "Prato",
    "SI" => "Siena"
  ],
  "Trentino-Alto Adige" => [
    "BZ" => "Bolzano/Bozen",
    "TN" => "Trento"
  ],
  "Umbria" => [
    "PG" => "Perugia",
    "TR" => "Terni"
  ],
  "Valle d'Aosta" => [
    "AO" => "Aosta"
  ],
  "Veneto" => [
    "BL" => "Belluno",
    "PD" => "Padova",
    "RO" => "Rovigo",
    "TV" => "Treviso",
    "VE" => "Venezia",
    "VR" => "Verona",
    "VI" => "Vicenza"
  ]
];
?>
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
                <div class="img"><a href="index.php?pag=fotoprofilo"><?php include("defaultUser-pic.php")  ?></a></div>
                <div class="inputs">
                    <input type="hidden" name="pag" value="modifiche">
                    <p>Ragione sociale: <input type="text" name="ragionesoc" id="" placeholder="Ragione sociale" value="<?php echo $_SESSION["user"]["ragsoc"]?>" maxlength="256" required></p>
                    <p>Indirizzo:<input type="text" name="ind" id="" placeholder="Indirizzo" value="<?php echo $_SESSION["user"]["ind"]?>" maxlength="256" required ></p>
                    <p>CAP:<input type="text" name="cap" id="" placeholder="CAP" value="<?php echo $_SESSION["user"]["cap"]?>" maxlength="5" required></p>
                    <p>Località:<input type="text" Passwo name="loc" id="" placeholder="Località" value="<?php echo $_SESSION["user"]["loc"]?>" maxlength="30" required></p>
                    <p>Provincia 
                        <select name="provincia" id="provincia" required>
                            <?php foreach ($province as $regione => $lista): ?>
                                <optgroup label="<?= htmlspecialchars($regione) ?>">
                                <?php foreach ($lista as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= ($sigla === $provinciaSelezionata) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($nome) ?>
                                    </option>
                                <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>P.iva<input type="text" name="pivs" id="" placeholder="p.iva" value="<?php echo $_SESSION["user"]["piva"]?>" maxlength="11" required></p>
                </div>
            </div>
            <div class="side additional">
                <div class="inputs">
                    <p>Nome referente:<input type="text" placeholder="Nome referente" value="<?php echo $_SESSION["user"]["nomeRef"]?>" maxlength="30"></p>
                    <p>Cognome referente:<input type="text" placeholder="Cognome referente" value="<?php echo $_SESSION["user"]["cognomeRef"]?>" maxlength="30"></p>
                    <p>Username:<input type="text" placeholder="Username" value="<?php echo $_SESSION["user"]["usernameRef"]?>" maxlength="30"></p>
                    <p>Email:<input type="email" name="email" id="" value="<?php echo $_SESSION["user"]["email"]?>" maxlength="100"></p>
                    <p>Sito web:<input type="text" name="web" id="" value="<?php echo $_SESSION["user"]["web"]?>" maxlength="60" class="web-link"></p>
                </div>
            </div>
        </div>
        <div class="links">

            <div class="links-section">
                <h3>Cambia password</h3>
                
                <div class="link-item">
                    <label>Password attuale:</label>
                    <input type="password" maxlength="30">
                </div>
                
                <div class="link-item">
                    <label>Nuova password:</label>
                    <input type="password" maxlength="30">
                </div>
            </div>
            <div class="save-button">
                <input class="" type="submit" value="Salva modifiche">
                <a href="index.php?pag=logout">Log Out</a>
            </div>
        </div>
    </form>
</div>