<?php
switch ($_SESSION["user-type"]) {
    case 1:
        $file = '../static/pfp/admin-pic/' . $_SESSION["user"]["idUt"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='../static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
    case 2:
        $file = '../static/pfp/studente-pic/' . $_SESSION["user"]["idStu"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='../static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
    case 3:
        $file = '../static/pfp/azienda-pic/' . $_SESSION["user"]["idAz"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='/static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
}
?>