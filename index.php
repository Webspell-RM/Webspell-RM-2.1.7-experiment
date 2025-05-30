<?php
/**
 * ─────────────────────────────────────────────────────────────────────────────
 * Webspell-RM 3.0 - Modern Content & Community Management System
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @version       3.0
 * @build         Stable Release
 * @release       2025
 * @copyright     © 2018–2025 Webspell-RM | https://www.webspell-rm.de
 * 
 * @description   Webspell-RM is a modern open source CMS designed for gaming
 *                communities, esports teams, and digital projects of any kind.
 * 
 * @author        Based on the original WebSPELL Clanpackage by Michael Gruber
 *                (webspell.at), further developed by the Webspell-RM Team.
 * 
 * @license       GNU General Public License (GPL)
 *                This software is distributed under the terms of the GPL.
 *                It is strictly prohibited to remove this copyright notice.
 *                For license details, see: https://www.gnu.org/licenses/gpl.html
 * 
 * @support       Support, updates, and plugins available at:
 *                → Website: https://www.webspell-rm.de
 *                → Forum:   https://www.webspell-rm.de/forum.html
 *                → Wiki:    https://www.webspell-rm.de/wiki.html
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 */


session_start();

include_once("system/config.inc.php");
include_once("system/settings.php");
include_once("system/functions.php");
include_once("system/themes.php");
include_once("system/init.php");
include_once("system/plugin.php");
include_once("system/widget.php");
include_once("system/multi_language.php");
include_once("system/classes/track_visitor.php");

if (file_exists("includes/plugins/counter/counter_track.php")) {
    include_once("includes/plugins/counter/counter_track.php");
}

if (file_exists("includes/plugins/whoisonline/whoisonline_tracker.php")) {
    include_once("includes/plugins/whoisonline/whoisonline_tracker.php");
}

$theme = new theme();

$tpl = new template();
$tpl->themes_path = rtrim($theme->get_active_theme(), '/\\') . DIRECTORY_SEPARATOR;
$tpl->template_path = "templates" . DIRECTORY_SEPARATOR;

$_pluginmanager = new plugin_manager();

$lang = getCurrentLanguage();

define("MODULE", "./includes/modules/");
define("PLUGIN", "./includes/plugins/");

#$_language->readModule('index');
#$index_language = $_language->module;

// CSS / JS Komponenten laden
$components_css = "";
if (!empty($components['css'])) {
    foreach ($components['css'] as $component) {
        $components_css .= '<link type="text/css" rel="stylesheet" href="' . htmlspecialchars($component) . '" />' . "\n";
    }
}

$components_js = "";
if (!empty($components['js'])) {
    foreach ($components['js'] as $component) {
        $components_js .= '<script src="' . htmlspecialchars($component) . '"></script>' . "\n";
    }
}

$theme_css = headfiles("css", $tpl->themes_path);
$theme_js = headfiles("js", $tpl->themes_path);

include($tpl->themes_path . "index.php");
