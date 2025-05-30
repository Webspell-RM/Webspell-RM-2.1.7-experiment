<?php
// system/init.php

require_once __DIR__ . '/classes/Template.php';

// Basis-Erkennung des Bereichs (Admin, Plugin oder Frontend)
$base_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);

if (strpos($base_path, '/admin/') !== false) {
    $themes_path = 'admin/';
    $template_path = 'templates/';
} elseif (strpos($base_path, '/includes/plugins/') !== false) {
    // z.B. /includes/plugins/news/templates/
    preg_match('#/includes/plugins/([^/]+)/#', $base_path, $matches);
    $plugin_key = $matches[1] ?? 'default';
    $themes_path = "includes/plugins/$plugin_key/";
    $template_path = 'templates/';
} else {
    // Standard-Frontend
    $current_theme = 'default'; // Optional dynamisch laden aus DB/Einstellungen
    $themes_path = "includes/themes/$current_theme/";
    $template_path = 'templates/';
}

// Globale Template-Instanz
$tpl = new Template($themes_path, $template_path);
Template::setInstance($tpl);

require_once __DIR__ . '/classes/TextFormatter.php';

global $_database;
// LanguageService einbinden (Namespace beachten!)
use webspell\LanguageService;

// Instanz erzeugen und global verfügbar machen
global $languageService;
$languageService = new LanguageService($_database);

// Sprache ermitteln und ggf. in Session speichern
$lang = $languageService->detectLanguage();
$_SESSION['language'] = $lang;

// $lang global machen, falls du das möchtest
global $currentLanguage;
$currentLanguage = $lang;

