<?php
    //per collegare il database e avviare la sessione
    session_start();
    $env = parse_ini_file("../.env");
    $conn = mysqli_connect($env["DB_HOST"],$env["DB_USRNAME"],$env["DB_PSW"],$env["DB_NAME"],$env["DB_PORT"]);
    
    //funzione di logout
    if(isset($_POST["pag"]) && $_POST["pag"]=="logout" && isset($_SESSION["user"])){
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
    //funzione di registrazione
    if(isset($_POST["pag"]) && $_POST["pag"]=="register" && !isset($_SESSION["user"])){
        //controllo username
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
        }
        //username e email disponibili
        $nome= mysqli_real_escape_string($conn, $_POST["nome"]);
        $cognome= mysqli_real_escape_string($conn, $_POST["cognome"]);
        $username= mysqli_real_escape_string($conn, $_POST["username"]);
        $password= password_hash($conn, $_POST["password"]);
        $mail= mysqli_real_escape_string($conn, $_POST["email"]);
        $q ="insert into studenti (nomeStu,cognomeStu,usernameStu,passwordStu,emailStu) values('".$nome."','".$cognome."','".$username."','".$password."','".$email."')";
        $ris= mysqli_query($conn, $q)or die("errore durante la registrazione");
        //registrazione effettuata con successo
        session_regenerate_id();
        header("Location: index.php?pag=login");
        exit();
    }
    //funzione di login
    if(isset($_POST["pag"]) && $_POST["pag"]=="login" && !isset($_SESSION["user"])){
        $username=mysqli_real_escape_string($conn, $_POST["username"]);
        $q= "select * from studenti where usernameStu='".$username."'";
        $ris= mysqli_query($conn, $q)or die("errore durante la verifica dell'username");
        $num= mysqli_num_rows($ris);
        if($num==1){
            $riga = mysqli_fetch_assoc($ris);
            if(password_verify($_POST["password"],$riga["passwordStu"])){
                //login effettuato con successo
                $_SESSION["user"]=$riga;
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Day</title>
</head>
<body>
    <?php
    if(isset($_SESSION["user"])){
        include("home.php");
    }
    else if(isset($_GET["pag"])){
        if($_GET["pag"] == "login"){
            include("login.php");
        }else if($_GET["pag"] == "register"){
            include("register.php");
        }
    }
    ?>
</body>
</html>