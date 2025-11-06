<?php
    // per prendere il fuso orario giusto
    date_default_timezone_set('Europe/Rome');
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

    if(isset($_GET["pag"]) && $_GET["pag"]=="eliminaut" && isset($_SESSION["user"])){
        if (elimina_utente($_SESSION["user"],$_SESSION["user-type"])){
            session_unset();
            session_destroy();
            include("delete_account_ok.php");
            exit();
        }else{
            header('Location:index.php?pag=request_elut&error=1');
        }
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
        $username=trim(mysqli_real_escape_string($conn, $_POST["username"]));
        $q ="select * from studenti where usernameStu='".$username."'";
        $ris = mysqli_query($conn, $q)or die("errore durante la verifica dell'username");
        $num = mysqli_num_rows($ris);
        if($num>0){
            //username già usato 
            header("Location: index.php?pag=register&error=0");
            exit();
        }
        //controllo email
        $email=trim(mysqli_real_escape_string($conn, $_POST["email"]));
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
        $nome= trim(mysqli_real_escape_string($conn, $_POST["nome"]));
        $cognome= trim(mysqli_real_escape_string($conn, $_POST["cognome"]));
        $password= password_hash(trim($_POST["password"]),PASSWORD_DEFAULT);
        $data= date("Y-m-d");
        $q ="insert into studenti (nomeStu,cognomeStu,usernameStu,passwordStu,emailStu,lastPwdStu,lastLoginStu) values('".$nome."','".$cognome."','".$username."','".$password."','".$email."','".$data."','".$data."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione | ".$q." | ".mysqli_error($conn));
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=register&success=1");
        exit();
    }

    //funzione di login studente
    if(isset($_POST["pag"]) && $_POST["pag"]=="login" && !isset($_SESSION["user"])){
        if (!isset($_POST["email"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");

        $email=trim(mysqli_real_escape_string($conn, $_POST["email"]));
        $q= "select * from studenti where emailStu='".$email."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        $riga = mysqli_fetch_assoc($ris);
        if($num==1){
            if(verify_pwd_res($riga["idStu"] , 2)){
                if(password_verify(trim($_POST["password"]),$riga["passwordStu"])){
                //login effettuato con successo
                    $_SESSION["user"]=$riga;
                    $_SESSION["user-type"] = 2;
                    session_regenerate_id();
                    $date=date("Y-m-d");
                    $q="update studenti set lastLoginStu='".$date."' where idStu=".$_SESSION["user"]["idStu"];
                    $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                    if (isset($_SESSION["next-page"]) && $_SESSION["next-page"] != ""){
                        header("Location: ".$_SESSION["next-page"]);
                    }else{
                        header("Location: index.php");
                        exit();
                    }
                }else{
                    //password errata
                    header("Location: index.php?pag=login&error=0");
                    exit();
                }
            }
            else{
                $q="select * from token where ruser=".$riga["idStu"]. " and user_type = 2 order by idTok desc";
                $ris=mysqli_query($conn,$q);
                $riga=mysqli_fetch_assoc($ris);
                header("Location:index.php?pag=reset_pwd&token=" . $riga["token"]);
                exit();
            }
        }
        else{
            //username errato
            header("Location: index.php?pag=login&error=1");
            exit();
        }
    }

    //funzione di login aziende
    if(isset($_POST["pag"]) && $_POST["pag"]=="login_soc" && !isset($_SESSION["user"])){
        if (!isset($_POST["email"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");
        $email=trim(mysqli_real_escape_string($conn, $_POST["email"]));
        $q= "select * from aziende where email='".$email."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(verify_pwd_res($riga["idAz"], 3)){
                if(password_verify(trim($_POST["password"]),$riga["passwordRef"])){
                //login effettuato con successo
                    $_SESSION["user"]=$riga;
                    $_SESSION["user-type"] = 3;
                    session_regenerate_id();
                    $date=date("Y-m-d");
                    $q="update aziende set lastLoginRef='".$date."' where idAz=".$_SESSION["user"]["idAz"];
                    $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                    if (isset($_SESSION["next-page"]) && $_SESSION["next-page"] != ""){
                        header("Location: ".$_SESSION["next-page"]);
                    }else{
                        header("Location: index.php");
                    }
                    exit();
                }else{
                    //password errata
                    header("Location: index.php?pag=login&error=0");
                }
            }
            else{
                $q="select * from token where ruser=".$riga["idAz"]. " and user_type = 3 order by idTok desc";
                $ris=mysqli_query($conn,$q);
                $riga=mysqli_fetch_assoc($ris);
                header("Location:index.php?pag=reset_pwd&token=" . $riga["token"]);
                exit();
            }
        }
        else{
            //username errato
            header("Location: index.php?pag=login&error=1");
            exit();
        }
    }

    //funzione di login admin
    if(isset($_POST["pag"]) && $_POST["pag"]=="login_admin" && !isset($_SESSION["user"])){
        if (!isset($_POST["username"]) or !isset($_POST["password"])) header("Location: index.php?pag=login&error=2");
        $username=trim(mysqli_real_escape_string($conn, $_POST["username"]));
        $q= "select * from admins where usernameUt='".$username."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'email");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(password_verify(trim($_POST["password"]),$riga["passwordUt"])){
                //login effettuato con successo
                $_SESSION["user"]=$riga;
                $_SESSION["user-type"] = 1;
                session_regenerate_id();
                $date=date("Y-m-d");
                $q="update admins set lastLoginUt='".$date."' where idUt=".$_SESSION["user"]["idUt"];
                $ris=mysqli_query($conn, $q)or die("errore durante il salvataggio della data");
                if (isset($_SESSION["next-page"]) && $_SESSION["next-page"] != ""){
                    header("Location: ".$_SESSION["next-page"]);
                }else{
                    header("Location: index.php");
                }
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
        $username=trim(mysqli_real_escape_string($conn, $_POST["username"]));
        $piva=trim(mysqli_real_escape_string($conn, $_POST["piva"]));

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
        $email=trim(mysqli_real_escape_string($conn, $_POST["email"]));
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
        $ragsoc= trim(mysqli_real_escape_string($conn, $_POST["ragsoc"]));
        $indirizzo= trim(mysqli_real_escape_string($conn, $_POST["indirizzo"]));
        $cap= trim(mysqli_real_escape_string($conn, $_POST["cap"]));
        $loc= trim(mysqli_real_escape_string($conn, $_POST["loc"]));
        $prov= trim(mysqli_real_escape_string($conn, $_POST["prov"]));
        $nome=trim( mysqli_real_escape_string($conn, $_POST["nomeRef"]));
        $cognome= trim(mysqli_real_escape_string($conn, $_POST["cognomeRef"]));
        $password= password_hash(trim($_POST["password"]),PASSWORD_DEFAULT);
        $data= date("Y-m-d");
        $q ="insert into aziende (ragsoc,ind,cap,loc,prov,piva,email,nomeRef,cognomeRef,usernameRef,passwordRef,lastPwdRef,lastLoginRef) values('".$ragsoc."','".$indirizzo."','".$cap."','".$loc."','".$prov."','".$piva."','".$email."','".$nome."','".$cognome."','".$username."','".$password."','".$data."','".$data."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione | ".$q." | ".mysqli_error($conn));
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=register&success=1");
        exit();
    }



    // funzione di reset password da login
    if(isset($_POST["pag"]) && $_POST["pag"]=="request_reset_pwd" && !isset($_SESSION["user"])){
        $q="";
        if($_SESSION["user-type"]== 2){
            $q= "select * from studenti where emailstu='".trim(mysqli_real_escape_string($conn,$_POST["email"]))."'";
        }
        if($_SESSION["user-type"]== 3){
            $q= "select * from aziende where email='".trim(mysqli_real_escape_string($conn,$_POST["email"]))."'";
        }
        $ris= mysqli_query($conn, $q)or die("queri don't work");
        $num = mysqli_num_rows($ris);
        if($num == 1){
            $riga = mysqli_fetch_assoc($ris);
            $token_random = random_ascii_string(32);
            $pwd_random = random_ascii_string(32);
            if($_SESSION["user-type"] == 2){
                $q="update studenti set passwordstu = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idStu='" . $riga["idStu"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('" . $riga["idStu"]. "' , '".$token_random."' , '" .$_SESSION["user-type"]."','" .date('Y-m-d H:i:s')."')";
            }
            if($_SESSION["user-type"] == 3){
                $q="update aziende set passwordRef = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idAz='" . $riga["idAz"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('".$riga["idAz"]."' , '".$token_random."' , '" .$_SESSION["user-type"]."','" .date('Y-m-d H:i:s')."')";
                //echo $q2;
            }
            mysqli_query($conn,$q) or die("errore cambio password");
            mysqli_query($conn,$q2) or die("errore cambio token: " . mysqli_error($conn));
            
            $mitt="no-reply@savoiacareerday.it"; //mittente
            $ogg="Reset password Carreday";
            // $mess="Clicca su questo link per resettare la tua password : \nhttps://careerday.altervista.org/php/index.php?pag=reset_pwd&token=" .$token_random . "\n Inserisci questa password provvisoria nel campo: " . $pwd_random ; // link da inserire
            include 'reset_pwd_email.php';
            $mess = generateResetPasswordEmail($pwd_random, $token_random);
            $headers = array(
                'From' => $mitt,
                'Reply-To' => $mitt,
                'X-Mailer' => 'PHP/' . phpversion(),
                'Content-Type' => 'text/html; charset=utf-8'
            );
            if(mail($_POST["email"], "Reset password Carreday", $mess,$headers)){ // destinatario , oggetto , messaggio , invio
                header("Location: index.php?pag=request_reset_pwd&success=1");
                // exit("Email inviata con successo");
            }else{
                header('Location:index.php?pag=request_reset_pwd&error=1'); 
                // header('Location:index.php?pag=errori_reset_pwd&err=1&pwdtemp=' .$pwd_random); 
                //echo'<p class="p_error">L&acuteemail non è stata inviata correttamente.</p>';
                //email non inviata
            }
        }else if ($num == 0 ){
            header('Location:index.php?pag=request_reset_pwd&error=2');
            //echo'<p class="p_error">L&acuteemail inserita non è stata registrata.</p>';
            // ce piu di un utente
        } 

    }





    if(isset($_POST["pag"]) && $_POST["pag"] == "reset_pwd" && !isset($_GET["pag"]) &&  !isset($_SESSION["user"])){
        $token = trim(mysqli_real_escape_string($conn, $_POST["token"]));
        $q= "select * from token where token='".$token."' order by idTok desc";

        $ris= mysqli_query($conn, $q)or die();
        $num = mysqli_num_rows($ris);

        $riga=mysqli_fetch_assoc($ris);
        echo $_POST["token"] . " \ ";
        echo $q;
        if($num == 1){
            if(days_counter($riga["created"]) <=2){ //per vedere se sono passati piu di 2 giorni
                $tipopwd="";
                if($riga["user_type"] == 2){ // per vedere che tipo di utente è
                    $q="select * from studenti where idStu = '".$riga["rUser"]."'";
                    $tipo_pwd="passwordStu";
                }
                if($riga["user_type"] == 3){
                    $q="select * from aziende where idAz='".$riga["rUser"]."'";
                    $tipo_pwd="passwordRef";
                }
                $risut = mysqli_query($conn, $q)or die("utente inesistente ");
                $rigaut=mysqli_fetch_assoc($risut);
                //echo " \ " . $_SESSION["user-type" ] . " \ ";
                //echo $riga["rUser"] .  " \ ";
                //echo $q . " \ ";
                //echo $rigaut . " \ ";
                $i = password_verify($_POST["password_temp"],$rigaut[$tipo_pwd]);
                //echo "pass temp: " . $_POST["password_temp"] . " \ ";
                //echo "tipo pwd:" .$rigaut[$tipo_pwd] . " \ ";
                //echo $i ;
                if(password_verify($_POST["password_temp"],$rigaut[$tipo_pwd])){ // per vedere se le password temporanee coincidono
                    //echo "pwd temp giuste";
                    if($_POST["password1"]==$_POST["password2"]){ // per vedere se le password nuove coincidono
                        //echo "entra pwd uguali \ ";
                        if($riga["user_type"] == 2){
                            $qpass="update studenti set passwordstu='" .password_hash($_POST["password1"],PASSWORD_DEFAULT). "'  , lastpwdstu='".date('Y-m-d H:i:s')."' where idstu='".$riga["rUser"]."'";
                        }
                        if($riga["user_type"] == 3){
                            $qpass="update aziende set passwordRef='" .password_hash($_POST["password1"],PASSWORD_DEFAULT). "' , lastpwdref='".date('Y-m-d H:i:s')."' where idaz='".$riga["rUser"]."'";
                        }
                        //$_GET["pwdtemp"] = $qpass;
                        $qtok="select * from token where ruser='" . $riga["rUser"] . "' and user_type='" .$riga["user-type"] . "'";
                        $ristoken=mysqli_query($conn,$qtok)or die ("morto");
                        $num=mysqli_num_rows($ristoken);
                        $qdeltoken="delete from token where ruser='" .$riga["rUser"]. "'";
                        mysqli_query($conn, $qdeltoken)or die("errore delete token");
                        //echo $qtoken ." \ ";
                        mysqli_query($conn, $qpass)or die("errore updating");

                        //echo $qpass;
                        //echo "password cambiata con successo";
                        header('Location:index.php?pag=reset_pwd&success=1&token=' . $token);
                        exit();
                    }else{
                        //password nuove diverse
                        header('Location:index.php?pag=reset_pwd&error=3&token=' . $token);
                        //echo'<p class="p_error">Le password inserite sono diverse.</p>';
                    }
                }else{
                    header('Location:index.php?pag=reset_pwd&error=4&token=' . $token);
                    //echo'<p class="p_error">La password temporanea inserita è sbagliata</p>';
                    //password temporanee diverse
                }
            }else{
                header('Location:index.php?pag=reset_pwd&error=5&token=' . $token);
                //exit("sono passati troppi giorni sulla richiesta");
                //$qtoken="delete from token where token='" .$riga["token"]. "'";
                //mysqli_query($conn, $qtoken)or die("errore delete token");
               //"sono passati troppi giorni"
            }
        }else if($num==0){
            header('Location:index.php?pag=reset_pwd&error=6&token=' . $token);
            //echo'<p class="p_error">Il token inserito è errato</p>';
            //"piu utenti"
        }
        else{
            header('Location:index.php?pag=reset_pwd&error=7&token=' . $token);
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
                break;
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
                //echo "Si è verificato un errore durante il controllo dell'account";
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

    //funzione che vede se hai un reset pwd in corso
    function verify_pwd_res($idut,$u){
        global $conn;
        //echo $idut;
        $q="select * from token where ruser=" .$idut. " and user_type='" . $u . "'";
        $ris=mysqli_query($conn,$q);
        $num=mysqli_num_rows($ris);
        //echo"ci sono";
        if($num != 0){
            return false;
        }else{
            return true;
        }
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

    function reload_user_data(){
        global $conn;
        $q  = "";
        switch ($_SESSION['user-type']){
            case 1:
                $q = "select * from admin where idUt = ".$_SESSION["user"]["idUt"];
                break;
            case 2:
                $q = "select * from studenti where idStu = ".$_SESSION["user"]["idStu"];
                break;
            case 3:
                $q = "select * from aziende where idAz = ".$_SESSION["user"]["idAz"];
                break;
        }
        $res = mysqli_query($conn,$q) or die();
        $user_data = mysqli_fetch_assoc($res);
        $_SESSION["user"] = $user_data;
    }
    
    
    //fornisci un idutente e il suo usertye e elimina tutti i dati utente
    function elimina_utente($user,$us_type){
        global $conn;
        if($us_type == 1){
            $q = "delete from admin where idUt= " .$user["idUt"];
            if(file_exists("../static/pfp/admin-pic/" . $user["idUt"] .".jpeg")){
                unlink("../static/pfp/admin-pic/" . $user["idUt"] .".jpeg");
            }
        }
        else if($us_type == 2){
            $q = "delete from studenti where idStu=" .$user["idStu"];
            if (file_exists("../static/pfp/studente-pic/" .$user["idStu"] .".jpeg")){
                unlink("../static/pfp/studente-pic/" .$user["idStu"] .".jpeg");
            }
        }
        else if($us_type == 3){
            $q = "delete from aziende where idAz=" .$user["idAz"];
            if(file_exists("../static/pfp/azienda-pic/" .$user["idAz"] .".jpeg")){
                unlink("../static/pfp/azienda-pic/" .$user["idAz"] .".jpeg");
            }
        }else{
            return false;
        }
        if (!mysqli_query($conn,$q)){
            return false;
        }
        return true;
    }

    if(isset($_POST["pag"]) && $_POST["pag"]=="new_event" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 1){
        $required = ["nome","descrizione","date","start_time","end_time","pos"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                header("Location: index.php?pag=new_event&error=1");
                exit();
            }
        }
        $nome = trim(mysqli_real_escape_string($conn, $_POST["nome"]));
        $desc = trim(mysqli_real_escape_string($conn, $_POST["descrizione"]));
        $date = trim(mysqli_real_escape_string($conn, $_POST["date"]));
        $start_time = trim(mysqli_real_escape_string($conn, $_POST["start_time"]));
        $end_time = trim(mysqli_real_escape_string($conn, $_POST["end_time"]));
        $pos = trim(mysqli_real_escape_string($conn, $_POST["pos"]));
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
        $nome = trim(mysqli_real_escape_string($conn, $_POST["nome"]));
        $desc = trim(mysqli_real_escape_string($conn, $_POST["descrizione"]));
        $date = trim(mysqli_real_escape_string($conn, $_POST["date"]));
        $start_time = trim(mysqli_real_escape_string($conn, $_POST["start_time"]));
        $end_time = trim(mysqli_real_escape_string($conn, $_POST["end_time"]));
        $pos = trim(mysqli_real_escape_string($conn, $_POST["pos"]));
        $q ="update career_day set nameCd='".$nome."',dateCd='".$date."',fromCd='".$start_time."',toCd='".$end_time."',locationCd='".$pos."',descCd='".$desc."' where idCd=".$id;
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=event&id=".$id);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="prenotazione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 2){
        if(!isset($_POST["id"]) || !isset($_POST["posizione"])) {
            header("Location: index.php?pag=adesione&error=1");
            exit();
        }
        $pos = filter_input(INPUT_POST,"posizione", FILTER_SANITIZE_NUMBER_INT);
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        
        $adQ = "select * from adesioni where enablePren = 1 and idAd = ".$id;
        $adRes = mysqli_query($conn, $adQ) or die("");
        if (mysqli_num_rows($adRes) != 1) {
            header("Location: index.php?pag=adesione&error=1");
            exit();
        }
        $q ="insert into prenotazioni (rAd,rStu,datapren, rPos) values('".$id."','".$_SESSION["user"]["idStu"]."','".date('Y-m-d H:i:s')."',".$pos.")";
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=adesione&id=".$id);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="update_prenotazione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        if(!isset($_POST["id"])) {
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        $completed = trim(mysqli_real_escape_string($conn, $_POST["completed"]));
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
        header("Location: index.php?pag=colloqui&selected=".$id);
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
        $q = "select * from prenotazioni where rPos = ".$id;
        $ris = mysqli_query($conn, $q) or die("c'è stato un problema nel controllare le prenotazioni associate a questa posizione");
        $num = mysqli_num_rows($ris);
        if($num > 0){
            if(!$id) exit();
            $q = "delete from posizioni where idPos = ".$id;
            $result = mysqli_query($conn, $q) or die();
            header("Location: index.php?pag=posizioni");
        }
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="add_position" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        $nome = trim(mysqli_real_escape_string($conn, $_POST["nomePos"]));
        $desc = trim(mysqli_real_escape_string($conn, $_POST["descPos"]));
        if(!$nome || !$desc) exit();
        $q = "insert into posizioni (rAz,nomePos,descrizionePos) values (".$_SESSION["user"]["idAz"].",'".$nome."','".$desc."')";
        $result = mysqli_query($conn, $q) or die();
        header("Location: index.php?pag=posizioni");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="edit_user" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 2){
        $required = ["nome","cognome","username","email"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                exit();
            }
        }
        $nome = trim(mysqli_real_escape_string($conn, $_POST["nome"]));
        $cognome = trim(mysqli_real_escape_string($conn, $_POST["cognome"]));
        $username = trim(mysqli_real_escape_string($conn, $_POST["username"]));
        $email = trim(mysqli_real_escape_string($conn, $_POST["email"]));

        $qCheckUsername = "select * from studenti where usernameStu = '".$username."' and idStu != ".$_SESSION["user"]["idStu"];
        $resultCheckUsername = mysqli_query($conn,$qCheckUsername) or die();
        if (mysqli_num_rows($resultCheckUsername) !=0){
            header("Location: index.php?pag=settings&error=1");
            exit();
        }
        $qCheckEmail = "select * from studenti where emailStu = '".$email."' and idStu != ".$_SESSION["user"]["idStu"];
        $resultCheckEmail = mysqli_query($conn,$qCheckEmail) or die();
        if (mysqli_num_rows($resultCheckEmail) !=0){
            header("Location: index.php?pag=settings&error=2");
            exit();
        }

        if (isset($_FILES["CV"]) && isset($_FILES["CV"]["name"]) && is_uploaded_file($_FILES["CV"]["tmp_name"])){
            if ($_FILES["CV"]["size"] > 5000000){
                header("Location: index.php?pag=settings&error=3");
                exit();
            }
            if (mime_content_type($_FILES["CV"]["tmp_name"]) != "application/pdf"){
                header("Location: index.php?pag=settings&error=4");
                exit();
            }
            if(!move_uploaded_file($_FILES["CV"]["tmp_name"], "../private/cv/".$_SESSION["user"]["idStu"].".pdf")){
                header("Location: index.php?pag=settings&error=5");
            }
        }


        $website = trim(mysqli_real_escape_string($conn, $_POST["website"]));
        $github = trim(mysqli_real_escape_string($conn, $_POST["github"]));
        $linkedin = trim(mysqli_real_escape_string($conn, $_POST["linkedin"]));

        $q ="UPDATE studenti SET nomeStu='".$nome."',cognomeStu='".$cognome."',emailStu = '".$email."',usernameStu = '".$username."' WHERE idStu=".$_SESSION["user"]["idStu"];
        $result = mysqli_query($conn, $q) or die("errore nella query");

        $q = "UPDATE studenti SET websiteStu = '" . $website . "', urlGithubStu = '" . $github . "', urlLinkedinStu = '" . $linkedin . "' WHERE idStu = " . $_SESSION["user"]["idStu"];
        $result = mysqli_query($conn, $q) or die();

        $tel = filter_input(INPUT_POST,"tel", FILTER_SANITIZE_NUMBER_INT);
        $loc = trim(mysqli_real_escape_string($conn, $_POST["loc"]));
        $bio = trim(mysqli_real_escape_string($conn, $_POST["bio"]));

        $q = "UPDATE studenti SET telStu = '" . $tel . "', locStu = '" . $loc . "', bioStu = '" . $bio . "' WHERE idStu = " . $_SESSION["user"]["idStu"];
        $result = mysqli_query($conn, $q) or die();
        reload_user_data();

        if(isset($_POST["password"]) && isset($_POST["newpassword"]) && !empty($_POST["password"]) && !empty($_POST["newpassword"])){
            $oldpwd = trim(mysqli_real_escape_string($conn,$_POST["password"]));
            $newpwd = trim(mysqli_real_escape_string($conn,$_POST["newpassword"]));
            if(!password_verify($oldpwd,$_SESSION["user"]["passwordStu"])){
                header("Location: index.php?pag=settings&error=6");
                exit();
            }
            $q = "UPDATE studenti SET passwordStu = '" . password_hash($newpwd,PASSWORD_DEFAULT)."' WHERE idStu = " . $_SESSION["user"]["idStu"];
            $result = mysqli_query($conn, $q) or die();
            reload_user_data();
        }
        header("Location: index.php?pag=settings&success=1");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="edit_az" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        $required = ["ragsoc","ind","cap","loc","provincia","piva","nomeref","cognomeref","username","email"];
        foreach($required as $r){
            if(!isset($_POST[$r])) {
                exit("campi mancanti");
            }
        }


        $ragsoc = trim(mysqli_real_escape_string($conn, $_POST["ragsoc"]));
        $ind = trim(mysqli_real_escape_string($conn, $_POST["ind"]));
        $cap = trim(mysqli_real_escape_string($conn, $_POST["cap"]));
        $loc = trim(mysqli_real_escape_string($conn, $_POST["loc"]));
        $prov = trim(mysqli_real_escape_string($conn, $_POST["provincia"]));
        $piva = trim(mysqli_real_escape_string($conn, $_POST["piva"]));
        $nome = trim(mysqli_real_escape_string($conn, $_POST["nomeref"]));
        $cognome = trim(mysqli_real_escape_string($conn, $_POST["cognomeref"]));
        $username = trim(mysqli_real_escape_string($conn, $_POST["username"]));
        $email = trim(mysqli_real_escape_string($conn, $_POST["email"]));

        $web = trim(mysqli_real_escape_string($conn, $_POST["web"]));

        $qCheckUsername = "select * from aziende where usernameRef = '".$username."' and idAz != ".$_SESSION["user"]["idAz"];
        $resultCheckUsername = mysqli_query($conn,$qCheckUsername) or die();
        if (mysqli_num_rows($resultCheckUsername) !=0){
            header("Location: index.php?pag=settings&error=1");
            exit();
        }
        $qCheckEmail = "select * from aziende where email = '".$email."' and idAz != ".$_SESSION["user"]["idAz"];
        $resultCheckEmail = mysqli_query($conn,$qCheckEmail) or die();
        if (mysqli_num_rows($resultCheckEmail) !=0){
            header("Location: index.php?pag=settings&error=2");
            exit();
        }
        $qCheckPiva = "select * from aziende where piva = '".$piva."' and idAz != ".$_SESSION["user"]["idAz"];
        $resultCheckPiva = mysqli_query($conn,$qCheckPiva) or die();
        if (mysqli_num_rows($resultCheckPiva) !=0){
            header("Location: index.php?pag=settings&error=3");
            exit();
        }

        $q ="UPDATE aziende SET ragsoc='".$ragsoc."',ind='".$ind."',cap = '".$cap."',loc = '".$loc."',prov = '".$prov."',piva = '".$piva."',email = '".$email."',web = '".$web."',nomeRef = '".$nome."',cognomeRef = '".$cognome."',usernameRef = '".$username."' WHERE idAz=".$_SESSION["user"]["idAz"];
        $result = mysqli_query($conn, $q) or die("errore nella query");
        reload_user_data();

        if(isset($_POST["password"]) && isset($_POST["newpassword"]) && !empty($_POST["password"]) && !empty($_POST["newpassword"])){
            $oldpwd = trim(mysqli_real_escape_string($conn,$_POST["password"]));
            $newpwd = trim(mysqli_real_escape_string($conn,$_POST["newpassword"]));
            if(!password_verify($oldpwd,$_SESSION["user"]["passwordRef"])){
                header("Location: index.php?pag=settings&error=6");
                exit();
            }
            $q = "UPDATE aziende SET passwordRef = '" . password_hash($newpwd,PASSWORD_DEFAULT)."' WHERE idAz = " . $_SESSION["user"]["idAz"];
            $result = mysqli_query($conn, $q) or die();
            reload_user_data();
        }

        header("Location: index.php?pag=settings&success=1");
    }
    if ($_POST["pag"] == "fotoprofilo2" && isset($_SESSION["user"]) && isset($_SESSION["user-type"])) {
        $dest = "";
        switch ($_SESSION["user-type"]) {
            case 1:
                $dest ="../static/pfp/admin-pic/" . $_SESSION["user"]["idUt"] . '.jpeg';
                break;
            case 2:
                $dest="../static/pfp/studente-pic/" . $_SESSION["user"]["idStu"] . '.jpeg';
                break;
            case 3:
                $dest = "../static/pfp/azienda-pic/" . $_SESSION["user"]["idAz"] . '.jpeg';
                break;
        }
        if(isset($_FILES["miofile"]) && isset($_FILES["miofile"]["name"]) && is_uploaded_file( $_FILES["miofile"]["tmp_name"])){
             if (mime_content_type($_FILES["miofile"]["tmp_name"]) != "image/jpeg" && mime_content_type($_FILES["miofile"]["tmp_name"]) != "image/png"){
                exit();
            }
            move_uploaded_file($_FILES["miofile"]["tmp_name"], $dest);
        }
        header("Location: index.php?pag=settings");
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="enable_prenotazione" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        if(!isset($_POST["id"]) || !isset($_POST["eventId"])) {
            header("Location: index.php");
            exit();
        }
        $id = filter_input(INPUT_POST,"id", FILTER_SANITIZE_NUMBER_INT);
        $eventId = filter_input(INPUT_POST,"eventId", FILTER_SANITIZE_NUMBER_INT);
        $checked = trim(mysqli_real_escape_string($conn, $_POST["enabledCheck"]));
        $qIdAd = "select * from adesioni where idAd = ".$id;
        $result = mysqli_query($conn, $qIdAd) or die();
        if (mysqli_num_rows($result) == 0) exit();
        $adesione = mysqli_fetch_assoc($result);
        if ($adesione["rAz"] != $_SESSION["user"]["idAz"]) die();
        if ($checked && $checked == "on"){
            $q = "UPDATE adesioni set enablePren = 1 WHERE idAd = ".$id;
        }else {
            $q = "UPDATE adesioni set enablePren = 0 WHERE idAd = ".$id;
        }
        $result = mysqli_query($conn, $q) or die("errore nella query");
        header("Location: index.php?pag=event&id=".$eventId);
    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="commPren" && isset($_SESSION["user"]) && $_SESSION["user-type"] == 3){
        if (!isset($_POST["id"]) || !isset($_POST["feedback"])){
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $id = trim(mysqli_real_escape_string($conn, $_POST["id"]));
        $feedback = trim(mysqli_real_escape_string($conn, $_POST["feedback"]));
        if(strlen($feedback) > 255){
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $q = "select * from prenotazioni inner join adesioni on prenotazioni.rAd = adesioni.idAd where idPren = ".$id;
        $res = mysqli_query($conn, $q) or die();
        if (mysqli_num_rows($res) == 0) {
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $prenotazione = mysqli_fetch_assoc($res);
        if ($prenotazione["rAz"] != $_SESSION["user"]["idAz"]){
            header("Location: index.php?pag=colloqui&error=1");
            exit();
        }
        $q = "UPDATE prenotazioni SET commPren = '".$feedback."' where idPren = ".$id;
        $res = mysqli_query($conn, $q) or die();
        header("Location: index.php?pag=colloqui&selected=".$id);
    }
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <link rel="manifest" href="../manifest.json" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../static/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/login_register.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="stylesheet" href="../css/event.css">
    <link rel="stylesheet" href="../css/new_edit_event.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="stylesheet" href="../css/send_mail.css">
    <link rel="stylesheet" href="../css/reset_password.css">
    <link rel="stylesheet" href="../css/colloqui.css">
    <link rel="stylesheet" href="../css/posizioni.css">
    <link rel="stylesheet" href="../css/prenotazione.css">
    <link rel="stylesheet" href="../css/movepfp.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add,arrow_back_ios_new,calendar_today,dark_mode,delete_forever,edit,expand_circle_down,light_mode,location_on,logout,visibility,visibility_off" />
    <script src="../js/occhiolino.js"></script>
    <script src="../js/dark-mode.js"></script>
    <title>Career Day</title>
</head>
<body class>
    <main>
    <?php
    if(isset($_SESSION["user"])){
        if(pwd_expired() && $_GET["pag"]!="pwdUpdate"){
            header("Location: index.php?pag=pwdUpdate");
        }
        if($_GET["pag"]=="pwdUpdate"){
            include("pwdUpdate.php");
        }
        if ($_GET["pag"] == "settings") {
            switch ($_SESSION["user-type"]) {
                case 1:
                    break;
                case 2:
                    include("settings.php");
                    break;
                case 3:
                    include("setting-az.php");
                    break;
                default:
                    echo "Si è verificato un errore durante il controllo dell'account";
                    exit();
            }
        }else if($_GET["pag"] == "event"){
            include("event.php");
        }else if ($_GET["pag"] == "new_event" && $_SESSION["user-type"] == 1){
            include ("new_event.php");
        }else if ($_GET["pag"] == "edit_event" && $_SESSION["user-type"] == 1){
            include ("edit-event.php");
        }else if ($_GET["pag"] == "adesione" && $_SESSION["user-type"] == 2){
            include ("adesione.php");
        }else if ($_GET["pag"] == "mycv" && $_SESSION["user-type"] == 2){
            include ("mycv.php");
        }else if ($_GET["pag"] == "colloqui" && $_SESSION["user-type"] == 3){
            include ("colloqui.php");
        }else if ($_GET["pag"] == "posizioni" && $_SESSION["user-type"] == 3){
            include ("posizioni.php");
        }else if ($_GET["pag"] == "fotoprofilo") {
            include("movefile.php");
        }else if ($_GET["pag"] == "viewcv" && $_SESSION["user-type"] == 3){
            include ("viewcv.php");
        }elseif($_GET["pag"] == "request_elut"){
            include ("request_elut.php");
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
        $_SESSION["next-page"] = "";
        if($_GET["pag"] == "login"){
            include("login.php");
        }else if($_GET["pag"] == "register"){
            include("register.php");
        }else if($_GET["pag"] == "request_reset_pwd"){
            include("request_reset_pwd.php");
        }else if($_GET["pag"] == "errori_reset_pwd"){
            include("errori_reset_pwd.php");
        }else if ($_GET["pag"] == "reset_pwd"){
            include ("reset_pwd.php");
        }else{
            include("homepage.php");
            $_SESSION["next-page"] = $_SERVER['REQUEST_URI'];
        }
    }else{
        include("homepage.php");
        $_SESSION["next-page"] = "";
    }
    ?>
    </main>
    <?php
        include("footer.php");
    ?>
</body>
</html>

<?php
    mysqli_close($conn);
?>