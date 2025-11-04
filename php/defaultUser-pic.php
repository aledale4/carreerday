<?php
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
    $data = file_get_contents($file); 
    $base64 = base64_encode($data); 
    echo '<img src="data:image/jpeg;base64,' . $base64. '" alt="">';
} else {
    echo "<img src='../static/Default_pfp.svg' alt=''>";
}
?>