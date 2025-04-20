<?php
session_start();
require_once("../system/config.inc.php");

// SQL-Datei importieren
function import_sql_file($mysqli, $filename)
{
    if (!file_exists($filename)) return '❌ SQL-Datei wurde nicht gefunden.';
    $sql = file_get_contents($filename);
    if (!$sql) return '❌ Fehler beim Laden der SQL-Datei.';

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

// RESET zum Testen
if (isset($_GET['reset'])) {
    unset($_SESSION['step5_done'], $_SESSION['progress'], $_SESSION['created_tables']);
    header("Location: step5.php");
    exit;
}

// Ausführen
$error = null;
$success = null;

if (!isset($_SESSION['step5_done'])) {
    $error = import_sql_file($_database, __DIR__ . '/webspellrm_base.sql');
    if (!$error) {
        $_SESSION['step5_done'] = true;
        $success = "✅ Datenbank erfolgreich installiert! Du wirst in wenigen Sekunden automatisch zu <strong>Schritt 6</strong> weitergeleitet.";
    }
} else {
    $success = "✅ Die Datenbank wurde bereits installiert. Weiterleitung erfolgt erneut zu <strong>Schritt 6</strong>";
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
        <ul class="list-group mb-4">
            <?php foreach ($_SESSION['created_tables'] as $table): ?>
                <li class="list-group-item"><?= htmlspecialchars($table) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
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
        }, 10000);
        <?php endif; ?>
    });
</script>
</body>
</html>

