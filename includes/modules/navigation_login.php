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

GLOBAL $userID, $board_topics, $split, $array;

$_language->readModule('index');

$template = $tpl->loadTemplate("navigation", "login_head", []);
echo $template;

if ($loggedin) {

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='forum' AND activate=1"));
    if (!isset($dx['modulname']) || $dx['modulname'] != 'forum') {
        $new_forum_posts = '';
        $icon = '';
    } else {
        $boards = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_boards");
        while ($db = mysqli_fetch_array($boards)) {

            $board_topics = [];
            $q = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_topics");

            while ($lp = mysqli_fetch_assoc($q)) {
                if ($userID) {
                    $board_topics[] = $lp['topicID'];
                } else {
                    break;
                }
            }

            if ($userID) {
                $ergebnisz = safe_query("SELECT topics FROM " . PREFIX . "user WHERE userID='$userID'");
                $gv = mysqli_fetch_array($ergebnisz);

                $icon = '<a data-toggle="tooltip" data-placement="bottom" title="' . $index_language['no_forum_post'] . '" href="index.php?site=forum">
                            <span class="icon badge bg-light text-dark mt-0 position-relative">
                                <i class="bi bi-chat"></i>
                            </span>
                         </a>';

                if (!empty($gv['topics'])) {
                    $topic = explode("|", $gv['topics']);
                    if (is_array($topic)) {
                        $n = 1;
                        foreach ($topic as $topics) {
                            if ($topics != "") {
                                $badgeNumber = min($n, 10);
                                $badgeLabel = ($badgeNumber == 10) ? "10+" : $badgeNumber;

                                $icon = '<a data-toggle="tooltip" data-placement="bottom" title="' . $index_language['more_new_forum_post'] . '" href="index.php?site=forum">
                                            <span class="badge bg-warning text-dark mt-0 position-relative">
                                                <i class="bi bi-chat-dots"></i>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                    ' . $badgeLabel . '
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </span>
                                         </a>';
                                $n++;
                            }
                        }
                    }
                }
            }
        }
    }

    $l_avatar = getavatar($userID) ?: "noavatar.png";

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname='messenger' AND activate=1"));
    if (!isset($dx['modulname']) || $dx['modulname'] != 'messenger') {
        $newmessages = '';
    } else {
        $newmessagesCount = getnewmessages($userID);

        $badgeNumber = min($newmessagesCount, 10);
        $badgeLabel = ($badgeNumber == 10) ? "10+" : $badgeNumber;

        if ($newmessagesCount > 0) {
            $newmessages = '<a data-toggle="tooltip" data-placement="bottom" title="' . ($newmessagesCount == 1 ? $index_language['one_new_message'] : $index_language['more_new_message']) . '" href="index.php?site=messenger">
                                <span class="icon badge text-bg-success position-relative">
                                    <i class="bi bi-envelope-check"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        ' . $badgeLabel . '
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </span>
                            </a>';
        } else {
            $newmessages = '<a data-toggle="tooltip" data-placement="bottom" title="' . $index_language['no_new_messages'] . '" href="index.php?site=messenger">
                                <span class="icon badge text-bg-light position-relative">
                                    <i class="bi bi-envelope"></i>
                                </span>
                            </a>';
        }
    }

    $dashboard = isanyadmin($_SESSION['ws_user']) ? 
        '<li><a class="dropdown-item" href="admin/admincenter.php" target="_blank">' . $index_language['admincenter'] . '</a></li>' : '';

    $_SESSION['ws_sessiontest'] = true;

    $data_array = [
        'modulepath' => substr(MODULE, 0, -1),
        'icon' => $icon,
        'newmessages' => $newmessages,
        'userID' => $userID,
        'l_avatar' => $l_avatar,
        'nickname' => getnickname($userID),
        'dashboard' => $dashboard,
        'lang_log_off' => $_language->module['log_off'],
        'lang_overview' => $index_language['overview'],
        'to_profil' => $index_language['to_profil'],
        'lang_user_information' => $index_language['user_information'],
        'lang_edit_profile' => $index_language['edit_profile']
    ];

    $template = $tpl->loadTemplate("navigation", "login_loggedin", $data_array);
    echo $template;

} else {

    $_SESSION['ws_sessiontest'] = true;

    $data_array = [
        'modulepath' => substr(MODULE, 0, -1),
        'lang_login' => $_language->module['login']
    ];

    $template = $tpl->loadTemplate("navigation", "login_login", $data_array);
    echo $template;
}

$template = $tpl->loadTemplate("navigation", "login_foot", []);
echo $template;
?>
