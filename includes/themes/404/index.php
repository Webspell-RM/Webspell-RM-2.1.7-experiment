<?php
// Nur wenn noch keine Ausgabe gesendet wurde
if (!headers_sent()) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Seite nicht gefunden</title>
</head>
<body>
    <h1>404 - Seite nicht gefunden</h1>
    <p>Die angeforderte Seite existiert nicht.</p>
</body>
</html>
