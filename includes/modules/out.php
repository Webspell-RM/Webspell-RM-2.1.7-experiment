<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2025 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

chdir("../../");
$err = 0;
if (file_exists("system/sql.php")) { include("system/sql.php"); } else { $err++; }
if (file_exists("system/settings.php")) { include("system/settings.php"); } else { $err++; }
if (file_exists("system/functions.php")) { include("system/functions.php"); } else { $err++; }

$closed_tmp = $closed;
$closed = 0;

$_language->readModule('out');

// Ermitteln des aktuellen Protokolls (http oder https)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

// Sicherstellen, dass die Eingabewerte gültig sind
if (isset($_GET['bannerID']) && is_numeric($_GET['bannerID'])) {
    $bannerID = (int)$_GET['bannerID'];
    safe_query("UPDATE " . PREFIX . "plugins_bannerrotation SET hits = hits + 1 WHERE bannerID = '$bannerID'");
    
    $ds = mysqli_fetch_assoc(safe_query(
        "SELECT bannerurl FROM " . PREFIX . "plugins_bannerrotation WHERE bannerID = '$bannerID'"
    ));
    // Dynamisches Setzen des Protokolls
    $target = $protocol . '://' . str_replace('http://', '', str_replace('https://', '', $ds['bannerurl']));
    $type = "direct";
}

if (isset($_GET['partnerID']) && is_numeric($_GET['partnerID'])) {
    $partnerID = (int)$_GET['partnerID'];
    safe_query("UPDATE " . PREFIX . "plugins_partners SET hits = hits + 1 WHERE partnerID = '$partnerID'");
    
    $ds = mysqli_fetch_assoc(safe_query(
        "SELECT url FROM " . PREFIX . "plugins_partners WHERE partnerID = '$partnerID'"
    ));
    // Dynamisches Setzen des Protokolls
    $target = $protocol . '://' . str_replace('http://', '', str_replace('https://', '', $ds['url']));
    $type = "direct";
}

if (isset($_GET['sponsorID']) && is_numeric($_GET['sponsorID'])) {
    $sponsorID = (int)$_GET['sponsorID'];
    safe_query("UPDATE " . PREFIX . "plugins_sponsors SET hits = hits + 1 WHERE sponsorID = '$sponsorID'");
    
    $ds = mysqli_fetch_assoc(safe_query(
        "SELECT url FROM " . PREFIX . "plugins_sponsors WHERE sponsorID = '$sponsorID'"
    ));
    // Dynamisches Setzen des Protokolls
    $target = $protocol . '://' . str_replace('http://', '', str_replace('https://', '', $ds['url']));
    $type = "direct";
}

// Sicherstellen, dass ein Ziel gesetzt wurde, bevor wir weiterleiten
if (isset($target)) {
    header("Location: $target");
} else {
    // Sicherungs-URL, falls keine Weiterleitung möglich ist
    header("Location: " . $_SERVER['HTTP_REFERER'] ?? 'index.php');
}

exit;
?>
