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

# Sprachdateien aus dem Plugin-Ordner laden
    $pm = new plugin_manager(); 
    $plugin_language = $pm->plugin_language("easyfooter", $plugin_path);

GLOBAL $myclanname;    
       
        

$ergebnis = safe_query("SELECT * FROM plugins_footer");
if(mysqli_num_rows($ergebnis)){
    while ($ds = mysqli_fetch_array($ergebnis)) {

        $settings = safe_query("SELECT * FROM plugins_footer_target");
        $db = mysqli_fetch_array($settings);

        if ($db[ 'windows14' ]) {
            $windows14 = '';
        } else {
            $windows14 = 'target="_blank"';
        }

        if ($db[ 'windows15' ]) {
            $windows15 = '';
        } else {
            $windows15 = 'target="_blank"';
        }

        if ($db[ 'windows16' ]) {
            $windows16 = '';
        } else {
            $windows16 = 'target="_blank"';
        }

        if ($db[ 'windows17' ]) {
            $windows17 = '';
        } else {
            $windows17 = 'target="_blank"';
        }

        if ($db[ 'windows18' ]) {
            $windows18 = '';
        } else {
            $windows18 = 'target="_blank"';
        }


        if ($ds[ 'copyright_link1' ] != '') {
            $copyright_link1 = '<a class="foot_link" href="' . htmlspecialchars($ds[ 'copyright_link1' ]) . '" '.$windows14.' rel="nofollow">' . htmlspecialchars($ds[ 'copyright_link_name1' ]) . '</a>';
        } else {
            $copyright_link1 = '';
        }

        if ($ds[ 'copyright_link2' ] != '') {
            $copyright_link2 = '<a class="foot_link" href="' . htmlspecialchars($ds[ 'copyright_link2' ]) . '" '.$windows15.' rel="nofollow">' . htmlspecialchars($ds[ 'copyright_link_name2' ]) . '</a>';
        } else {
            $copyright_link2 = '';
        }

        if ($ds[ 'copyright_link3' ] != '') {
            $copyright_link3 = '<a class="foot_link" href="' . htmlspecialchars($ds[ 'copyright_link3' ]) . '" '.$windows16.' rel="nofollow">' . htmlspecialchars($ds[ 'copyright_link_name3' ]) . '</a>';
        } else {
            $copyright_link3 = '';
        }

        if ($ds[ 'copyright_link4' ] != '') {
            $copyright_link4 = '<a class="foot_link" href="' . htmlspecialchars($ds[ 'copyright_link4' ]) . '" '.$windows17.' rel="nofollow">' . htmlspecialchars($ds[ 'copyright_link_name4' ]) . '</a>';
        } else {
            $copyright_link4 = '';
        }

        if ($ds[ 'copyright_link5' ] != '') {
            $copyright_link5 = '<a class="foot_link" href="' . htmlspecialchars($ds[ 'copyright_link5' ]) . '" '.$windows18.' rel="nofollow">' . htmlspecialchars($ds[ 'copyright_link_name5' ]) . '</a>';
        } else {
            $copyright_link5 = '';
        }  

        $dx = mysqli_fetch_array(safe_query("SELECT * FROM settings_social_media"));

        $since = $dx['since'];
            


        $data_array = [
            'myclanname' => $myclanname,
            'since' => $since,

            'copyright_link1' => $copyright_link1,
            'copyright_link2' => $copyright_link2,
            'copyright_link3' => $copyright_link3,
            'copyright_link4' => $copyright_link4,
            'copyright_link5' => $copyright_link5
        ];
        
        $tpl = Template::getInstance();
        echo $tpl->loadTemplate("footer_easy", "content", $data_array, 'plugin');

    }
}
?>