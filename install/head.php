<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

session_name("ws_session");
session_start();

header('content-type: text/html; charset=utf-8');

include("../system/func/language.php");
include("../system/func/user.php");
include("../system/template.php");
include("../system/version.php");

$_language = new \webspell\Language();

// Setzen der Standardsprache, wenn sie noch nicht gesetzt ist
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = "de"; // Standard-Sprache auf Deutsch setzen
}

// Überprüfen, ob eine andere Sprache über GET gesetzt wurde
if (isset($_GET['lang'])) {
    $available_languages = ['de', 'en', 'fr']; // Liste der unterstützten Sprachen
    if (in_array($_GET['lang'], $available_languages)) {
        $_language->setLanguage($_GET['lang']);
        $_SESSION['language'] = $_GET['lang'];
    } else {
        $_SESSION['language'] = 'de';  // Standard auf Deutsch setzen, falls ungültige Sprache
    }
    header("Location: index.php"); // Seite nach Sprachänderung neu laden
    exit();
}

// Setze die Sprache basierend auf der Sitzung
$_language->setLanguage($_SESSION['language']);
$_language->readModule('index');

$_template = new template('', 'templates/');

// Schritt aus der URL auslesen
if (isset($_GET['step'])) {
    $step = (int)$_GET['step'];
} else {
    $step = 0;
}

$calcstep = ($step > 0) ? $step + 1 : 1;

// Lade die Sprachmodule für den entsprechenden Schritt
if ($step >= 0 && $step <= 4) {
    $_language->readModule('step' . $step, true);
} else {
    $_language->readModule('step0', true);
}

// Setze die Anzeige für abgeschlossene Schritte
$doneArray = array();
for ($x = 0; $x < 5; $x++) {
    $doneArray[$x] = ($step > $x) ? '<i class="bi bi-check-circle green"></i>' : '';
}

// Funktion zur Ermittlung der aktuellen URL
function CurrentUrl() {
    return ((empty($_SERVER['HTTPS'])) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];
}

// Funktion zur Überprüfung von Session-Werten
function checksession($value) {
  if(isset($_SESSION[$value])) {
    $vari = $_SESSION[$value];
  } else {    
    $vari = '';     
  }
  return $vari;
}

// Funktion zur Überprüfung von Funktionen (z.B. MySQL-Verbindung)
function checkfunc($value) {
    global $_language;
    if (function_exists('mysqli_connect')) {
        $value = '<div class="alert alert-success text-center" role="alert">'.$_language->module['available'].' <i class="bi bi-check-lg"></i></div>';
    } else {
        $value = '<div class="alert alert-danger text-center" role="alert">'.$_language->module['unavailable'].' <i class="bi bi-x-lg"></i></div>';
        $fatal_error = false;
    }
    return $value;
}

// Initialisierung der Variablen und Template-Daten
$data_array = array();
$data_array['$lang_welcome'] = $_language->module['welcome'];
$data_array['$lang_license_agreement'] = $_language->module['license_agreement'];
$data_array['$lang_url'] = $_language->module['url'];
$data_array['$lang_permissions'] = $_language->module['permissions'];
$data_array['$lang_select_installation'] = $_language->module['select_installation'];
$data_array['$lang_configuration'] = $_language->module['configuration'];
$data_array['$lang_complete'] = $_language->module['complete'];
$data_array['$done0'] = $doneArray[0];
$data_array['$done1'] = $doneArray[1];
$data_array['$done2'] = $doneArray[2];
$data_array['$done3'] = $doneArray[3];
$data_array['$done4'] = $doneArray[4];
$data_array['$calcstep'] = $calcstep;

// Laden des Template-Headers
$installhead = $_template->loadTemplate('install', 'head', $data_array);
echo $installhead;

?>