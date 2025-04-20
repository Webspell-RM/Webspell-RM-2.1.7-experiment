<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/check_install_lock.php';

$show_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_host = $_POST['db_host'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_password = $_POST['db_password'] ?? '';
    $db_name = $_POST['db_name'] ?? '';

    // Fehlerbehandlung mit try-catch für die Verbindung
    try {
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
        
        // Wenn der Fehler auftritt, wird eine Ausnahme geworfen
        if ($conn->connect_error) {
            throw new mysqli_sql_exception("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
        }
        
        // Wenn die Verbindung erfolgreich ist, erstellst du die config-Datei
        $system_dir = __DIR__ . '/../system';
        $config_file = $system_dir . '/config.inc.php';

        @chmod($system_dir, 0777);

        $config_content = <<<PHP
<?php
// config.inc.php

define('DB_HOST', '{$db_host}'); // Datenbank-Host
define('DB_NAME', '{$db_name}'); // Datenbank-Name
define('DB_USER', '{$db_user}'); // Datenbank-Benutzer
define('DB_PASS', '{$db_password}'); // Datenbank-Passwort

// Verbindung zur Datenbank herstellen
\$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (\$_database->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . \$_database->connect_error);
}

define('DB_CHARSET', 'utf8mb4'); // Zeichensatz für die Verbindung

// Setze den Zeichensatz für die Verbindung
\$_database->set_charset(DB_CHARSET);
?>
PHP;

        if (file_put_contents($config_file, $config_content) === false) {
            $error_message = '❌ Fehler beim Schreiben der Konfigurationsdatei! Bitte prüfe die Rechte von /system/.';
        } else {
            @chmod($config_file, 0644);
            @chmod($system_dir, 0755);
            $show_success = true;
        }
    } catch (mysqli_sql_exception $e) {
        // Fehler im Fehlerbereich anzeigen
        $error_message = '❌ Fehler bei der Datenbankverbindung: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Schritt 2: Datenbank Zugangsdaten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Schritt 2: Datenbank Zugangsdaten</h2>
    <div class="card mt-4">
        <div class="card-body">
            <?php if ($show_success): ?>
                <div id="success-message" class="alert alert-success">
                    ✅ Datenbankverbindung erfolgreich!<br>
                    🔒 Konfigurationsdatei gespeichert.<br>
                </div>
                <div id="success-message" class="alert alert-success">
                    ✅ Alle Voraussetzungen erfüllt! Du wirst in wenigen Sekunden automatisch zu <strong>Schritt 3</strong> weitergeleitet.
                </div>

                <script>
                    setTimeout(function () {
                        window.location.href = "step3.php";
                    }, 3000);
                </script>

            <?php elseif (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Optional: Formularanzeige, falls noch nicht ausgefüllt -->
            <?php if (!$show_success): ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="db_host" class="form-label">Datenbank-Host</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" required
                               placeholder="z. B. localhost oder 127.0.0.1"
                               title="Die Adresse des MySQL-Servers. In den meisten Fällen ist das localhost.">
                    </div>

                    <div class="mb-3">
                        <label for="db_user" class="form-label">Datenbank-Benutzer</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" required
                               placeholder="z. B. root oder webspell_user"
                               title="Der Benutzername, mit dem sich das System bei der Datenbank anmeldet.">
                    </div>

                    <div class="mb-3">
                        <label for="db_password" class="form-label">Datenbank-Passwort</label>
                        <input type="password" class="form-control" id="db_password" name="db_password"
                               placeholder="Optionales Passwort"
                               title="Das Passwort für den angegebenen Datenbank-Benutzer. Kann leer bleiben, wenn nicht erforderlich.">
                    </div>

                    <div class="mb-3">
                        <label for="db_name" class="form-label">Datenbank-Name</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" required
                               placeholder="z. B. webspell"
                               title="Der Name der MySQL-Datenbank, in der die Tabellen installiert werden sollen.">
                    </div>

                    <button type="submit" class="btn btn-primary">Speichern & Weiter</button>
                </form>

            <?php endif; ?>
        </div>
    </div>    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
