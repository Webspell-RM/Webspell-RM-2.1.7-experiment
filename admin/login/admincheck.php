<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */



chdir("../../");

// Fehlerzähler initialisieren
$err = 0;

// Systemdateien einbinden, falls vorhanden
if (file_exists("system/sql.php")) {
    include("system/sql.php");
} else {
    $err++;
}
if (file_exists("system/settings.php")) {
    include("system/settings.php");
} else {
    $err++;
}

// Temporär Wartungsmodus deaktivieren
$closed_tmp = $closed;
$closed = 0;

if (file_exists("system/functions.php")) {
    include("system/functions.php");
} else {
    $err++;
}

// Verzögerung bei falschem Passwort aktivieren?
$sleep = 1;

// Prüfen, ob es sich um eine AJAX-Anfrage handelt
$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

// Rückgabeobjekt vorbereiten
$return = new stdClass();
$return->state = "failed";
$return->message = "";
$reenter = false;

// IP auf Bannliste prüfen
$get = safe_query("SELECT * FROM `banned_ips` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
if (mysqli_num_rows($get) == 0) {
    
    $ws_user = $_POST['ws_user'];
    
    // Benutzer anhand E-Mail prüfen
    $check = safe_query("SELECT * FROM `users` WHERE `email` = '" . $ws_user . "'");
    $anz = mysqli_num_rows($check);
    $login = 0;

    if ($anz) {
        // Aktivierte Benutzer prüfen
        $check = safe_query("SELECT * FROM `users` WHERE `email` = '" . $ws_user . "' AND `activated` = '1'");
        if (mysqli_num_rows($check)) {
            $ds = mysqli_fetch_array($check);
            $login = 0;

            // Passwort im alten Format prüfen
            $ws_pwd = generatePasswordHash(stripslashes($_POST['password']));
            if (!empty($ds['password']) && !empty($_POST['password'])) {
                if (hash_equals($ws_pwd, $ds['password'])) {
                    // Migration auf neues Passwort-Hash-System
                    $new_pepper = Gen_PasswordPepper();
                    Set_PasswordPepper($new_pepper, $ds['userID']);
                    $pass = Gen_PasswordHash($_POST['password'], $ds['userID']);
                    safe_query("UPDATE `users` SET `password` = '', `password_hash` = '" . $pass . "' WHERE `userID` = '" . intval($ds['userID']) . "' LIMIT 1");
                } else {
                    // Bei falschem Passwort Sperrversuch protokollieren
                    if ($sleep) sleep(3);
                    
                    $get = safe_query("SELECT `wrong` FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                    if (mysqli_num_rows($get)) {
                        safe_query("UPDATE `failed_login_attempts` SET `wrong` = `wrong` + 1 WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                    } else {
                        safe_query("INSERT INTO `failed_login_attempts` (`ip`, `wrong`) VALUES ('" . $GLOBALS['ip'] . "', 1)");
                    }

                    $get = safe_query("SELECT `wrong` FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                    if (mysqli_num_rows($get)) {
                        $ban = mysqli_fetch_assoc($get);
                        if ($ban['wrong'] == $max_wrong_pw) {
                            $bantime = time() + (60 * 60 * 3); // 3 Stunden Bann
                            safe_query("INSERT INTO `banned_ips` (`ip`, `deltime`, `reason`) VALUES ('" . $GLOBALS['ip'] . "', " . $bantime . ", 'Possible brute force attack')");
                            safe_query("DELETE FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                        }
                    }
                    $reenter = true;
                    $return->message = $_language->module['invalid_password'];
                    $return->code = 'invalid_password';
                }
            }

            // Neues Passwort prüfen
            $ws_pwd = stripslashes($_POST['password']) . $ds['password_pepper'];
            $valid = password_verify($ws_pwd, $ds['password_hash']);
            if ($valid) {
                // Login erfolgreich
                $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];

                if (isset($_SESSION['ws_sessiontest'])) {
                    unset($_SESSION['ws_sessiontest']);
                }

                \webspell\LoginCookie::set('ws_auth', $ds['userID'], $sessionduration * 60 * 60);

                safe_query("DELETE FROM `whoisonline` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                safe_query("DELETE FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");

                $return->state = "success";
                $return->message = $_language->module['login_successful'];
            } else {
                if ($sleep) sleep(3);

                $get = safe_query("SELECT `wrong` FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                if (mysqli_num_rows($get)) {
                    safe_query("UPDATE `failed_login_attempts` SET `wrong` = `wrong` + 1 WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                } else {
                    safe_query("INSERT INTO `failed_login_attempts` (`ip`, `wrong`) VALUES ('" . $GLOBALS['ip'] . "', 1)");
                }

                $get = safe_query("SELECT `wrong` FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                if (mysqli_num_rows($get)) {
                    $ban = mysqli_fetch_assoc($get);
                    if ($ban['wrong'] == $max_wrong_pw) {
                        $bantime = time() + (60 * 60 * 3);
                        safe_query("INSERT INTO `banned_ips` (`ip`, `deltime`, `reason`) VALUES ('" . $GLOBALS['ip'] . "', " . $bantime . ", 'Possible brute force attack')");
                        safe_query("DELETE FROM `failed_login_attempts` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
                    }
                }

                $reenter = true;
                $return->message = $_language->module['invalid_password'];
                $return->code = 'invalid_password';
            }

        } else {
            $return->message = $_language->module['not_activated'];
            $return->code = 'not_activated';
        }

    } else {
        $return->code = 'no_user';
        $reenter = true;
    }
}

// AJAX-Rückgabe oder Weiterleitung
if ($ajax === true) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    echo json_encode($return);
} else {
    header("Location: $_modulepath/admin/admincenter.php");
}
