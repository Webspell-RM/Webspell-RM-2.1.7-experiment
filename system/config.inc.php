<?php
// config.inc.php

define('DB_HOST', 'localhost'); // Datenbank-Host
define('DB_NAME', 'd038d957'); // Datenbank-Name
define('DB_USER', 'd038d957'); // Datenbank-Benutzer
define('DB_PASS', '9KBQZ5x9HyDvt8f6'); // Datenbank-Passwort

// Verbindung zur Datenbank herstellen
$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($_database->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $_database->connect_error);
}

define('DB_CHARSET', 'utf8mb4'); // Zeichensatz für die Verbindung

// Setze den Zeichensatz für die Verbindung
#$_database->set_charset(DB_CHARSET);
#define('AES_KEY', 'dein-geheimer-schluessel');
#define('AES_IV', 'deinInitialisierungsVektorHier'); // 16 Bytes
?>