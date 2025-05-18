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

// Modul 'startpage' laden, um Sprachressourcen für die Startseite zu verwenden
$_language->readModule('startpage');

// Datenbankabfrage, um die Startseiten-Einstellungen zu holen
$ergebnis = safe_query("SELECT * FROM `settings_startpage`");
if (mysqli_num_rows($ergebnis)) {
    // Wenn Ergebnisse vorhanden sind, hole dir die Daten aus der Datenbank
    $ds = mysqli_fetch_array($ergebnis);

    // Den Titel der Startseite aus der Datenbank holen
    $title = $ds['title'];

    // Übersetzungs-Objekt für mehrsprachige Unterstützung erstellen
    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($title);  // Die Sprachen für den Titel erkennen
    $title = $translate->getTextByLanguage($title);  // Den Titel basierend auf der aktuellen Sprache setzen

    $config = mysqli_fetch_array(safe_query("SELECT selected_style FROM settings_headstyle_config WHERE id=1"));
    $class = htmlspecialchars($config['selected_style']);

    // Header-Daten
    $data_array = [
        'class'    => $class,
        'title' => $title,  // Den übersetzten Titel setzen
        'subtitle' => 'Start Page',  // Untertitel der Startseite setzen
    ];

    // Template für den Kopfbereich der Startseite laden und ausgeben
    $template = $tpl->loadTemplate("startpage", "head", $data_array);
    echo $template;

    // Den Text der Startseite aus der Datenbank holen
    $startpage_text = $ds['startpage_text'];

    // Den Startseitentext für Übersetzungen vorbereiten
    $translate->detectLanguages($startpage_text);
    $startpage_text = $translate->getTextByLanguage($startpage_text);  // Den Startseitentext in der aktuellen Sprache setzen

    // Ein weiteres Array für die Template-Daten vorbereiten
    $data_array = [
        'startpage_text' => $startpage_text,  // Den übersetzten Startseitentext setzen
        'date' => $date,  // Aktuelles Datum setzen
        'startpage' => $_language->module['startpage'],
        'stand1' => $_language->module['stand1'],
        'stand2' => $_language->module['stand2'],
    ];

    // Template für den Inhalt der Startseite laden und ausgeben
    $template = $tpl->loadTemplate("startpage", "content", $data_array);
    echo $template;

} else {
    // Falls keine Daten für die Startseite in der Datenbank gefunden wurden, eine Alert-Nachricht anzeigen
    echo generateAlert($_language->module['no_startpage'], 'alert-info');
}

