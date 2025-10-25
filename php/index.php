<?php
    //per collegare il database e avviare la sessione
    session_start();
    $env = parse_ini_file("../.env");
    // $conn = mysqli_connect($env["DB_HOST"],$env["DB_USRNAME"],$env["DB_PSW"],$env["DB_NAME"],$env["DB_PORT"]);
    $ssl_ca = '../ca.pem';
    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, "", NULL);
    if (!mysqli_real_connect($conn, $env["DB_HOST"],$env["DB_USRNAME"],$env["DB_PSW"],$env["DB_NAME"],$env["DB_PORT"], NULL, MYSQLI_CLIENT_SSL)) {
        die("". mysqli_connect_error());
    }
    include 'phpqrcode/qrlib.php';
    // regenerate_qrcodes();
    //funzione di logout
    if(isset($_GET["pag"]) && $_GET["pag"]=="logout" && isset($_SESSION["user"])){
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
    if(isset($_GET["pag"]) && $_GET["pag"] == "download_qr" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        $id = filter_input(INPUT_GET,"id", FILTER_SANITIZE_NUMBER_INT);
        $q = "select * from adesioni where idAd = ".$id;
        $result = mysqli_query($conn, $q);
        if (mysqli_num_rows($result) == 0) exit("");
        $row = mysqli_fetch_array($result);
        $idAz = $row["rAz"];
        if ($idAz != $_SESSION["user"]["idAz"]) exit("");
        $file = "../static/qrcodes/".$id.".png";
        header('Content-Type: application/download');
        header('Content-Disposition: attachment; filename="qr.png"');
        header("Content-Length: " . filesize($file));
        $fp = fopen($file, "r");
        fpassthru($fp);
        fclose($fp);
        exit();
    }
    if((isset($_GET["pag"]) && $_GET["pag"] == "register" && $_SESSION["user-type"] == 1 && !isset($_SESSION["user"])) || (!isset($_SESSION["user-type"]) && !isset($_SESSION["user"]))){
        $_SESSION["user-type"] = 2;
    }
    if(isset($_GET["usertype"]) && !isset($_SESSION["user"]) && (!isset($_GET["pag"]) || ($_GET["pag"] == "login" || $_GET["pag"] == "register"))){
        $user_type = filter_input(INPUT_GET,"usertype", FILTER_VALIDATE_INT);
        if($user_type && $user_type >= 1 && $user_type <=3){
            $_SESSION["user-type"] = $user_type;
        }else{
            $_SESSION["user-type"] = 2;
        }
    }

    //funzione di registrazione studente
    if(isset($_POST["pag"]) && $_POST["pag"]=="register" && !isset($_SESSION["user"])){
        //controllo username
        $required = ["username","email","nome","cognome","password","password2"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                header("Location: index.php?pag=register&error=3");
                exit();
            }
        } 
        $username=mysqli_real_escape_string($conn, $_POST["username"]);
        $q ="select * from studenti where usernameStu='".$username."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica dell'username");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //username già usato 
            header("Location: index.php?pag=register&error=0");
            exit();
        }
        //controllo email
        $email=mysqli_real_escape_string($conn, $_POST["email"]);
        $q ="select * from studenti where emailStu='".$email."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica della mail");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //email già usata
            header("Location: index.php?pag=register&error=1");
            exit();
        }
        if($_POST["password"]!=$_POST["password2"]){
            //password non corrispondenti
            header("Location: index.php?pag=register&error=2");
            exit();
        }
        //username e email disponibili
        $nome= mysqli_real_escape_string($conn, $_POST["nome"]);
        $cognome= mysqli_real_escape_string($conn, $_POST["cognome"]);
        $password= password_hash($_POST["password"],PASSWORD_DEFAULT);
        $data= date("Y-m-d");
        $q ="insert into studenti (nomeStu,cognomeStu,usernameStu,passwordStu,emailStu,lastPwdStu,lastLoginStu) values('".$nome."','".$cognome."','".$username."','".$password."','".$email."','".$data."','".$data."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione | ".$q." | ".mysqli_error($conn));
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=login");
        exit();
    }

    //funzione di login studente
    if(isset($_POST["pag"]) && $_POST["pag"]=="login" && !isset($_SESSION["user"])){
        if (!isset($_POST["email"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");
        $email=mysqli_real_escape_string($conn, $_POST["email"]);
        $q= "select * from studenti where emailStu='".$email."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(password_verify($_POST["password"],$riga["passwordStu"])){
                //login effettuato con successo
                $_SESSION["user"]=$riga;
                $_SESSION["user-type"] = 2;
                session_regenerate_id();
                $date=date("Y-m-d");
                $q="update studenti set lastLoginStu='".$date."' where idStu=".$_SESSION["user"]["idStu"];
                $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                header("Location: index.php");
                exit();
            }
            else{
                //password errata
                header("Location: index.php?pag=login&error=0");
            }
        }
        else{
            //username errato
            header("Location: index.php?pag=login&error=1");
        }
    }

    //funzione di login aziende
    if(isset($_POST["pag"]) && $_POST["pag"]=="login_soc" && !isset($_SESSION["user"])){
        if (!isset($_POST["email"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");
        $email=mysqli_real_escape_string($conn, $_POST["email"]);
        $q= "select * from aziende where email='".$email."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(password_verify($_POST["password"],$riga["passwordRef"])){
                //login effettuato con successo
                $_SESSION["user"]=$riga;
                $_SESSION["user-type"] = 3;
                session_regenerate_id();
                $date=date("Y-m-d");
                $q="update aziende set lastLoginRef='".$date."' where idAz=".$_SESSION["user"]["idAz"];
                $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                header("Location: index.php");
                exit();
            }
            else{
                //password errata
                header("Location: index.php?pag=login&error=0");
            }
        }
        else{
            //username errato
            header("Location: index.php?pag=login&error=1");
        }
    }

    //funzione di login admin
    if(isset($_POST["pag"]) && $_POST["pag"]=="login_admin" && !isset($_SESSION["user"])){
        if (!isset($_POST["username"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");
        $username=mysqli_real_escape_string($conn, $_POST["username"]);
        $q= "select * from admins where usernameUt='".$username."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(password_verify($_POST["password"],$riga["passwordUt"])){
                //login effettuato con successo
                $_SESSION["user"]=$riga;
                $_SESSION["user-type"] = 1;
                session_regenerate_id();
                $date=date("Y-m-d");
                $q="update admins set lastLoginUt='".$date."' where idUt=".$_SESSION["user"]["idUt"];
                $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                header("Location: index.php");
                exit();
            }
            else{
                //password errata
                header("Location: index.php?pag=login&error=0");
            }
        }
        else{
            //username errato
            header("Location: index.php?pag=login&error=1");
        }
    }

    //funzione di registrazione aziende
    if(isset($_POST["pag"]) && $_POST["pag"]=="register_soc" && !isset($_SESSION["user"])){
        //controllo username
        $required = ["ragsoc","piva","indirizzo","cap","loc","prov","username","email","nomeRef","cognomeRef","password","password2"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                header("Location: index.php?pag=register&error=3");
                exit();
            }
        }
        $username=mysqli_real_escape_string($conn, $_POST["username"]);
        $piva=mysqli_real_escape_string($conn, $_POST["piva"]);

        $q ="select * from aziende where usernameRef='".$username."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica dell'username");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //username già usato 
            header("Location: index.php?pag=register&error=0");
            exit();
        }
        $q ="select * from aziende where piva='".$piva."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica della p.iva");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //p.iva già usata
            header("Location: index.php?pag=register&error=3");
            exit();
        }
        //controllo email
        $email=mysqli_real_escape_string($conn, $_POST["email"]);
        $q ="select * from aziende where email='".$email."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica della mail");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //email già usata
            header("Location: index.php?pag=register&error=1");
            exit();
        }
        if($_POST["password"]!=$_POST["password2"]){
            //password non corrispondenti
            header("Location: index.php?pag=register&error=2");
            exit();
        }
        //username e email disponibili
        $ragsoc= mysqli_real_escape_string($conn, $_POST["ragsoc"]);
        $indirizzo= mysqli_real_escape_string($conn, $_POST["indirizzo"]);
        $cap= mysqli_real_escape_string($conn, $_POST["cap"]);
        $loc= mysqli_real_escape_string($conn, $_POST["loc"]);
        $prov= mysqli_real_escape_string($conn, $_POST["prov"]);
        $nome= mysqli_real_escape_string($conn, $_POST["nomeRef"]);
        $cognome= mysqli_real_escape_string($conn, $_POST["cognomeRef"]);
        $password= password_hash($_POST["password"],PASSWORD_DEFAULT);
        $data= date("Y-m-d");
        $q ="insert into aziende (ragsoc,ind,cap,loc,prov,piva,email,nomeRef,cognomeRef,usernameRef,passwordRef,lastPwdRef,lastLoginRef) values('".$ragsoc."','".$indirizzo."','".$cap."','".$loc."','".$prov."','".$piva."','".$email."','".$nome."','".$cognome."','".$username."','".$password."','".$data."','".$data."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione | ".$q." | ".mysqli_error($conn));
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=login");
        exit();
    }
    // funzione di reset password
    if(isset($_POST["pag"]) && $_POST["pag"]=="request_reset_pwd" && !isset($_SESSION["user"])){
        $q="";
        if($_SESSION["user-type"]== 2){
            $q= "select * from studenti where emailstu='".$_POST["email"]."'";
        }
        if($_SESSION["user-type"]== 3){
            $q= "select * from aziende where emailref='".$_POST["email"]."'";
        }
        $ris= mysqli_query($conn, $q)or die("queri don't work");
        $num = mysqli_num_rows($ris);
        if($num == 1){
            $riga = mysqli_fetch_assoc($ris);
            $token_random = random_ascii_string(32);
            $pwd_random = random_ascii_string(32);
            if($_SESSION["user-type"] == 2){
                $q="update studenti set passwordstu = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idStu='" . $riga["idStu"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('" . $riga["idStu"]. "' , '".$token_random."' , '" .$_SESSION["user-type"]."','" .date('Y-m-d')."')";
            }
            if($_SESSION["user-type"] == 3){
                $q="update aziende set passwordref = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idAz='" . $riga["idAz"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('".$riga["idAz"]."' , '".$token_random."' , '" .$_SESSION["user-type"]."','" .date('Y-m-d')."')";
            }
            echo $pwd_random;
            mysqli_query($conn,$q) or die("errore cambio password");
            mysqli_query($conn,$q2) or die("errore cambio token       " . mysqli_error($conn));
            
            $mitt="morganello76@gmail.com"; //mittente
            $ogg="Reset password Carreday";
            $mess="Clicca su questo link per resettare a tua password : \nreset_pwd.php?token=" .$token . "\n Inserisci questa password provvisoria nel campo: Password provvisoria. \n" . $pwd_pro ; // link da inserire
            $header="From: ".$mitt."\r\nReply-To:".$mitt."\r\nContent-type: text/html; charset=utf-8\r\n";
            if(mail($_POST["email"], $ogg, $mess, $header)){ // destinatario , oggetto , messaggio , invio
                exit("tutto apposto");
                header("Location:email_inviata.php");
                
            }else{
                exit("email non inviata: parametri sbagliati");
                //email non inviata
            }
        }else{
            exit("email non inviata: problemi con il numero di utenti");
            // ce piu di un utente
        } 

    }
    if(isset($_POST["pag"]) && $_POST["pag"] == "reset_pwd" && !isset($_GET["pag"]) &&  !isset($_SESSION["user"])){
        
        $q= "select * from token where token='".$_POST["token"]."'";

        $ris= mysqli_query($conn, $q)or die("token inesistente");
        $num = mysqli_num_rows($ris);

        $riga=mysqli_fetch_assoc($ris);

        if($num == 1){
            if(days_counter($riga["created"]) <=2){ //per vedere se sono passati piu di 2 giorni
                $tipopwd="";
                if($_SESSION["user-type"] == 2){ // per vedere che tipo di utente è
                    $q="select * from studenti where idStu = '".$riga["rUser"]."'";
                    $tipo_pwd="passwordStu";
                }
                if($_SESSION["user-type"] == 3){
                    $q="select * from aziende where idAz='".$riga["rUser"]."'";
                    $tipo_pwd="passwordRef";
                }
                $risut = mysqli_query($conn, $q)or die("utente inesistente ");
                $rigaut=mysqli_fetch_assoc($risut);
                echo $_SESSION["user-type" ] . " \ ";
                echo $riga["rUser"] .  " \ ";
                echo $q . " \ ";
                echo $rigaut . " \ ";
                $i = password_verify($_POST["password_temp"],$rigaut[$tipo_pwd]);
                echo $_POST["password_temp"] . " \ ";
                echo $rigaut[$tipo_pwd] . " \ ";
                //echo $i ;
                if(password_verify($_POST["password_temp"],$rigaut[$tipo_pwd])){ // per vedere se le password temporanee coincidono
                    echo "pwd temp giuste";
                    if($_POST["password1"]==$_POST["password2"]){ // per vedere se le password nuove coincidono
                        echo "entra pwd uguali \ ";
                        if($_SESSION["user-type"] == 2){
                            $qpass="update studenti set passwordstu='" .password_hash($_POST["password1"],PASSWORD_DEFAULT). "'  , lastpwdstu='".date('Y-m-d')."' where idstu='".$riga["rUser"]."'";
                            echo"entra usertype 2 \ ";
                        }
                        if($_SESSION["user-type"] == 3){
                            $qpass="update aziende set passwordref='" .password_hash($_POST["password1"],PASSWORD_DEFAULT). "' , lastpwdref='".date('Y-m-d')."' where idaz='".$riga["rUser"]."'";
                        }
                        echo $qpass . " \ ";
                        $qtoken="delete from token where token='" .$riga["token"]. "'";
                        echo $qtoken ." \ ";
                        
                        mysqli_query($conn, $qpass)or die("errore updating");
                        mysqli_query($conn, $qtoken)or die("errore delete token");
                        echo $qpass;
                        //header("Location:index.php?pag=login");
                        exit();
                    }else{
                        //password nuove diverse
                        exit("password nuove diverse");
                    }
                }else{
                    exit("password temporanee diverse");
                    //password temporanee diverse
                }
            }else{
                exit("sono passati troppi giorni sulla richiesta");
                $qtoken="delete from token where token='" .$riga["token"]. "'";
                mysqli_query($conn, $qtoken)or die("errore delete token");
               //"sono passati troppi giorni"
            }
        }else if($num==0){
            exit("token non trovato");
            //"piu utenti"
        }
        else{
            exit("numero token anomalo");
        }
    }

    // crea un stringa casuale
    function random_ascii_string($length) {
                return substr(bin2hex(random_bytes($length)), 0, $length);
    }

    //funzione per l'aggiornamento della password
    if(isset($_POST["pag"]) && $_POST["pag"]=="pwdUpdate2" && isset($_POST["newpwd"]) && isset($_SESSION["user"])){
        switch($_SESSION["user-type"]){
            case 1:
                $tabella="admins";
                $campo1="passwordUt";
                $campo2="lastPwdUt";
                $campo3="idUt";
            case 2:
                $tabella="studenti";
                $campo1="passwordStu";
                $campo2="lastPwdStu";
                $campo3="idStu";
                break;
            case 3:
                $tabella="aziende";
                $campo1="passwordAz";
                $campo2="lastPwdAz";
                $campo3="idAz";
                break;
            default:
                echo "Si è verificato un errore durante il controllo dell'account";
                exit();
        }
        $data = date("%Y-%m-%d");
        $q="update ".$tabella." set ".$campo1." = ".password_hash($_POST["newpwd"]).", ".$campo2." = ".$data." where ".$campo3." = '".$_SESSION["user"][$campo3]."'";
        $ris=mysqli_query($conn, $q)or die("Errore nell'aggiornamento della password");
        $_SESSION["user"][$campo1]=password_hash($_POST["newpwd"]);
        header("Location: index.php");
    }

    //funzione per controllare se la password è "scaduta"
    //restituisce un valore booleano:
    //- true se la password è scaduta
    //- false se la password è valida
    function pwd_expired(){
        switch($_SESSION["user-type"]){
            case 1:
                $tabella="admins";
                $id=$_SESSION["user"]["idUt"];
                $campo1="idUt";
                break;
            case 2:
                $tabella="studenti";
                $id=$_SESSION["user"]["idStu"];
                $campo1="idStu";
                break;
            case 3:
                $tabella="aziende";
                $id=$_SESSION["user"]["idAz"];
                $campo1="idAz";
                break;
            default:
                echo "Si è verificato un errore durante il controllo dell'account";
                exit();
        }
    	global $conn;
        $q="select * from `".$tabella."` where ".$campo1."='".$id."'";
        $ris= mysqli_query($conn, $q)or die("errore durante il controllo password | ".$q.mysqli_error($conn));
        $num= mysqli_num_rows($ris);
        
        if($num==1){
            try{
        	$today= new DateTime(date('Y-m-d'));
            $date=mysqli_fetch_assoc($ris);
            $date2= new DateTime($date["data_formattata"]);
            $intervallo = $date2->diff($today);
            // echo $intervallo->format("%a giorni");
            if($intervallo > 183){
                return true;
            }
            else{
                return false;
            }
            }catch(Exception $e){}
        }
        else if($num>1){
            exit("errore duante la verifica della password più di un utente trovato");
        }
        else if($num==0){
            exit("utente non trovato");
        }
        else{
            exit("errore nella funzione di verifica della password");
        }
    }

    // funzione per vedere le posizioni libere di un azienda deve passare l'id azienda returna un array con tutte le posizioni aperte
    function posizioni_libere($idaz){
        $pos = [];
        global $conn;
        $q="select * from posizioni where rAz='" .$idaz. "'";
        $ris = mysqli_query($conn,$q);
        while($row = mysqli_fetch_assoc($ris)){
            $pos[] = $row;
        }
       return $pos;
    }

    //funzione che conta i giorni da una data fornita in input con formato "Y-m-d", restituisce il numero di giorni
    function days_counter($value){
        $today= new DateTime(date("Y-m-d"));
        $date= new DateTime($value);
        $days= $today->diff($date);
        $days->format("%a giorni");
        return $days;
    }

    function regenerate_qrcodes(){
        $q = "select * from adesioni";
        global $conn;
        global $env;
        $ris = mysqli_query($conn,$q);
        while ($adesione = mysqli_fetch_assoc($ris)){
        $id_qr = $adesione["idAd"];
        QRcode::png($env['BASE_URL']."/php/index.php?pag=adesione&id=".$id_qr, '../static/qrcodes/'.$id_qr.'.png', 'L', 16, 2);
        }
    }

    if(isset($_POST["pag"]) && $_POST["pag"]=="new_event" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 1){
        $required = ["nome","descrizione","date","start_time","end_time","pos"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                header("Location: index.php?pag=new_event&error=1");
                exit();
            }
        }
        $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
        $desc = mysqli_real_escape_string($conn, $_POST["descrizione"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $start_time = mysqli_real_escape_string($conn, $_POST["start_time"]);
        $end_time = mysqli_real_escape_string($conn, $_POST["end_time"]);
        $pos = mysqli_real_escape_string($conn, $_POST["pos"]);
        $q ="insert into career_day (nameCd,dateCd,fromCd,toCd,locationCd,descCd) values('".$nome."','".$date."','".$start_time."','".$end_time."','".$pos."','".$desc."')";
        $result = mysqli_query($conn, $q) or die("errore nella query");
        $id = mysqli_insert_id($conn);
        $q = "select * from aziende";
        $r = mysqli_query($conn, $q);
        while ($row = mysqli_fetch_assoc($r)) {
           if (isset($_POST[$row["idAz"]]) && $_POST[$row["idAz"]] == "on"){
                $adQ = "insert into adesioni (rAz,rCd) values ('".$row["idAz"]."','".$id."')";
                $ad = mysqli_query($conn, $adQ) or die("errore nella query");
                $id_qr = mysqli_insert_id($conn);
                QRcode::png($env['BASE_URL']."/php/index.php?pag=adesione&id=".$id_qr, '../static/qrcodes/'.$id_qr.'.png', 'L', 16, 2);
           }
        }

        header("Location: index.php");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="edit_event" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 1){
        $required = ["nome","descrizione","date","start_time","end_time","pos"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                header("Location: index.php?pag=edit_event&error=1");
                exit();
            }
        }
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
        $desc = mysqli_real_escape_string($conn, $_POST["descrizione"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $start_time = mysqli_real_escape_string($conn, $_POST["start_time"]);
        $end_time = mysqli_real_escape_string($conn, $_POST["end_time"]);
        $pos = mysqli_real_escape_string($conn, $_POST["pos"]);
        $q ="update career_day set nameCd='".$nome."',dateCd='".$date."',fromCd='".$start_time."',toCd='".$end_time."',locationCd='".$pos."',descCd='".$desc."' where idCd=".$id;
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=event&id=".$id);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="prenotazione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 2){
        if(!isset($_POST["id"])) {
            header("Location: index.php?pag=adesione&error=1");
            exit();
        }
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        $date = new DateTime("now", new DateTimeZone('Europe/Rome') );
        $q ="insert into prenotazioni (rAd,rStu,datapren) values('".$id."','".$_SESSION["user"]["idStu"]."','".($date->format('Y-m-d H:i:s'))."')";
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=adesione&id=".$id);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="update_prenotazione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        if(!isset($_POST["id"])) {
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        $completed = filter_input(INPUT_POST,"completed", FILTER_SANITIZE_STRING);
        $q = "select * from prenotazioni where idPren = ".$id;
        $prenotazioneQ = mysqli_query($conn, $q) or die();
        if (mysqli_num_rows($prenotazioneQ) == 0) exit();
        $prenotazione = mysqli_fetch_assoc($prenotazioneQ);
        $qIdAd = "select * from adesioni where idAd = ".$prenotazione["rAd"];
        $result = mysqli_query($conn, $qIdAd) or die();
        if (mysqli_num_rows($result) == 0) exit();
        $adesione = mysqli_fetch_assoc($result);

        if ($adesione["rAz"] != $_SESSION["user"]["idAz"]) die();
        if ($completed && $completed == "on"){
            $q ="update prenotazioni set completed = 1 where idPren = ".$id;
        }else {
            $q ="update prenotazioni set completed = 0 where idPren = ".$id;
        }
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=colloqui");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="remove_adesione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 1){
        $id = filter_input(INPUT_POST,"idAd", FILTER_SANITIZE_NUMBER_INT);
        $idEvento = filter_input(INPUT_POST,"idEvento", FILTER_SANITIZE_NUMBER_INT);
        if(!$id || !$idEvento) exit();
        $q = "delete from adesioni where idAd = ".$id;
        $result = mysqli_query($conn, $q) or die();
        $q = "delete from prenotazioni where rAd = ".$id;
        $result = mysqli_query($conn, $q) or die();
        header("Location: index.php?pag=edit_event&id=".$idEvento);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="add_adesione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 1){
        $id = filter_input(INPUT_POST,"idAz", FILTER_SANITIZE_NUMBER_INT);
        $idEvento = filter_input(INPUT_POST,"idEvento", FILTER_SANITIZE_NUMBER_INT);
        if(!$id || !$idEvento) exit();
        $q = "insert into adesioni (rAz,rCd) values ('".$id."','".$idEvento."')";
        $result = mysqli_query($conn, $q) or die();
        $id_qr = mysqli_insert_id($conn);
        QRcode::png($env['BASE_URL']."/php/index.php?pag=adesione&id=".$id_qr, '../static/qrcodes/'.$id_qr.'.png', 'L', 16, 2);
        header("Location: index.php?pag=edit_event&id=".$idEvento);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="delete_position" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        $id = filter_input(INPUT_POST,"idPos", FILTER_SANITIZE_NUMBER_INT);
        if(!$id) exit();
        $q = "delete from posizioni where idPos = ".$id;
        $result = mysqli_query($conn, $q) or die();
        header("Location: index.php?pag=posizioni");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="add_position" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        $nome = htmlspecialchars($_POST["nomePos"]);
        $desc = htmlspecialchars($_POST["descPos"]);
        if(!$nome || !$desc) exit();
        $q = "insert into posizioni (rAz,nomePos,descrizionePos) values (".$_SESSION["user"]["idAz"].",'".$nome."','".$desc."')";
        $result = mysqli_query($conn, $q) or die();
        header("Location: index.php?pag=posizioni");
    }
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../static/logo-careerday.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/login_register.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="stylesheet" href="../css/event.css">
    <link rel="stylesheet" href="../css/new_edit_event.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="stylesheet" href="../css/company-home.css">
    <link rel="stylesheet" href="../css/colloqui.css">
    <link rel="stylesheet" href="../css/posizioni.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add,arrow_back_ios_new,delete_forever,edit,location_on,logout" />
    <title>Career Day</title>
</head>
<body>
    <?php
    if(isset($_SESSION["user"])){
        if(pwd_expired() && $_GET["pag"]!="pwdUpdate"){
            header("Location: index.php?pag=pwdUpdate");
        }
        if($_GET["pag"]=="pwdUpdate"){
            include("pwdUpdate.php");
        }
        if($_GET["pag"] == "settings" ){
            include("settings.php");
        }else if($_GET["pag"] == "event"){
            include("event.php");
        }else if ($_GET["pag"] == "new_event" && $_SESSION["user-type"] == 1){
            include ("new_event.php");
        }else if ($_GET["pag"] == "edit_event" && $_SESSION["user-type"] == 1){
            include ("edit-event.php");
        }else if ($_GET["pag"] == "adesione" && $_SESSION["user-type"] == 2){
            include ("adesione.php");
        }else if ($_GET["pag"] == "colloqui" && $_SESSION["user-type"] == 3){
            include ("colloqui.php");
        }else if ($_GET["pag"] == "posizioni" && $_SESSION["user-type"] == 3){
            include ("posizioni.php");
        }else {
            switch($_SESSION["user-type"]){
                case 1:
                    include("admin-home.php");
                    break;
                case 2:
                    include("home.php");
                    break;
                case 3:
                    include("company-home.php");
                    break;
            }
        }
    }
    else if(isset($_GET["pag"])){
        if($_GET["pag"] == "login"){
            include("login.php");
        }else if($_GET["pag"] == "register"){
            include("register.php");
        }else if($_GET["pag"] == "request_reset_pwd"){
            include("request_reset_pwd.php");
        }else{
            include("login.php");
        }
    }else{
        include("login.php");
    }
    ?>
</body>
</html>

<?php
    mysqli_close($conn);
?>