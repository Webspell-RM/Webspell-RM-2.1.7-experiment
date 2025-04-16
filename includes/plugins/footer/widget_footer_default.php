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

// Sicherstellen, dass $myclanname definiert ist
$myclanname = isset($myclanname) ? $myclanname : 'Unbekannter Clan';  // Standardwert setzen
$myclanname = htmlspecialchars($myclanname, ENT_QUOTES, 'UTF-8');  // XSS verhindern

$ds = [];
$db_target = [];
$windowsTargets = [];

$settings = safe_query("SELECT * FROM "plugins_footer_target");
if (mysqli_num_rows($settings)) {
    $db_target = mysqli_fetch_array($settings);
}

for ($i = 1; $i <= 18; $i++) {
    $windowsTargets[$i] = (!empty($db_target["windows$i"])) ? '' : 'target="_blank"';
}

$footerLinks = '';
for ($i = 1; $i <= 10; $i++) {
    $navilinkKey = "navilink$i";
    $linknameKey = "linkname$i";

    if (!empty($ds[$navilinkKey]) && !empty($ds[$linknameKey])) {
        $footerLinks .= '<li class="list-group-item list-footer"><i class="bi bi-chevron-double-right text-white"></i>
            <a href="' . htmlspecialchars($ds[$navilinkKey], ENT_QUOTES, 'UTF-8') . '" ' . $windowsTargets[$i] . ' rel="nofollow">' . 
            htmlspecialchars($ds[$linknameKey], ENT_QUOTES, 'UTF-8') . '</a></li>';
    }
}

$sortierung = 'socialID ASC';
$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "settings_social_media` ORDER BY $sortierung");

if (mysqli_num_rows($ergebnis)) {
    while ($db_social = mysqli_fetch_array($ergebnis)) {

        $socials = [
            'twitch'    => 'bi-twitch',
            'steam'     => 'bi-steam',
            'facebook'  => 'bi-facebook',
            'twitter'   => 'bi-twitter',
            'youtube'   => 'bi-youtube',
            'rss'       => 'bi-rss',
            'linkedin'  => 'bi-linkedin-in',
            'instagram' => 'bi-instagram'
        ];

        foreach ($socials as $key => $icon) {
            ${$key} = (!empty($db_social[$key]))
                ? '<a href="' . htmlspecialchars($db_social[$key], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="nofollow"><i class="bi ' . $icon . '"></i></a>'
                : 'n_a';
        }

        $since = $db_social['since'];

        if ($db_social['socialID'] == 1) {
            $result_footer = safe_query("SELECT * FROM `" . PREFIX . "plugins_footer`");
            if (mysqli_num_rows($result_footer)) {
                while ($ds = mysqli_fetch_array($result_footer)) {

                    $navilinks = [];
                    $windows = [];

                    // Sicherstellen, dass die Fensteroptionen korrekt gesetzt sind
                    for ($i = 1; $i <= 18; $i++) {
                        $windows[$i] = (!empty($db_target["windows$i"])) ? '' : 'target="_blank"';
                    }

                    // Verknüpfung von Links und Namen
                    for ($i = 1; $i <= 10; $i++) {
                        $navilinkKey = 'navilink' . $i;
                        $linknameKey = 'linkname' . $i;
                        $navilinkVar = 'navilink' . $i;

                        // Sicherstellen, dass sowohl Link als auch Name vorhanden sind
                        if (!empty($ds[$navilinkKey]) && !empty($ds[$linknameKey])) {
                            ${$navilinkVar} = '<li class="list-group-item list-footer"><i class="bi bi-chevron-double-right text-white"></i> 
                                <a href="' . htmlspecialchars($ds[$navilinkKey], ENT_QUOTES, 'UTF-8') . '" ' . $windows[$i] . ' rel="nofollow">' . htmlspecialchars($ds[$linknameKey], ENT_QUOTES, 'UTF-8') . '</a></li>';
                        } else {
                            ${$navilinkVar} = '';
                        }
                    }

                    $data_array = [
                        'about'     => $ds['about'] ?? '',
                        'name'      => $ds['name'] ?? '',
                        'strasse'   => $ds['strasse'] ?? '',
                        'email'     => $ds['email'] ?? '',
                        'telefon'   => $ds['telefon'] ?? '',

                        'navilink1' => $navilink1 ?? '',
                        'navilink2' => $navilink2 ?? '',
                        'navilink3' => $navilink3 ?? '',
                        'navilink4' => $navilink4 ?? '',
                        'navilink5' => $navilink5 ?? '',
                        'navilink6' => $navilink6 ?? '',
                        'navilink7' => $navilink7 ?? '',
                        'navilink8' => $navilink8 ?? '',
                        'navilink9' => $navilink9 ?? '',
                        'navilink10' => $navilink10 ?? '',

                        'twitch'    => $twitch ?? 'n_a',
                        'facebook'  => $facebook ?? 'n_a',
                        'twitter'   => $twitter ?? 'n_a',
                        'youtube'   => $youtube ?? 'n_a',
                        'rss'       => $rss ?? 'n_a',
                        'linkedin'  => $linkedin ?? 'n_a',
                        'instagram' => $instagram ?? 'n_a',
                        'steam'     => $steam ?? 'n_a',

                        'since'     => $since ?? '',
                        'myclanname' => $myclanname ?? '',

                        'lang_aboutus' => $plugin_language['aboutus'] ?? '',
                        'lang_phone'   => $plugin_language['phone'] ?? '',
                        'lang_address' => $plugin_language['address'] ?? '',
                        'lang_name'    => $plugin_language['name'] ?? '',
                        'navigation'   => $plugin_language['navigation'] ?? '',
                        'links'        => $plugin_language['links'] ?? '',
                        'contact'      => $plugin_language['contact'] ?? ''
                    ];

                    $template = $plugin_template->loadTemplate("footer_default", "content", $data_array, $plugin_path);
                    echo $template;
                }
            }
        }
    }
}
