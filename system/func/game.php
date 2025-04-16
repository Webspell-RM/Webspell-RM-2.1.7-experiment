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

/**
 * Gibt die Anzahl der Kommentare zu einem bestimmten CW zurück
 *
 * @param int $cwID - Die ID des CWs
 * @return int - Anzahl der Kommentare
 */
function getanzcwcomments($cwID)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `plugins_comments` WHERE `parentID` = " . (int)$cwID . " AND `type` = 'cw'"
        )
    );
}

/**
 * Gibt eine HTML-Option-Liste aller Squads zurück
 *
 * @return string - HTML-Optionen für alle Squads
 */
function getsquads()
{
    $squads = "";
    $result = safe_query("SELECT * FROM `plugins_squads`");

    while ($ds = mysqli_fetch_array($result)) {
        $squads .= '<option value="' . $ds['squadID'] . '">' . htmlspecialchars($ds['name']) . '</option>';
    }

    return $squads;
}

/**
 * Gibt eine HTML-Option-Liste aller Squads zurück, die als "Gamesquads" markiert sind
 *
 * @return string - HTML-Optionen für alle Gamesquads
 */
function getgamesquads()
{
    $squads = '';
    $result = safe_query("SELECT * FROM `plugins_squads` WHERE `gamesquad` = 1");

    while ($ds = mysqli_fetch_array($result)) {
        $squads .= '<option value="' . $ds['squadID'] . '">' . htmlspecialchars($ds['name']) . '</option>';
    }

    return $squads;
}

/**
 * Gibt den Namen eines Squads basierend auf der SquadID zurück
 *
 * @param int $squadID - Die ID des Squads
 * @return string - Der Name des Squads
 */
function getsquadname($squadID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT `name` FROM `plugins_squads` WHERE `squadID` = " . (int)$squadID
        )
    );
    return @$ds['name'];
}

/**
 * Überprüft, ob ein Benutzer Mitglied eines bestimmten Squads ist
 *
 * @param int $userID - Die ID des Benutzers
 * @param int $squadID - Die ID des Squads
 * @return bool - True, wenn der Benutzer Mitglied des Squads ist, sonst False
 */
function issquadmember($userID, $squadID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `sqmID` FROM `plugins_squads_members` WHERE `userID` = " . (int)$userID . " AND `squadID` = " . (int)$squadID
            )
        ) > 0
    );
}

/**
 * Überprüft, ob ein Squad als "Gamesquad" markiert ist
 *
 * @param int $squadID - Die ID des Squads
 * @return bool - True, wenn es sich um ein Gamesquad handelt, sonst False
 */
function isgamesquad($squadID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT `squadID` FROM `plugins_squads` WHERE `squadID` = " . (int)$squadID . " AND `gamesquad` = 1"
            )
        ) > 0
    );
}

/**
 * Gibt den Namen eines Spiels basierend auf dem Tag zurück
 *
 * @param string $tag - Das Tag des Spiels
 * @return string - Der Name des Spiels
 */
function getgamename($tag)
{
    $get = safe_query("SELECT `name` FROM `plugins_games_pic` WHERE `tag` = '$tag'");

    if (mysqli_num_rows($get) > 0) {
        $ds = mysqli_fetch_array($get);
        return $ds['name'];
    } else {
        return '';
    }
}

/**
 * Überprüft, ob ein bestimmtes Spiel-Tag existiert
 *
 * @param string $tag - Das Tag des Spiels
 * @return bool - True, wenn das Tag existiert, sonst False
 */
function is_gametag($tag)
{
    return (mysqli_num_rows(safe_query("SELECT `name` FROM `plugins_games_pic` WHERE `tag` = '$tag'")) > 0);
}

/**
 * Gibt eine HTML-Option-Liste aller Spiele zurück
 *
 * @param string|null $selected - Das Tag des standardmäßig ausgewählten Spiels (optional)
 * @return string - HTML-Optionen für alle Spiele
 */
function getGamesAsOptionList($selected = null)
{
    $gamesa = safe_query("SELECT * FROM `plugins_games_pic` ORDER BY `name`");
    $list = "";

    while ($ds = mysqli_fetch_array($gamesa)) {
        if ($ds['tag'] == $selected) {
            $list .= '<option value="' . $ds['tag'] . '" selected="selected">' . htmlspecialchars($ds['name']) . '</option>';
        } else {
            $list .= '<option value="' . $ds['tag'] . '">' . htmlspecialchars($ds['name']) . '</option>';
        }
    }

    return $list;
}
