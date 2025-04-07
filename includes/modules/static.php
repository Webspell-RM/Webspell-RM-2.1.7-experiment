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
 * @copyright       2018-2023 by webspell-rm.de                                                              *
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

// Überprüfen, ob der Parameter 'staticID' in der URL vorhanden ist
if (isset($_GET['staticID'])) {
    // Wenn vorhanden, wird der Wert des Parameters in $staticID gespeichert
    $staticID = $_GET['staticID'];
} else {
    // Falls nicht, wird $staticID auf einen leeren Wert gesetzt
    $staticID = '';
}

// Eine Abfrage ausführen, um die statischen Einstellungen aus der Datenbank zu holen, basierend auf der 'staticID'
$ds = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_static WHERE staticID='" . $staticID . "'"));

// Sprachmodul 'static' laden, um Übersetzungen für die statische Seite zu ermöglichen
$_language->readModule("static");

// Eine Variable 'allowed' setzen, die bestimmt, ob der Zugriff erlaubt ist
$allowed = false;

// Den Zugriff basierend auf dem 'accesslevel' des Datensatzes prüfen
switch ($ds['accesslevel']) {
    case 0:
        // Zugriff für alle Benutzer erlaubt
        $allowed = true;
        break;
    case 1:
        // Zugriff nur für eingeloggte Benutzer erlaubt
        if ($userID) {
            $allowed = true;
        }
        break;
    case 2:
        // Zugriff nur für Clan-Mitglieder erlaubt
        if (isclanmember($userID)) {
            $allowed = true;
        }
        break;
}

// Sprachmodul für die Navigation laden
$_language->readModule('navigation');

// Überprüfen, ob der Zugriff erlaubt ist
if ($allowed) {
    // Wenn der Zugriff erlaubt ist, den Titel der Seite aus dem Datensatz holen
    $title = $ds['title'];
    
    // Übersetzungsobjekt für mehrsprachige Unterstützung erstellen
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($title);  // Die Sprachen für den Titel erkennen
    $title = $translate->getTextByLanguage($title);  // Den Titel in der richtigen Sprache setzen

    // Ein Array mit den Template-Daten für den Kopfbereich der Seite erstellen
    $data_array = array();
    $data_array['$title'] = $title;  // Den Titel in das Array setzen
    $data_array['$subtitle'] = $title;  // Den Titel auch als Untertitel setzen
    
    // Template für den Kopfbereich der statischen Seite laden und ausgeben
    $template = $tpl->loadTemplate("static", "head", $data_array);
    echo $template;

    // Den Inhalt der statischen Seite aus der Datenbank holen
    $content = $ds['content'];
    
    // Den Inhalt für Übersetzungen vorbereiten
    $translate->detectLanguages($content);
    $content = $translate->getTextByLanguage($content);  // Den Inhalt in der richtigen Sprache setzen

    // Ein Array mit den Template-Daten für den Inhalt der Seite erstellen
    $data_array = array();
    $data_array['$content'] = $content;  // Den übersetzten Inhalt in das Array setzen
    
    // Template für den Inhalt der statischen Seite laden und ausgeben
    $template = $tpl->loadTemplate("static", "content", $data_array);
    echo $template;

} else {
    // Falls der Zugriff nicht erlaubt ist, das 'static' Modul für die Übersetzung laden
    $_language->readModule('static');
    
    // Weiterleitung zur Startseite mit einer Fehlermeldung
    redirect("index.php", $_language->module['no_access'], 3);
}
