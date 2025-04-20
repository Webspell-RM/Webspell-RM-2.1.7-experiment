<?php
session_start();
require_once("../system/config.inc.php");

// Platzhalter-Ersetzung
function replace_placeholders($sql, $replacements)
{
    foreach ($replacements as $key => $value) {
        #echo "Ersetze {{".$key."}} mit ".$value."<br>"; // Debugging-Ausgabe
        $sql = str_replace('{{' . $key . '}}', $value, $sql);
    }
    return $sql;
}

// SQL-Datei importieren und Platzhalter ersetzen
function import_sql_file($mysqli, $filename, $replacements = [])
{
    if (!file_exists($filename)) return '❌ SQL-Datei wurde nicht gefunden.';
    $sql = file_get_contents($filename);
    if (!$sql) return '❌ Fehler beim Laden der SQL-Datei.';

    // Platzhalter ersetzen
    $sql = replace_placeholders($sql, $replacements);
    
    // In einzelne Queries aufteilen
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    $total = count($queries);
    $done = 0;
    $created_tables = [];

    foreach ($queries as $query) {
        if (empty($query)) continue;

        if (!$mysqli->query($query)) {
            return "❌ Fehler bei SQL: " . $mysqli->error . "<br><pre>$query</pre>";
        }

        if (preg_match('/CREATE TABLE IF NOT EXISTS `?([a-zA-Z0-9_]+)`?/i', $query, $match)) {
            $created_tables[] = $match[1];
        }

        $done++;
        $_SESSION['progress'] = intval(($done / $total) * 100);
        usleep(50000);
    }

    $_SESSION['created_tables'] = $created_tables;
    $_SESSION['progress'] = 100;

    return null;
}

$error = null;
$success = null;

$replacements = [
    'adminuser' => $_SESSION['install_adminuser'],
    'adminmail' => $_SESSION['install_adminmail'],
    'adminpass' => $_SESSION['install_adminpass'],
    'adminweburl' => $_SESSION['install_adminweburl']
];

$error = import_sql_file($_database, __DIR__ . '/webspellrm_base.sql', $replacements);
if (!$error && !empty($_SESSION['created_tables'])) {
    $_SESSION['step5_done'] = true;
    $success = "✅ Datenbank erfolgreich installiert!";
} else if (!$error) {
    $error = "⚠️ Es wurden keine Tabellen erstellt – bitte prüfe die SQL-Datei.";
}


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Installation – Schritt 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container my-5">
    <h2>Schritt 5: Datenbank importieren</h2>
    <div class="card mt-4">
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <div class="progress my-4">
                <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%">0%</div>
            </div>

            <?php if (!empty($_SESSION['created_tables'])): ?>
                <h5>Erstellte Tabellen:</h5>
                <ul class="list-group mb-4 scrollable-list">
                    <?php foreach ($_SESSION['created_tables'] as $table): ?>
                        <li class="list-group-item"><?= htmlspecialchars($table) ?></li>
                    <?php endforeach; ?>
                </ul>

                <style>
                    .scrollable-list {
                        max-height: 400px;
                        overflow-y: auto;
                    }
                </style>
            <?php endif; ?>
        </div>
    </div>    
</div>

<script>
    $(document).ready(function () {
        function updateProgress() {
            $.get('progress.php', function (data) {
                let percent = data.progress || 0;
                $('#progressBar').css('width', percent + '%').text(percent + '%');
                if (percent < 100) setTimeout(updateProgress, 300);
            });
        }

        updateProgress();

        <?php if (!$error): ?>
        setTimeout(function () {
            window.location.href = 'finish.php';
        }, 20000);
        <?php endif; ?>
    });
</script>
</body>
</html>
