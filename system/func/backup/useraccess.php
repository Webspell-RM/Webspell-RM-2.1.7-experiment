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

function isUserInGroup($userID, $group) {
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT userID FROM `" . PREFIX . "user_groups` WHERE `userID` = " . (int)$userID . " AND `$group` = 1"
            )
        ) > 0
    );
}

function isanyadmin($userID)
{
    $groups = ['super', 'forum', 'files', 'page', 'feedback', 'news', 'news_writer', 'polls', 'clanwars', 'user', 'cash', 'gallery'];
    foreach ($groups as $group) {
        if (isUserInGroup($userID, $group)) {
            return true;
        }
    }
    return false;
}

function issuperadmin($userID)
{
    return isUserInGroup($userID, 'super');
}

function isforumadmin($userID)
{
    return isUserInGroup($userID, 'forum') || isUserInGroup($userID, 'super');
}

function isfilesadmin($userID)
{
    return isUserInGroup($userID, 'files') || isUserInGroup($userID, 'super');
}

function ispageadmin($userID)
{
    return isUserInGroup($userID, 'page') || isUserInGroup($userID, 'super');
}

function isfeedbackadmin($userID)
{
    return isUserInGroup($userID, 'feedback') || isUserInGroup($userID, 'super');
}

function isnewsadmin($userID)
{
    return isUserInGroup($userID, 'news') || isUserInGroup($userID, 'super');
}

function isnewswriter($userID)
{
    return isUserInGroup($userID, 'news_writer') || isUserInGroup($userID, 'super') || isUserInGroup($userID, 'news');
}

function ispollsadmin($userID)
{
    return isUserInGroup($userID, 'polls') || isUserInGroup($userID, 'super');
}

function isclanwarsadmin($userID)
{
    return isUserInGroup($userID, 'clanwars') || isUserInGroup($userID, 'super');
}

function ismoderator($userID, $boardID)
{
    if (empty($userID) || empty($boardID)) {
        return false;
    }

    if (!isanymoderator($userID)) {
        return false;
    }

    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    userID
                FROM
                    " . PREFIX . "plugins_forum_moderators
                WHERE
                    `userID` = " . (int)$userID . " AND
                    `boardID` = " . (int)$boardID
            )
        ) > 0
    );
}

function isanymoderator($userID)
{
    return isUserInGroup($userID, 'moderator');
}

function isuseradmin($userID)
{
    return isUserInGroup($userID, 'user') || isUserInGroup($userID, 'super');
}

function iscashadmin($userID)
{
    return isUserInGroup($userID, 'cash') || isUserInGroup($userID, 'super');
}

function isgalleryadmin($userID)
{
    return isUserInGroup($userID, 'gallery') || isUserInGroup($userID, 'super');
}

function isclanmember($userID)
{
    if (mysqli_num_rows(
        safe_query(
            "SELECT userID FROM `" . PREFIX . "plugins_squads_members` WHERE `userID` = " . (int)$userID
        )
    ) > 0
    ) {
        return true;
    } else {
        return issuperadmin($userID);
    }
}

function isjoinusmember($userID)
{
    if (mysqli_num_rows(
        safe_query(
            "SELECT userID FROM `" . PREFIX . "plugins_squads_members` WHERE `userID` = " . (int)$userID
        )
    ) > 0
    ) {
        return true;
    } else {
        return issuperadmin($userID);
    }
}

function isbanned($userID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    userID
                FROM
                    `" . PREFIX . "user`
                WHERE
                    `userID` = " . (int)$userID . " AND
                    (
                        `banned` = 'perm' OR
                        `banned` IS NOT NULL
                    )"
            )
        ) > 0
    );
}

function iscommentposter($userID, $commID)
{
    if (empty($userID) || empty($commID)) {
        return false;
    }

    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    commentID
                FROM
                    " . PREFIX . "plugins_comments
                WHERE
                    `commentID` = " . (int)$commID . " AND
                    `userID` = " . (int)$userID
            )
        ) > 0
    );
}

function isforumposter($userID, $postID)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    postID
                FROM
                    " . PREFIX . "plugins_forum_posts
                WHERE
                    `postID` = " . (int)$postID . " AND
                    `poster` = " . (int)$userID
            )
        ) > 0
    );
}

function istopicpost($topicID, $postID)
{
        $ds = mysqli_fetch_array(
            safe_query(
                "SELECT
                    postID
                FROM
                    " . PREFIX . "plugins_forum_posts
                WHERE
                    `topicID` = " . (int)$topicID . "
                ORDER BY
                    `date` ASC
                LIMIT
                    0,1"
            )
        );
        if($ds[ 'postID' ] == $postID) {
            return true;
        }
        else {
            return false;
        }
}

function isinusergrp($usergrp, $userID)
{
    if ($usergrp == 'user' && !empty($userID)) {
        return true;
    }

    if (!usergrpexists($usergrp)) {
        return false;
    }

    if (mysqli_num_rows(safe_query(
        "SELECT
                userID
            FROM
                " . PREFIX . "user_forum_groups
            WHERE
                `" . $usergrp . "` = 1 AND
                `userID` = " . (int)$userID
    )) > 0
    ) {
        return true;
    }

    return isforumadmin($userID);
}
