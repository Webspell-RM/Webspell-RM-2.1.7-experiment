<?php
/**
 * Webspell-RM - A Modern Content Management System
 *
 * @version     3.0 (Next Generation 2025)
 * @author      Webspell-RM Team
 * @website     https://www.webspell-rm.de
 * @license     GNU GENERAL PUBLIC LICENSE (GPL)
 *
 * @note        This project is a full rework based on the original
 *              Webspell Clanpackage by Michael Gruber (webspell.at)
 *              with major architectural and codebase changes.
 *
 * @description
 * Webspell-RM is an open-source content management system (CMS) designed to manage websites and communities.
 * It provides powerful tools for creating and managing content, themes, plugins, and user permissions.
 *
 * @third_party
 * - jQuery: MIT License (https://jquery.com/)
 * - Bootstrap: MIT License (https://getbootstrap.com/)
 * - Other libraries: Refer to the LICENSE file for third-party libraries used in this project.
 */

include_once("system/config.inc.php");
include_once("system/settings.php");
include_once("system/functions.php");
include_once("system/themes.php");
include_once("system/init.php");
include_once("system/plugin.php");
include_once("system/widget.php");
include_once("system/multi_language.php");
include_once("system/classes/track_visitor.php");
#include_once("system/classes/TextFormatter.php");

// THEME INITIALISIEREN
$theme = new theme();

// TEMPLATE ENGINE INITIALISIEREN
$tpl = new template();
$tpl->themes_path = $theme->get_active_theme();
$tpl->template_path = "templates/";

// Plugin Manager
$_pluginmanager = new plugin_manager();

// DEFINES
define("MODULE", "./includes/modules/");

// PLUGINS
define("PLUGIN", "./includes/plugins/");

// SPRACHE LADEN
$_language->readModule('index');
$index_language = $_language->module;

// CSS / JS Komponenten laden (wie gehabt)
$components_css = "";
foreach ($components['css'] as $component) {
    $components_css .= '<link type="text/css" rel="stylesheet" href="' . $component . '" />' . "\n";
}

$components_js = "";
foreach ($components['js'] as $component) {
    $components_js .= '<script src="' . $component . '"></script>' . "\n";
}

$theme_css = headfiles("css", $tpl->themes_path);
$theme_js = headfiles("js", $tpl->themes_path);

// START
// include theme / content
include($theme->get_active_theme()."index.php");

?>