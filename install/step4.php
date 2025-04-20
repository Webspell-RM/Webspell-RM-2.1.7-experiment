<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/check_install_lock.php';
require_once __DIR__ . '/../system/config.inc.php';

$sql_file = __DIR__ . '/sql/webspellrm_base.sql';

// Platzhalter ersetzen
function replace_placeholders($sql, $replacements)
{
    foreach ($replacements as $key => $value) {
        $sql = str_replace('{{' . $key . '}}', $value, $sql);
    }
    return $sql;
}

// SQL-Datei importieren
function import_sql_file($mysqli, $filename, $replacements) {
    $sql = file_get_contents($filename);
    if (!$sql) {
        return '❌ Fehler: SQL-Datei konnte nicht geladen werden.';
    }

    $sql = replace_placeholders($sql, $replacements);

    if (!$mysqli->multi_query($sql)) {
        return '❌ Fehler beim Ausführen der SQL-Befehle: ' . $mysqli->error;
    }

    do {
        $mysqli->store_result();
    } while ($mysqli->more_results() && $mysqli->next_result());

    return null;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $admin_user   = trim($_POST['adminuser'] ?? '');
    $admin_email  = trim($_POST['adminmail'] ?? '');
    $admin_pass   = $_POST['adminpass'] ?? '';
    $admin_weburl = trim($_POST['adminweburl'] ?? '');

    if (empty($admin_user) || empty($admin_email) || empty($admin_pass) || empty($admin_weburl)) {
        $error = 'Bitte alle Felder ausfüllen.';
    } else {
        // Passwort hashen und in Session speichern
        $hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);

        $_SESSION['install_adminuser'] = $admin_user;
        $_SESSION['install_adminmail'] = $admin_email;
        $_SESSION['install_adminpass'] = $hashed_pass;
        $_SESSION['install_adminweburl'] = $admin_weburl;

        $replacements = [
            'adminuser' => $_SESSION['install_adminuser'],
            'adminmail' => $_SESSION['install_adminmail'],
            'adminpass' => $_SESSION['install_adminpass'],
            'weburl'    => $_SESSION['install_adminweburl'],
        ];

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
    <title>Installation Schritt 4 – Admin-Konto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Schritt 4: Admin-Konto erstellen</h2>
    <div class="card mt-4">
        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="admin_user" class="form-label">Admin Benutzername</label>
                        <input type="text" name="adminuser" id="admin_user" class="form-control" required placeholder="z. B. admin">
                    </div>

                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Admin E-Mail</label>
                        <input type="email" name="adminmail" id="admin_email" class="form-control" required placeholder="z. B. admin@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="admin_pass" class="form-label">Passwort</label>
                        <input type="password" name="adminpass" id="admin_pass" class="form-control" required placeholder="Sicheres Passwort wählen">
                    </div>

                    <div class="mb-3">
                        <label for="admin_weburl" class="form-label">Website URL</label>
                        <input type="text" name="adminweburl" id="admin_weburl" class="form-control" required placeholder="https://www.deineseite.de">
                    </div>

                    <button type="submit" class="btn btn-primary">Installation abschließen</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
