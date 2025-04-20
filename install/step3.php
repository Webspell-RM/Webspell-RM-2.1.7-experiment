<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/check_install_lock.php';

// config laden
require_once __DIR__ . '/../system/config.inc.php';

// Verbindung zur Datenbank herstellen
$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verbindung überprüfen
if ($_database->connect_error) {
    die('Fehler bei der Verbindung zur Datenbank: ' . $_database->connect_error);
}

// Initialisiere die Fehler-Variable
$error = null;
$success_messages = [];

// Überprüfen der PHP-Version
$required_php_version = '8.0.0';
if (version_compare(PHP_VERSION, $required_php_version, '<')) {
    die('<div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">⚠️ Fehler: Ungültige PHP-Version!</h4>
        <p>Webspell-RM benötigt PHP Version <strong>8.0 oder höher</strong>. Deine aktuelle PHP-Version ist <strong>' . PHP_VERSION . '</strong>.</p>
    </div>');
} else {
    $success_messages[] = "✅ PHP-Version ist korrekt! (Aktuelle Version: " . PHP_VERSION . ")";
}

// Überprüfen der MySQL- oder MariaDB-Version
$required_mysql_version = '8.0';
if ($_database->connect_error) {
    die('<div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">⚠️ Fehler: MySQL-Verbindung fehlgeschlagen!</h4>
        <p>Stelle sicher, dass MySQL oder MariaDB auf deinem Server läuft und die Verbindung funktioniert.</p>
    </div>');
}

$mysql_version = $_database->server_info;
if (strpos($mysql_version, 'MariaDB') !== false) {
    // Wenn MariaDB erkannt wird
    $success_messages[] = "✅ MariaDB-Version erkannt! (Aktuelle Version: " . $mysql_version . ")";
} else {
    if (version_compare($mysql_version, $required_mysql_version, '<')) {
        die('<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">⚠️ Fehler: Ungültige MySQL-Version!</h4>
            <p>Webspell-RM benötigt MySQL Version <strong>8.0 oder höher</strong>. Deine aktuelle MySQL-Version ist <strong>' . $mysql_version . '</strong>.</p>
        </div>');
    } else {
        $success_messages[] = "✅ MySQL-Version ist korrekt! (Aktuelle Version: " . $mysql_version . ")";
    }
}

// Prüfen der Datei-Berechtigungen für stylesheets.css
$css_file = __DIR__ . '/../includes/themes/default/css/stylesheet.css';
if (!is_writable($css_file)) {
    $error = "❌ Die Datei <strong>stylesheet.css</strong> hat nicht die erforderlichen Berechtigungen. Stelle sicher, dass sie schreibbar ist.";
} else {
    $success_messages[] = "✅ Die Datei <strong>stylesheet.css</strong> ist schreibbar.";
}

// Wenn keine Fehler vorhanden sind, Weiterleitung zu Schritt 4
if (!$error) {
    echo '<script>
            setTimeout(function () {
                window.location.href = "step4.php";
            }, 10000);
          </script>';
}

if (!$error) {
    $success = "✅ Alle Voraussetzungen erfüllt! Du wirst in wenigen Sekunden automatisch zu <strong>Schritt 4</strong> weitergeleitet.";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Schritt 3: Server Infos abfragen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Schritt 3: Server Infos abfragen</h2>
    <div class="card mt-4">
        <div class="card-body">
            <!-- Erfolgsmeldungen -->
            <?php if ($success_messages): ?>
                <div class="alert alert-success">
                    <?php foreach ($success_messages as $message): ?>
                        <p><?= $message ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Fehler-Meldung -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
        </div>
    </div>    
</div>
</body>
</html>
