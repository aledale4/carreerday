<!--errore numero 1-->
<?php
if($_GET["errore"] == 1){
?>
<p>email non inviata correttamente.</p>
<a href="index.php" class="accedi">Torna alla pagina di login</a>
<?php    
}
?>



<!--errore numero 2-->
<?php
if($_GET["errore"] == 2){
?>
<p>email inserita non esistente.</p>
<a href="index.php" class="accedi">Torna alla pagina di login</a>
<?php    
}
?>



<!--errore numero 3-->
<?php
if($_GET["errore"] == 3){
?>
<p>Password inserite diverse.</p>
<a href="index.php">Torna alla pagina di login</a>
<?php    
}
?>



<!--errore numero 4-->
<?php
if($_GET["errore"] == 4){
?>
<p>Le password temporanee inserite sono diverse.</p>
<a href="index.php">Torna alla pagina di login</a>
<?php    
}
?>



<!--errore numero 5 -->
<?php
if($_GET["errore"] == 5){
?>
<p>Sono passati troppi giorni dalla richiesta del cambio password.</p>
<a href="index.php">Torna alla pagina di login</a>
<?php    
}
?>



<!-- errore numero 6 -->
<?php
if($_GET["errore"] == 6){
?>
<p>Il token inserto è errato.</p>
<a href="index.php">Torna alla pagina di login</a>
<?php    
}
?>