<?php
$r = random_int(0,10000);
$file = "";
switch ($_SESSION["user-type"]) {
    case 1:
        $file = '../static/pfp/admin-pic/' . $_SESSION["user"]["idUt"] . '.jpeg';
        break;
    case 2:
        $file = '../static/pfp/studente-pic/' . $_SESSION["user"]["idStu"] . '.jpeg';
        break;
    case 3:
        $file = '../static/pfp/azienda-pic/' . $_SESSION["user"]["idAz"] . '.jpeg';
        break;
    default:
        break;
}
if (file_exists($file)) {
    echo '<img src="' . $file."?".$r . '" alt="">';
} else {
    echo "<img src='../static/pfp/Default_pfp.svg' alt=''>";
}
?>