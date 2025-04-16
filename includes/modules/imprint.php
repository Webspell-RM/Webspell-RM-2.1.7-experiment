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


$_language->readModule('imprint');

// Daten für das Template
$data_array = [
    'title' => $_language->module['imprint'],
    'subtitle' => 'Imprint'
];

$template = $tpl->loadTemplate("imprint", "head", $data_array);
echo $template;

$ergebnis =
    safe_query(
        "SELECT
            u.firstname, u.lastname, u.username, u.userID
        FROM
            user_groups as g, users as u
        WHERE
            u.userID = g.userID
        AND
            (g.page='1'
        OR
            g.forum='1'
        OR
            g.user='1'
        OR
            g.news='1'
        OR
            g.clanwars='1'
        OR
            g.feedback='1'
        OR
            g.super='1'
        OR
            g.gallery='1'
        OR
            g.cash='1'
        OR
            g.files='1')"
    );
$administrators = '';
while ($ds = mysqli_fetch_array($ergebnis)) {
    $administrators .= "<a href='index.php?site=profile&amp;id=" . $ds[ 'userID' ] . "'>" . $ds[ 'firstname' ] . " '" . $ds[ 'username' ] . "' " . $ds[ 'lastname' ] . "</a><br>";
}
$ergebnis =
    safe_query(
        "SELECT
            u.firstname, u.lastname, u.username, u.userID
        FROM
            user_groups as g, users as u
        WHERE
            u.userID = g.userID
        AND
            g.moderator='1'"
    );
$moderators = '';
while ($ds = mysqli_fetch_array($ergebnis)) {
    $moderators .= "<a href='index.php?site=profile&amp;id=" . $ds[ 'userID' ] . "'>" . $ds[ 'firstname' ] . " '" . $ds[ 'username' ] . "' " . $ds[ 'lastname' ] . "</a><br>";
}
// Include version
include('./system/version.php');

$headline1 = $_language->module['imprint'];
$headline2 = $_language->module['coding'];

if ($imprint_type) {
    $ds = mysqli_fetch_array(safe_query("SELECT imprint FROM `settings_imprint`"));
    $imprint_head = $ds['imprint'];

    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($imprint_head);
    $imprint_head = $translate->getTextByLanguage($imprint_head);
} else {
    
    $imprint_head = '<div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label"> ' . $_language->module[ 'webmaster' ] . '</label>
            <div class="col-sm-9">
                <p class="form-control-static">
                    <a href="mailto:' . mail_protect($admin_email) . '">' . $admin_name . '</a>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">' . $_language->module[ 'admins' ] . '</label>
            <div class="col-sm-9">
                <p class="form-control-static">' . $administrators . '</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">' . $_language->module[ 'mods' ] . '</label>
            <div class="col-sm-9">
                <p class="form-control-static">' . $moderators . '</p>
            </div>
        </div>
    </div>';
}

$ergebnis = safe_query("SELECT * FROM settings_imprint");
if (mysqli_num_rows($ergebnis)) {
    $ds = mysqli_fetch_array($ergebnis);
    $disclaimer_text = $ds['disclaimer_text'];

    $translate = new multiLanguage(detectCurrentLanguage());
    $translate->detectLanguages($disclaimer_text);
    $disclaimer_text = $translate->getTextByLanguage($disclaimer_text);
}

// Daten für das Template vorbereiten
$data_array = [
    'headline1' => $headline1,
    'imprint_head' => $imprint_head,
    'headline2' => $headline2,
    'version' => $version,  // Stelle sicher, dass $version definiert ist
    'disclaimer_text' => $disclaimer_text,
    'disclaimer' => $_language->module['disclaimer'],
    'coding_info' => $_language->module['coding_info'],
    'coding_info1' => $_language->module['coding_info1']
];

$template = $tpl->loadTemplate("imprint", "content", $data_array);
echo $template;
