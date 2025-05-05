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

// Sprachmodul für die Navigation laden
$_language->readModule('navigation');

/**
 * Funktion zur Erzeugung einer Navigation ohne Dropdown-Menüs.
 *
 * @param string $default_url Standard-URL, falls keine spezifische Seite gefunden wird.
 * @return string HTML-Ausgabe der Navigation oder Fehlernachricht im Debug-Modus.
 */
function navigation_nodropdown($default_url) {
    $newurl = $default_url;
    
    // Prüfe, ob mod_Rewrite aktiviert ist
    $mr_res = mysqli_fetch_array(safe_query("SELECT * FROM `settings` WHERE 1"));
    if ($mr_res['modRewrite'] == 1) {
        $urlParts = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
        if (!empty($urlParts[0]) && strpos($urlParts[0], '.') !== false) {
            $newurl = "index.php?site=" . explode(".", $urlParts[0])[0];
        } else {
            $newurl = "index.php?site=" . $urlParts[0];
        }
    }
    
    try {
        // Überprüfung, ob die URL in der Navigation existiert
        $rex = safe_query("SELECT * FROM `navigation_website_sub` WHERE `url`='" . $newurl . "'");
        if (mysqli_num_rows($rex)) {
            $output = "";
            $rox = mysqli_fetch_array($rex);
            // Untermenüs abrufen
            $res = safe_query("SELECT * FROM `navigation_website_sub` WHERE `mnavID`='".intval($rox['mnavID'])."' AND `indropdown`='0' ORDER BY `sort`");
            while ($row = mysqli_fetch_array($res)) {
                $name = $_language->module[strtolower($row['name'])] ?? $row['name'];
                $output .= '<li class="nav-item"><a class="dropdown-item" href="' . $row['url'] . '">' . $name . '</a></li>';
            }
            return $output;
        }
    } catch (Exception $e) {
        if (DEBUG === "ON") {
            return $e->getMessage();
        }
    }
}

try {
    // Hauptnavigation abrufen
    $res = safe_query("SELECT * FROM `navigation_website_main` ORDER BY `sort`");
    $lo = 0;
    
    while ($row = mysqli_fetch_array($res)) {
        // Array für Navigationseinträge vorbereiten
        $head_array = [
            'name' => $_language->module[strtolower($row['name'])] ?? $row['name'],
            'url' => $row['url']
        ];
        
        // Sprache für Navigationseinträge übersetzen
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($row['name']);
        $head_array['name'] = $translate->getTextByLanguage($row['name']);
        
        // Wenn das Menü ein Dropdown ist
        if ($row['isdropdown'] == 1) {
            // Login-Übersicht hinzufügen, falls erforderlich
            if ($lo == 1) {
                $head_array['login_overview'] = $loggedin ? 
                    '<li class="nav-item"><a class="dropdown-item" href="index.php?site=loginoverview">' . $_language->module['overview'] . '</a></li>' : 
                    '<li class="nav-item"><a class="dropdown-item" href="index.php?site=login">' . $_language->module['login'] . '</a></li>';
            } else {
                $head_array['login_overview'] = "";
            }
            $lo++;
            
            // Untermenüs abrufen
            $rex = safe_query("SELECT * FROM `navigation_website_sub` WHERE `mnavID`='" . $row['mnavID'] . "' AND `indropdown`='1' ORDER BY `sort`");
            if (mysqli_num_rows($rex)) {
                // Template-Daten für Dropdown-Menü
                $data_array = [
                    'head' => $head_array,
                    'sub_open' => [],
                    'sub_nav' => [],
                    'sub_close' => [],
                    'dd_head' => [],
                    'dd_foot' => [],
                ];

                echo $tpl->loadTemplate("navigation", "dd_head", $data_array['head'], 'theme');
                echo $tpl->loadTemplate("navigation", "sub_open", $data_array['sub_open'], 'theme');
                
                while ($rox = mysqli_fetch_array($rex)) {
                    // Menüeinträge im Dropdown-Menü erzeugen
                    if (!empty($rox['indropdown']) && $rox['indropdown'] == 1) {
                        $sub_array = [
                            'url' => strpos($rox['url'], 'http://') !== false ? $rox['url'] . '" target="_blank' : $rox['url'],
                            'name' => $translate->getTextByLanguage($rox['name'])
                        ];
                        echo $tpl->loadTemplate("navigation", "sub_nav", $sub_array, 'theme');
                    }
                }
                
                echo $tpl->loadTemplate("navigation", "sub_close", $data_array['sub_close'], 'theme');
                echo $tpl->loadTemplate("navigation", "dd_foot", $data_array['dd_foot'], 'theme');
            }
        } else {
            // Falls kein Dropdown-Menü, normalen Navigationseintrag ausgeben
            #$head_array['windows'] = $row['windows'] ? '' : '_blank';

            $head_array = [
                    'windows' => $row['windows'] ? '' : '_blank',
                    'url' => strpos($rox['url'], 'http://') !== false ? $rox['url'] . '" target="_blank' : $rox['url'],
                            'name' => $translate->getTextByLanguage($rox['name'])
                ];

            echo $tpl->loadTemplate("navigation", "main_head", $head_array, 'theme');
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
    return false;
}
