<!--errore numero 1-->
<?php
if($_GET["err"] == 1){
?>
<div class="container_errori">
<p class="riga">email non inviata correttamente.</p>
<a href="index.php" class="accedi_errori">Torna alla pagina di login</a>
<div>
<?php    
}
?>



<!--errore numero 2-->
<?php
if($_GET["err"] == 2){
?>
<div class="container_errori">
<div class="container_errori">
<p class="riga_errori">email inserita non esistente.</p>
<a href="index.php" class="accedi_errori">Torna alla pagina di login</a>
</div>
</div>
<?php    
}
?>



<!--errore numero 3-->
<?php
if($_GET["err"] == 3){
?>
<div class="container_errori">
<p class="riga_errori">Password inserite diverse.</p>
<a class="accedi_errori" href="index.php">Torna alla pagina di login</a>
</div>
<?php    
}
?>



<!--errore numero 4-->
<?php
if($_GET["err"] == 4){
?>
<div class="container_errori">
<p class="riga_errori" >Le password temporanee inserite sono diverse.</p>
<a class="accedi_errori" href="index.php">Torna alla pagina di login</a>
<div>
<?php    
}
?>



<!--errore numero 5 -->
<?php
if($_GET["err"] == 5){
?>
<div class="container_errori">
<p class="riga_errori">Sono passati troppi giorni dalla richiesta del cambio password.</p>
<a class="accedi_errori" href="index.php">Torna alla pagina di login</a>
</div>
<?php    
}
?>



<!-- errore numero 6 -->
<?php
if($_GET["err"] == 6){
?>
<div class="container_errori">
<p class="riga_errori">Il token inserto è errato.</p>
<a class="accedi_errori" href="index.php">Torna alla pagina di login</a>
</div>
<?php    
}
?>