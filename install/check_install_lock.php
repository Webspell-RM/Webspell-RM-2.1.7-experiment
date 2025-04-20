<?php
// check_install_lock.php
$lock_file = __DIR__ . '/../system/installed.lock';

if (file_exists($lock_file)) {
    echo <<<HTML
<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Installation bereits durchgeführt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-warning shadow rounded">
            <h4 class="alert-heading">⚠️ Installation bereits abgeschlossen!</h4>
            <p>Die Datei <code>installed.lock</code> wurde gefunden. Das bedeutet, dass Webspell-RM bereits installiert wurde.</p>
            <hr>
            <p class="mb-0">Bitte lösche den Ordner <code>/install/</code>, um die Sicherheit deiner Website zu erhöhen.</p>
        </div>
    </div>
</body>
</html>
HTML;
    exit;
}
