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

// Holt die Daten für das Plugin
$pluginData = \webspell\PluginService::getPluginData("footer", $plugin_path);
// Zugriff auf die Sprachdateien und das Template
$plugin_language = $pluginData['language'];
$plugin_template = $pluginData['template'];

// Sicherstellen, dass $myclanname definiert ist, falls nicht, einen Standardwert setzen
$myclanname = isset($myclanname) ? $myclanname : 'Unbekannter Clan';  // Standardwert: 'Unbekannter Clan'

// htmlspecialchars nur aufrufen, wenn der Wert nicht null oder leer ist
$myclanname = htmlspecialchars($myclanname, ENT_QUOTES, 'UTF-8');  // Sicherstellen, dass der String sicher ist

$ergebnis = safe_query("SELECT * FROM `".PREFIX."plugins_footer`");
if (mysqli_num_rows($ergebnis)) {
    while ($ds = mysqli_fetch_array($ergebnis)) {

        $settings = safe_query("SELECT * FROM " . PREFIX . "plugins_footer_target");
        $db = mysqli_fetch_array($settings);

        // Generiere target="_blank" für Links, falls benötigt
        $windows = [];
        for ($i = 14; $i <= 18; $i++) {
            $windows[$i] = (empty($db["windows$i"])) ? 'target="_blank"' : '';
        }

        // Generiere die Copyright-Links
        $copyright_links = [];
        for ($i = 1; $i <= 5; $i++) {
            $link_name = 'copyright_link' . $i;
            $link_url = 'copyright_link' . $i;
            $link_title = 'copyright_link_name' . $i;

            if (!empty($ds[$link_url])) {
                $copyright_links[$i] = '<a class="foot_link" href="' . htmlspecialchars($ds[$link_url]) . '" ' . $windows[14 + $i] . ' rel="nofollow">' . htmlspecialchars($ds[$link_title]) . '</a>';
            } else {
                $copyright_links[$i] = '';
            }
        }

        // Holen der "since"-Daten aus den Social Media Einstellungen
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM `".PREFIX."settings_social_media`"));
        $since = $dx['since'];

        // Bereite das Array mit den Template-Daten vor
        $data_array = [
            'myclanname' => $myclanname,
            'since' => $since,
            'copyright_link1' => $copyright_links[1],
            'copyright_link2' => $copyright_links[2],
            'copyright_link3' => $copyright_links[3],
            'copyright_link4' => $copyright_links[4],
            'copyright_link5' => $copyright_links[5],
            'date' => isset($plugin_language['date']) ? htmlspecialchars($plugin_language['date'], ENT_QUOTES, 'UTF-8') : 'N/A'  // Fallback auf 'N/A', wenn kein Wert gesetzt
        ];

        // Template laden und anzeigen
        $template = $plugin_template->loadTemplate("footer_easy", "content", $data_array, $plugin_path);
        echo $template;
    }
}
