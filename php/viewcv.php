<?php
$id = filter_input(INPUT_GET,"id", FILTER_SANITIZE_NUMBER_INT);
if (!$id) exit();

$q = "select * from prenotazioni where rStu = ".$id;
$result = mysqli_query($conn, $q);
if (mysqli_num_rows($result) == 0) exit();
$hasStudentBooked = false;
while( $row = mysqli_fetch_assoc($result)) {
    $idAd = $row["rAd"];
    $q2 = "select * from adesioni where idAd = ".$idAd.' and rAz = '.$_SESSION["user"]["idAz"];
    $result2 = mysqli_query($conn, $q2);
    if (mysqli_num_rows($result2) == 1){
        $hasStudentBooked = true;
        break;
    }
}
if (!$hasStudentBooked) exit();

$file = realpath(__DIR__ . "/../private/cv/" . basename($id) . ".pdf");

$baseDir = realpath(__DIR__ . "/../private/cv");
if ($file === false || strpos($file, $baseDir) !== 0 || !is_file($file)) {
    http_response_code(404);
    exit;
}
if (ob_get_level()) { ob_end_clean(); }
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="cv.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
header('Content-Length: ' . filesize($file));

readfile($file);
exit();
?>