<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/check_install_lock.php';

// config laden
require_once __DIR__ . '/../system/config.inc.php';

// Pfad zur SQL-Datei
$sql_file = __DIR__ . '/webspellrm_base.sql'; // Passe den Dateinamen an, falls nötig

// Funktion zum Ersetzen der Platzhalter in der SQL-Datei
function replace_placeholders($sql, $replacements)
{
    foreach ($replacements as $key => $value) {
        #echo "Ersetze {{".$key."}} mit ".$value."<br>"; // Debugging-Ausgabe
        $sql = str_replace('{{' . $key . '}}', $value, $sql);
    }
    return $sql;
}

// Funktion zum Importieren der SQL-Datei
function import_sql_file($mysqli, $filename, $replacements) {
    $sql = file_get_contents($filename);
    if (!$sql) {
        return '❌ Fehler: SQL-Datei konnte nicht geladen werden.';
    }

    // Platzhalter ersetzen
    $sql = replace_placeholders($sql, $replacements);

    // Mehrere Statements ausführen
    $mysqli->multi_query($sql);
    do {
        $mysqli->store_result();
    } while ($mysqli->next_result());

    return null; // kein Fehler
}

$error = null;
$success = null; // Hier wird der Erfolg gespeichert

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    $admin_user = $_POST['adminuser'] ?? '';
    $admin_email = $_POST['adminmail'] ?? '';
    $admin_pass = $_POST['adminpass'] ?? '';
    $admin_weburl = $_POST['adminweburl'] ?? '';

    $_SESSION['install_adminuser'] = $_POST['adminuser'];
    $_SESSION['install_adminmail'] = $_POST['adminmail'];
    #$_SESSION['install_adminpass'] = $_POST['adminpass'];
    $_SESSION['install_adminpass'] = password_hash($admin_pass, PASSWORD_DEFAULT); // hier gehasht!
    $_SESSION['install_adminweburl'] = $_POST['adminweburl'];

    if (empty($admin_user) || empty($admin_pass) || empty($admin_email) || empty($admin_weburl)) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        // Ersetzen der Platzhalter in der SQL-Datei
        $replacements = [
            'adminmail' => $_SESSION['install_adminmail'],
            'adminuser' => $_SESSION['install_adminuser'],
            'adminpass' => $_SESSION['install_adminpass'],
            'weburl'    => $_SESSION['install_adminweburl'],
        ];

        // Nur importieren, wenn noch nicht geschehen
        if (!isset($_SESSION['step4_sql_imported'])) {
            $error = import_sql_file($_database, $sql_file, $replacements);
            $_SESSION['step4_sql_imported'] = true;
        }

        if (!$error) {
            $success = "✅ Admin-Konto erfolgreich erstellt! Du wirst in wenigen Sekunden automatisch zu <strong>Schritt 5</strong> weitergeleitet.";

            echo '<script>
                setTimeout(function () {
                    window.location.href = "step5.php";
                }, 5000);
            </script>';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Schritt 4 – Datenbank einrichten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Schritt 4: Admin-Konto erstellen</h2>
    <div class="card mt-4">
        <div class="card-body">
            <!-- Fehlermeldung, nur anzeigen, wenn Fehler vorhanden -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- Erfolgsmeldung, nur anzeigen, wenn Erfolg vorhanden -->
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Admin Formular -->
            <?php if (!$success): ?>
            <form method="post">
                <div class="mb-3">
                    <label for="admin_user" class="form-label">Admin Benutzername</label>
                    <input type="text" name="adminuser" id="admin_user" class="form-control"
                           placeholder="z. B. admin oder webmaster"
                           title="Der Benutzername für den Administratorzugang zum Backend."
                           required>
                </div>

                <div class="mb-3">
                    <label for="admin_email" class="form-label">Admin E-Mail</label>
                    <input type="email" name="adminmail" id="admin_email" class="form-control"
                           placeholder="z. B. admin@example.com"
                           title="Die E-Mail-Adresse des Administrators – wird für Systembenachrichtigungen verwendet."
                           required>
                </div>

                <div class="mb-3">
                    <label for="admin_pass" class="form-label">Passwort</label>
                    <input type="password" name="adminpass" id="admin_pass" class="form-control"
                           placeholder="Sicheres Passwort wählen"
                           title="Ein sicheres Passwort für den Administrator. Mindestens 8 Zeichen empfohlen."
                           required>
                </div>

                <div class="mb-3">
                    <label for="admin_weburl" class="form-label">Website URL</label>
                    <input type="text" name="adminweburl" id="admin_weburl" class="form-control"
                           placeholder="z. B. https://www.deineseite.de"
                           title="Die vollständige URL deiner Webseite inklusive https:// – z. B. https://example.com"
                           required>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Installation abschließen</button>
                </div>
            </form>

            <?php endif; ?>
        </div>
    </div>    
</div>
</body>
</html>

