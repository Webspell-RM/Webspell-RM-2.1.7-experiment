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

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "settings_themes WHERE active= 1");
$dx = mysqli_fetch_array($ergebnis);

#Verhindert einen Fehler wenn kein Template aktiviert ist
if (@$dx['active'] != '1') {
} else {
	$themes_modulname = $dx['modulname'];
}

class widgets
{
    // Methode für sichere Datenbankabfragen
    function safe_query($query)
    {
        include_once("settings.php"); // Stellt sicher, dass die Konfiguration geladen ist
        global $_database; // Greift auf die globale Datenbankverbindung zu
        return mysqli_query($_database, $query); // Führt die SQL-Abfrage aus
    }

    // Private Variablen für Widgets
    private string $_widgetname;
    private string $_widgetdatei;
    private string $_modulname;

    // Überprüfung, ob ein Plugin vollständig ist
    private function isComplete($plugin_folder)
    {
        $info = $this->getInfo($plugin_folder);

        // Prüft, ob die erforderlichen Plugin-Informationen vorhanden sind
        return $this->infoExists("includes/plugins/$plugin_folder");
    }

    // Methode zur Anzeige eines Widgets
    public function showWidget($name, $curr_widgetname = "", $curr_modulname = "", $curr_widgetdatei = "", $curr_id = "")
    {
        global $_database; // Zugriff auf die globale Datenbankverbindung

        // Falls die internen Variablen nicht gesetzt sind, nutze die übergebenen Werte
        $widgetname = $this->_widgetname ?? $curr_widgetname;
        $widgetdatei = $this->_widgetdatei ?? $curr_widgetdatei;
        $modulname = $this->_modulname ?? $curr_modulname;

        // Lese die aktuelle Seite aus der URL aus
        $qs_arr = [];
        parse_str($_SERVER['QUERY_STRING'], $qs_arr);
        $getsite = $qs_arr['site'] ?? 'startpage';

        // Liste der Seiten, für die ein allgemeines Widget-Setting genutzt wird
        $excluded_sites = [
            'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile',
            'error_404', 'report', 'static', 'loginoverview', 'register',
            'lostpassword', 'login', 'logout', 'footer', 'navigation', 'topbar',
            'articles_comments', 'blog_comments', 'gallery_comments', 'news_comments',
            'news_recomments', 'polls_comments', 'videos_comments'
        ];

        // Bestimmung der korrekten Tabelle für das Widget
        if ($getsite === 'forum_topic') {
            $table_name = PREFIX . "plugins_forum_settings_widgets";
        } elseif (in_array($getsite, $excluded_sites)) {
            $table_name = PREFIX . "settings_plugins_widget_settings";
        } else {
            $table_name = PREFIX . "plugins_" . $getsite . "_settings_widgets";
        }

        // Sichere SQL-Abfrage mit Prepared Statements
        $query = $_database->prepare("SELECT * FROM `$table_name` WHERE widgetname = ?");
        $query->bind_param("s", $widgetname);
        $query->execute();
        $result = $query->get_result();
        $db = $result->fetch_assoc();
        $query->close();

        // Falls das Widget in der Datenbank gefunden wurde, lade es mit dem Plugin-Manager
        if ($db) {
            $plugin = new plugin_manager();
            $plugin->set_debug(DEBUG);
            echo $plugin->plugin_widget($db["id"] ?? '');
        }
    }

    // Methode zur Registrierung eines Widgets
    public function registerWidget($position, $template_file = "")
    {
        global $_database; // Sicherstellen, dass die globale DB-Verbindung verfügbar ist

        // Aktuelle Seite aus der URL extrahieren
        $qs_arr = [];
        parse_str($_SERVER['QUERY_STRING'], $qs_arr);
        $getsite = $qs_arr['site'] ?? 'startpage';

        // Liste von Seiten, auf denen keine individuellen Widgets geladen werden
        $excluded_sites = [
            'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile',
            'error_404', 'report', 'static', 'loginoverview', 'register',
            'lostpassword', 'login', 'logout', 'footer', 'navigation', 'topbar',
            'articles_comments', 'blog_comments', 'gallery_comments', 'news_comments',
            'news_recomments', 'polls_comments', 'videos_comments'
        ];

        global $themes_modulname; // Modulname für das aktuelle Theme abrufen

        // Standardmäßig auf die allgemeine Widget-Tabelle setzen
        $table_prefix = PREFIX . "settings_plugins_widget_settings";

        // Falls es sich um eine Forumsseite oder eine nicht ausgeschlossene Seite handelt, passe die Tabelle an
        if ($getsite === 'forum_topic') {
            $table_prefix = PREFIX . "plugins_forum_settings_widgets";
        } elseif (!in_array($getsite, $excluded_sites)) {
            $table_prefix = PREFIX . "plugins_" . $getsite . "_settings_widgets";
        }

        // SQL-Abfrage zur Auswahl der Widgets an der gewünschten Position
        $query = "SELECT * FROM $table_prefix 
                  WHERE position LIKE ? 
                  AND widgetdatei IS NOT NULL 
                  AND modulname IS NOT NULL 
                  AND themes_modulname = ? 
                  ORDER BY sort ASC";

        // Vorbereiten und Ausführen der Abfrage
        $stmt = $_database->prepare($query);
        if (!$stmt) {
            die("Fehler bei der Datenbankanfrage: " . $_database->error);
        }

        $stmt->bind_param("ss", $position, $themes_modulname);
        $stmt->execute();
        $result_all_widgets = $stmt->get_result();

        // Standardtext, falls keine Widgets gefunden werden
        $widgets_templates = "<div class='panel-body'>No Widgets added.</div>";
        $curr_widget_template = false;

        // Wenn Widgets gefunden wurden, lade sie
        if ($result_all_widgets->num_rows > 0) {
            $widgets_templates = "";
            while ($widget = $result_all_widgets->fetch_assoc()) {
                $this->_widgetname = $widget['widgetname'];
                $this->_widgetdatei = $widget['widgetdatei'];
                $this->_modulname = $widget['modulname'];
                $curr_widget_template = $this->showWidget($widget['id'], $this->_modulname, $this->_widgetdatei, $this->_widgetname);
            }
        } else {
            $curr_widget_template = true;
        }
    }
}