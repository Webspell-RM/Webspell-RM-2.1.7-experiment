<?php
// config.inc.php

define('DB_HOST', 'localhost'); // Datenbank-Host
define('DB_NAME', 'd038d957'); // Datenbank-Name
define('DB_USER', 'd038d957'); // Datenbank-Benutzer
define('DB_PASS', '9KBQZ5x9HyDvt8f6'); // Datenbank-Passwort
define('DB_CHARSET', 'utf8mb4'); // Zeichensatz für die Verbindung

// Verbindung zur Datenbank herstellen
$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($_database->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $_database->connect_error);
}

// Erstelle die Verbindung zur Datenbank
$_database = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Überprüfen, ob die Verbindung erfolgreich war
if ($_database->connect_error) {
    die('Fehler bei der Verbindung zur Datenbank: ' . $_database->connect_error);
}

// Setze den Zeichensatz für die Verbindung
$_database->set_charset(DB_CHARSET);
?>