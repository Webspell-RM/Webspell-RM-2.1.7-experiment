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

// Wechseln in das übergeordnete Verzeichnis
chdir("../../");

// Wichtige Dateien einbinden
$files = ["system/sql.php", "system/settings.php", "system/functions.php"];
foreach ($files as $file) {
    // Überprüfen, ob die Datei existiert, andernfalls Fehlermeldung
    if (!file_exists($file)) {
        die("Fehlende Datei: $file");
    }
    include($file);
}

// Temporäre Deaktivierung der Pagelock
$closed_tmp = $closed;
$closed = 0;

// `type` aus GET-Parameter filtern
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);

// `postID` und `commentID` als Integer validieren
$postID = filter_input(INPUT_GET, 'postID', FILTER_VALIDATE_INT);
$commentID = filter_input(INPUT_GET, 'commentID', FILTER_VALIDATE_INT);

// Prüfen, ob Spam-API verfügbar ist
if (!empty($spamapikey) && !empty($type) && in_array($type, ["spam", "ham"])) {

    // Wenn `postID` vorhanden ist, Spam-API für den Beitrag verwenden
    if ($postID) {
        $get = safe_query("SELECT * FROM `forum_posts` WHERE `postID`='" . $postID . "'");
        if (mysqli_num_rows($get) > 0) {
            $ds = mysqli_fetch_array($get);

            // Prüfen, ob der Benutzer Administrator oder Moderator des Boards ist
            if (isset($userID) && (ispageadmin($userID) || ismoderator($userID, $ds['boardID']))) {
                $spamApi = \webspell\SpamApi::getInstance();
                $spamApi->learn($ds['message'], $type);
            }
        }
    }

    // Wenn `commentID` vorhanden ist, Spam-API für den Kommentar verwenden
    if ($commentID) {
        if (isset($userID) && (ispageadmin($userID) || isfeedbackadmin($userID))) {
            $get = safe_query("SELECT * FROM `comments` WHERE `commentID`='" . $commentID . "'");
            if (mysqli_num_rows($get) > 0) {
                $ds = mysqli_fetch_array($get);
                $spamApi = \webspell\SpamApi::getInstance();
                $spamApi->learn($ds['comment'], $type);
            }
        }
    }
}
