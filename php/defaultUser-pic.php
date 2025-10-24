<?php
switch ($_SESSION["user-type"]) {
    case 1:
        $file = '../static/pfp/Adm/' . $_SESSION["user"]["idUt"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='../static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
    case 2:
        $file = '../static/pfp/stu/' . $_SESSION["user"]["idStu"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='../static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
    case 3:
        $file = '../static/pfp/AZ/' . $_SESSION["user"]["idAz"] . '.jpeg';
        if (file_exists($file)) {
            echo '<img src="' . $file . '" alt="">';
        } else {
            echo "<img src='/static/pfp/Default_pfp.svg' alt=''>";
        }
        break;
}
?>