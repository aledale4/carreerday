<?php
$file = realpath(__DIR__ . "/../private/cv/" . basename($_SESSION["user"]["idStu"]) . ".pdf");

// Controlla che il file esista e sia dentro la cartella prevista
$baseDir = realpath(__DIR__ . "/../private/cv");
if ($file === false || strpos($file, $baseDir) !== 0 || !is_file($file)) {
    http_response_code(404);
    exit;
}

// Pulisci buffer di output (nessun echo prima dell'header)
if (ob_get_level()) { ob_end_clean(); }

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="cv.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
header('Content-Length: ' . filesize($file));

// Serve il file
readfile($file);
exit();
?>