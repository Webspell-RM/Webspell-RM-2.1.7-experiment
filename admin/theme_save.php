<?php
// Konfigurationsdatei sicher einbinden
$configPath = __DIR__ . '/../system/config.inc.php';
if (!file_exists($configPath)) {
    die("Fehler: Konfigurationsdatei nicht gefunden.");
}
require_once $configPath;

// Datenbankverbindung aufbauen
$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Fehlerprüfung
if ($_database->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $_database->connect_error);
}

if ($_database->connect_error) {
    http_response_code(500);
    echo "DB-Verbindung fehlgeschlagen: " . $_database->connect_error;
    exit;
}

if (isset($_POST['theme']) && $_POST['theme'] !== '') {
    $theme = $_POST['theme'];

    $stmt = $_database->prepare("UPDATE settings_themes SET themename = ? WHERE modulname = 'default'");
    if (!$stmt) {
        http_response_code(500);
        echo "Prepare-Fehler: " . $_database->error;
        exit;
    }
    $stmt->bind_param("s", $theme);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "Execute-Fehler: " . $stmt->error;
    }
} else {
    http_response_code(400);
    echo "Fehlerhafte Eingabe: 'theme' fehlt oder leer";
}
