<?php
    //per collegare il database e avviare la sessione
    session_start();
    $env = parse_ini_file("../.env");
    $conn = mysqli_connect($env["DB_HOST"],$env["DB_USRNAME"],$env["DB_PSW"],$env["DB_NAME"],$env["DB_PORT"]);
    // $ssl_ca = '../ca.pem';
    // $conn = mysqli_init();
    // mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, "", NULL);

    // if (!mysqli_real_connect($conn, $env["DB_HOST"],$env["DB_USRNAME"],$env["DB_PSW"],$env["DB_NAME"],$env["DB_PORT"], NULL, MYSQLI_CLIENT_SSL)) {
    //     die("". mysqli_connect_error());
    // }
    //funzione di logout
    if(isset($_GET["pag"]) && $_GET["pag"]=="logout" && isset($_SESSION["user"])){
        session_unset();
        session_destroy();
        header("Location: index.php");
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
    //funzione di registrazione
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
        $q ="insert into studenti (nomeStu,cognomeStu,usernameStu,passwordStu,emailStu) values('".$nome."','".$cognome."','".$username."','".$password."','".$email."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione");
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=login");
        exit();
    }
    //funzione di login
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
            //p.iva già usatoa
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
        $q ="insert into aziende (ragsoc,ind,cap,loc,prov,piva,email,nomeRef,cognomeRef,usernameRef,passwordRef) values('".$ragsoc."','".$indirizzo."','".$cap."','".$loc."','".$prov."','".$piva."','".$email."','".$nome."','".$cognome."','".$username."','".$password."')";
        echo $q;
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione");
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=login");
        exit();
    }
    // funzione di reset password
    if(isset($_POST["pag"]) && $_POST["pag"]=="request_reset_pwd" && !isset($_SESSION["user"])){
        if($_SESSION["user_type"== 2]){
            $q= "select * from studenti where emails='".$_POST["email"]."'";
        }
        if($_SESSION["user_type"== 3]){
            $q= "select * from aziende where email='".$_POST["email"]."'";
        }
        $ris= mysqli_query($conn, $q)or die("utente inesistente");
        $num = mysqli_num_rows($ris);
        if($num == 1){
            $riga = mysqli_fetch_assoc($ris);
            $token_random = random_bytes(254);
            $pwd_random = random_bytes(32);
            $token= bin2hex($token_random);
            $pwd_pro= bin2hex($pwd_random);
            if($_SESSION["user_type"== 2]){
                $q="update studenti set passwords = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idtu='" . $riga["idstu"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('".$riga["idstu"]."' , '".$token."' , '" .$_SESSION["user_type"]."','" .date('Y-m-d')."')";
            }
            if($_SESSION["user_type"== 3]){
                $q="update aziende set passwordref = '".password_hash($pwd_random,PASSWORD_DEFAULT). "' where idaz='" . $riga["idaz"]. "'";
                $q2="insert into token (ruser,token,user_type,created) values('".$riga["idaz"]."' , '".$token."' , '" .$_SESSION["user_type"]."','" .date('Y-m-d')."')";
            }
            mysqli_query($conn,$q); // da rivdere pk mi sa che ci va global
            mysqli_query($conn,$q2);
            $mitt="mittente.it"; //mittente
            $ogg="Reset password Carreday";
            $mess="Clicca su questo link per resettare a tua password : \nreset_passoword.php?token=" .$token . "\n Inserisci questa password provvisoria nel campo: Password provvisoria. \n" . $pwd_pro ; // link da inserire
            $header="From: ".$mitt."\r\nReply-To:".$mitt."\r\nContent-type: text/html; charset=utf-8\r\n";
            if(mail($_POST["email"], $ogg, $mess, $header)){ // destinatario , oggetto , messaggio , invio
                header("Location:email_inviata.php");
                exit();
            }else{
                //"non funziona"
            }
        }else{

        } //inserire l'errore

    }
    if(isset($_POST["pag"]) && $_POST["pag"]=="reset_pwd" && !isset($_SESSION["user"])){
        $q= "select * from token where token='".$_GET["token"]."'";
        $ris= mysqli_query($conn, $q)or die("token inesistente");
        $num = mysqli_num_rows($ris);
        $riga=mysqli_fetch_assoc($ris);
        if($num == 1){
            if(days_counter($riga["created"]) <=2){ //per vedere se sono passati piu di 2 giorni
                if($_SESSION["user_type"] == 2){ // per vedere che tipo di utente è
                    $qut="select * from studenti where idstu='".$riga["ruser"]."'";
                    $tipo_pwd="passwords";
                }
                if($_SESSION["user_type"] == 3){
                    $qut="select * from aziende where idaz='".$riga["ruser"]."'";
                    $tipo_pwd="passwordref";
                }
                $risut = mysqli_query($conn, $qut)or die("utente inesistente");
                $rigaut=mysqli_fetch_assoc($risut);
                if($_POST["password_temp"] == $rigaut["$tipo_pwd"]){ // per vedere se le password temporanee coincidono
                    if($_POST["password1"]==$_POST["password2"]){ // per vedere se le password nuove coincidono
                        if($_SESSION["user_type"] == 2){
                            $qpass="update studenti set passwords='" .$_POST["password1"]. "' where idstu='".$riga["ruser"]."'";
                        }
                        if($_SESSION["user_type"] == 3){
                            $qpass="update aziende set passwordref='" .$_POST["password1"]. "' where idaz='".$riga["ruser"]."'";
                        }
                        $qtoken="delete from token where token='" .$riga["token"]. "'";
                        mysqli_query($conn, $qpass)or die("errore updating");
                        mysqli_query($conn, $qtoken)or die("errore delete token");
                        header("Location:index.php?pag=login");
                        exit();
                    }else{
                        //password nuove diverse
                    }
                }else{
                    //password temporanee diverse
                }
            }else{
                $qtoken="delete from token where token='" .$riga["token"]. "'";
                mysqli_query($conn, $qtoken)or die("errore delete token");
               //"sono passati troppi giorni"
            }
        }else{
            //"piu utenti"
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/login_register.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/event.css">
    <link rel="stylesheet" href="../css/company-home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=arrow_back_ios_new,location_on" />
    <title>Career Day</title>
</head>
<body>
    <?php
    if(isset($_SESSION["user"])){
        if($_GET["pag"] == "settings"){
            include("settings.php");
        }else if($_GET["pag"] == "event"){
            include("event.php");
        }else {
            switch($_SESSION["user-type"]){
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
        }else if($_GET["pag"] == "reset_pwd"){
            include("request_password_reset.php");
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