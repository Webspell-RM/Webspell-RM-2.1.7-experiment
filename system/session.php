<?php
// WICHTIG: Vor dem session_start() alle Session-Einstellungen setzen
session_name('rm_session');
// Start der Session
ini_set('session.cookie_lifetime', 3600); // Lebensdauer des Cookies (1 Stunde)
ini_set('session.cookie_path', '/'); // Pfad, auf dem das Cookie gültig ist
ini_set('session.cookie_secure', true); // Nur über HTTPS
ini_set('session.cookie_httponly', true); // Verhindert den Zugriff über JavaScript

// Nur bei HTTPS:
// ini_set('session.cookie_secure', '1');     // Nur bei HTTPS aktivieren!

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

