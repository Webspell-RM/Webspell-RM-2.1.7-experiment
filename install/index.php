<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/check_install_lock.php';


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webspell-RM Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2>Willkommen zur Webspell-RM Installation</h2>
    <div class="card mt-4">
        <div class="card-body">
            <h4>Schritt 1: Einführung und Systemanforderungen</h4>
            <p>Herzlich willkommen bei der Installation von Webspell-RM! In den nächsten Schritten wirst du aufgefordert, die Datenbankinformationen einzugeben und die erforderlichen Ordnerrechte zu überprüfen. Diese Installation ist notwendig, um das System korrekt einzurichten.</p>
            <p>Falls du Unterstützung benötigst, findest du weiterführende Informationen in unserer Dokumentation oder auf unserer Webseite.</p>

            <h5>Systemanforderungen:</h5>
            <ul>
                <li>PHP Version 8.0 oder höher</li>
                <li>MySQL 8 oder höher bzw. MariaDB 10</li>
                <li>mindestens 512 MB RAM</li>
                <li>Schreibrechte für den Ordner <code>/system/</code> und <code>/includes/</code></li>
                <li>Schreibrechte für die Dateien <code>config.inc.php</code> und <code>stylesheet.css</code></li>
            </ul>

            <p>Bitte stelle sicher, dass alle Anforderungen erfüllt sind, bevor du fortfährst. Klicke auf den Button unten, um mit der Installation fortzufahren.</p>

            <!-- Weiter-Button zur nächsten Installationsphase -->
            <a href="step1.php" class="btn btn-primary btn-lg">Installation starten</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
