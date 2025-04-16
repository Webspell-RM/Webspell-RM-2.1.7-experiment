<?php
// logout.php

session_start(); // Session starten

// Alle Session-Variablen löschen
session_unset();

// Die Session zerstören
session_destroy();

// Cookie löschen, falls gesetzt
setcookie('ws_session', '', time() - 3600, '/');

// Weiterleitung zur Login-Seite
header('Location: login.php');
exit;