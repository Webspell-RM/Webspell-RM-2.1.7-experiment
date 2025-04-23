<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

// installed.lock schreiben
$lock_file = __DIR__ . '/../system/installed.lock';
file_put_contents($lock_file, "Installation erfolgreich am " . date('Y-m-d H:i:s'));

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Installation abgeschlossen – Schritt 6</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <div class="card shadow p-4">
        <h2 class="text-success text-center">Installation erfolgreich abgeschlossen!</h2>
        <p class="text-center mt-3">Webspell-RM wurde erfolgreich installiert. Du kannst dich jetzt mit dem Admin-Zugang anmelden.</p>

        <div class="text-center mt-4">
            <a href="../admin/admincenter.php" class="btn btn-primary btn-lg">Zum Adminbereich</a>
            <a href="../index.php" class="btn btn-primary btn-lg">Zur Webseite</a>
        </div>

        <hr class="my-4">

        <div class="alert alert-info text-center">
            <strong>Hinweis:</strong> Bitte lösche jetzt den <code>/install/</code>-Ordner aus Sicherheitsgründen!
        </div>
    </div>
</div>

</body>
</html>
