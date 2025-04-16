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

namespace webspell;

/**
 * Verbesserte, sichere Login-Cookie-Implementierung.
 * 
 * - Generiert bei jedem Login einen zufälligen Schlüssel.
 * - Speichert einen Hash des Schlüssels in der Datenbank.
 * - Cookies verfallen nach einer gewissen Zeit automatisch.
 */
class LoginCookie
{
    /**
     * Erstellt einen SHA-512 Hash eines gegebenen Schlüssels.
     */
    private static function generateHash($key)
    {
        return hash('sha512', $key, true);
    }

    /**
     * Generiert einen zufälligen Schlüssel der angegebenen Länge (Standard: 64 Bytes).
     */
    private static function generateKey($length = 64)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            return openssl_random_pseudo_bytes($length);
        }

        $key = '';
        while ($length-- > 0) {
            $key .= pack('C', mt_rand(0, 255));
        }

        return $key;
    }

    /**
     * Setzt einen sicheren HTTP-Cookie.
     */
    private static function setCookie($cookieName, $cookieValue, $cookieExpire)
    {
        $params = session_get_cookie_params();
        setcookie(
            $cookieName,
            $cookieValue,
            $cookieExpire,
            $params['path'],
            $params['domain'],
            $params['secure'],
            true // HttpOnly
        );
    }

    /**
     * Erstellt und speichert ein Login-Cookie inkl. DB-Eintrag.
     */
    public static function set($cookieName, $user, $expiration)
    {
        global $_database;

        $key  = self::generateKey();
        $hash = self::generateHash($key);
        $cookieValue  = $user . ':' . base64_encode($key);
        $cookieExpire = ($expiration > 0) ? time() + $expiration : 0;

        safe_query("
            INSERT INTO `cookies`
                (`userID`, `cookie`, `expiration`)
            VALUES (
                " . (int)$user . ",
                '" . escapestring($hash) . "',
                " . (int)$cookieExpire . "
            )
        ");

        self::setCookie($cookieName, $cookieValue, $cookieExpire);
    }

    /**
     * Löscht das Login-Cookie sowie den zugehörigen Datenbankeintrag.
     */
    public static function clear($cookieName)
    {
        global $_database;

        if (!isset($_COOKIE[$cookieName])) {
            return;
        }

        $authent = explode(':', $_COOKIE[$cookieName]);
        if (count($authent) < 2) {
            return;
        }

        $user = (int)$authent[0];
        $key  = base64_decode($authent[1]);
        $hash = self::generateHash($key);

        safe_query("
            DELETE FROM `cookies`
            WHERE `userID` = " . $user . "
              AND `cookie` = '" . escapestring($hash) . "'
        ");

        // Cookie entfernen (Vergangenheit setzen)
        self::setCookie($cookieName, '', time() - 86400);
    }

    /**
     * Überprüft das Login-Cookie und meldet den Benutzer an.
     */
    public static function check($cookieName)
    {
        global $_database, $userID, $loggedin, $_language;

        if (!isset($_COOKIE[$cookieName])) {
            return;
        }

        $authent = explode(':', $_COOKIE[$cookieName]);
        if (count($authent) < 2) {
            return;
        }

        $user = (int)$authent[0];
        $key  = base64_decode($authent[1]);
        $hash = self::generateHash($key);

        $result = safe_query("
            SELECT u.`userID`, u.`language`, u.`lastlogin`
            FROM `cookies` c
            INNER JOIN `users` u ON c.`userID` = u.`userID`
            WHERE c.`userID` = " . $user . "
              AND c.`cookie` = '" . escapestring($hash) . "'
              AND c.`expiration` > " . time() . "
        ");

        if ($result && $row = $result->fetch_assoc()) {
            $loggedin = true;
            $userID = $row['userID'];

            $_SESSION['ws_user'] = $userID;
            $_SESSION['userID'] = $userID;
            $_SESSION['ws_lastlogin'] = $row['lastlogin'];

            $language = $row['language'];
            if (!empty($language) && isset($_language)) {
                if ($_language->setLanguage($language)) {
                    $_SESSION['language'] = $language;
                }
            }

            $result->free();
        }
    }

    /**
     * Entfernt abgelaufene Cookies aus der Datenbank.
     */
    public static function purge()
    {
        safe_query("
            DELETE FROM `cookies`
            WHERE `expiration` < " . time()
        );
    }
}

// Initialisierung globaler Login-Status
global $userID, $loggedin;

$userID = 0;
$loggedin = false;

// Prüfung: Ist Benutzer bereits eingeloggt (Session oder Cookie)
if (isset($_SESSION['ws_user'])) {
    $userID = (int)$_SESSION['ws_user'];
    $loggedin = true;
} elseif (isset($_COOKIE['ws_auth'])) {
    LoginCookie::purge();
    LoginCookie::check('ws_auth');
}
