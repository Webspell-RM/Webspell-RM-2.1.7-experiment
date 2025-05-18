<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_NAME', 'd03e3329');
define('DB_USER', 'd03e3329');
define('DB_PASS', '97v4RrSChCGnW9jK9GyR');

$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

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
