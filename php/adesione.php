<?php
    if (!isset($_GET["id"])) exit(0);
    $id = filter_input(INPUT_GET,"id", FILTER_SANITIZE_NUMBER_INT);
    $q = "select * from adesioni where idAd = ".$id;
    $result = mysqli_query($conn, $q) or die("errore nella query"); 
    if (mysqli_num_rows($result) == 0) exit();
    $row = mysqli_fetch_assoc($result);

    $qAz = "select * from aziende where idAz = ".$row["rAz"];
    $result = mysqli_query($conn, $qAz) or die();
    if (mysqli_num_rows($result) == 0) exit();
    $azienda = mysqli_fetch_assoc($result);

    $qEv = "select * from career_day where idCd = ".$row["rCd"];
    $result = mysqli_query($conn, $qEv) or die();
    if (mysqli_num_rows($result) == 0) exit();
    $evento = mysqli_fetch_assoc($result);

    $already_signed = true;
    $qCheck = "select * from prenotazioni where rAd = ".$id." and rStu = ".$_SESSION["user"]["idStu"];
    $resultCheck = mysqli_query($conn, $qCheck) or die("");
    if (mysqli_num_rows($resultCheck) == 0) $already_signed = false;

?>

<div class="home-container">
<div class="navbar">
    <div class="left-side">
        <img src="../static/logo.svg" alt="" srcset="" class="logo">
        <p>Portale Studenti</p>
    </div>
    <div class="right-side">
        <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeStu"]; ?></span></p>
         <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php")  ?></div>
            </a>
        <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
        <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
    </div>
</div>

<h1><?php echo $evento["nameCd"]?></h1>
<p>Prenota il tuo colloquio con <span><?php echo $azienda["ragsoc"]?></span></p>
<form action="index.php" method="post">
    <input type="hidden" name="pag" value="prenotazione">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="submit" value="Prenotati ora" <?php echo $already_signed?"disabled":""?> >
</form>
</div>