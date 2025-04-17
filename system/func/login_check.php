<?php
function loginCheck($ws_user, $password, $sleep = 1)
{
    global $_database;
    $return = new stdClass();
    $return->state = "failed";
    $return->message = "";

    $ip = $_SERVER['REMOTE_ADDR'];
    $max_wrong_pw = 5;
    $sessionduration = 6; // in Stunden

    // IP gebannt?
    $res = safe_query("SELECT * FROM banned_ips WHERE ip = '" . $_database->escape_string($ip) . "'");
    if (mysqli_num_rows($res)) {
        $return->message = "Diese IP wurde gesperrt.";
        return $return;
    }

    // Benutzer prüfen (E-Mail oder Username erlaubt)
    $ws_user_escaped = $_database->escape_string($ws_user);
    $check = safe_query("SELECT * FROM users WHERE email = '$ws_user_escaped' OR username = '$ws_user_escaped'");

    if (!mysqli_num_rows($check)) {
        $return->message = "Benutzer nicht gefunden.";
        return $return;
    }

    $user = mysqli_fetch_assoc($check);

    // Account aktiviert?
    if ($user['activated'] != 1) {
        $return->message = "Account ist nicht aktiviert.";
        return $return;
    }

    // Altes Passwort-System prüfen (optional)
    if (!empty($user['password'])) {
        $old_hash = generatePasswordHash($password);
        if ($old_hash === $user['password']) {
            $pepper = Gen_PasswordPepper();
            $new_hash = Gen_PasswordHash($password, $pepper);
            safe_query("UPDATE users SET password='', password_hash='" . $_database->escape_string($new_hash) . "', password_pepper='" . $_database->escape_string($pepper) . "' WHERE userID=" . (int)$user['userID']);
            $user['password'] = '';
            $user['password_hash'] = $new_hash;
            $user['password_pepper'] = $pepper;
        }
    }

    // Passwort prüfen (neues System)
    $combined = $password . $user['password_pepper'];
    if (password_verify($combined, $user['password_hash'])) {
        // Erfolgreich
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['username'] = $user['username'];

        // Cookie setzen (optional)
        \webspell\LoginCookie::set('ws_auth', $user['userID'], $sessionduration * 60 * 60);

        // IP-Eintrag löschen
        safe_query("DELETE FROM failed_login_attempts WHERE ip = '" . $_database->escape_string($ip) . "'");

        $return->state = "success";
        $return->message = "Login erfolgreich.";
        return $return;
    }

    // Passwort falsch → Fehlversuch zählen
    if ($sleep) sleep(3);

    $check_failed = safe_query("SELECT * FROM failed_login_attempts WHERE ip = '" . $_database->escape_string($ip) . "'");
    if (mysqli_num_rows($check_failed)) {
        safe_query("UPDATE failed_login_attempts SET wrong = wrong + 1 WHERE ip = '" . $_database->escape_string($ip) . "'");
    } else {
        safe_query("INSERT INTO failed_login_attempts (ip, wrong) VALUES ('" . $_database->escape_string($ip) . "', 1)");
    }

    $entry = safe_query("SELECT wrong FROM failed_login_attempts WHERE ip = '" . $_database->escape_string($ip) . "'");
    $fail = mysqli_fetch_assoc($entry);

    if ($fail['wrong'] >= $max_wrong_pw) {
        $bantime = time() + 3 * 60 * 60; // 3 Stunden
        safe_query("INSERT INTO banned_ips (ip, deltime, reason) VALUES ('" . $_database->escape_string($ip) . "', $bantime, 'Brute Force Verdacht')");
        safe_query("DELETE FROM failed_login_attempts WHERE ip = '" . $_database->escape_string($ip) . "'");
        $return->message = "Zu viele Fehlversuche. IP wurde gesperrt.";
    } else {
        $return->message = "Falsches Passwort.";
    }

    return $return;
}

// Funktion für fehlgeschlagene Logins
function trackFailedLogin() {
    global $max_wrong_pw;
    $ip = $GLOBALS['ip'];
    $get = safe_query("SELECT wrong FROM failed_login_attempts WHERE ip = '$ip'");
    if (mysqli_num_rows($get)) {
        safe_query("UPDATE failed_login_attempts SET wrong = wrong + 1 WHERE ip = '$ip'");
    } else {
        safe_query("INSERT INTO failed_login_attempts (ip, wrong) VALUES ('$ip', 1)");
    }

    $get = safe_query("SELECT wrong FROM failed_login_attempts WHERE ip = '$ip'");
    $ban = mysqli_fetch_assoc($get);
    if ($ban['wrong'] >= $max_wrong_pw) {
        $bantime = time() + 60 * 60 * 3;
        safe_query("INSERT INTO banned_ips (ip, deltime, reason) VALUES ('$ip', $bantime, 'Brute force')");
        safe_query("DELETE FROM failed_login_attempts WHERE ip = '$ip'");
    }
}
?>
