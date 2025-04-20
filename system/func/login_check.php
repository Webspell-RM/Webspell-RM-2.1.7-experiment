<?php

/*function loginCheck($username, $password) {
    global $_database;

    // Benutzer anhand des Benutzernamens oder der E-Mail abrufen
    $query = $_database->query("SELECT * FROM users WHERE username = '" . $_database->escape_string($username) . "' OR email = '" . $_database->escape_string($username) . "'");
    $user = $query->fetch_assoc();

    // Überprüfen, ob der Benutzer existiert
    if ($user) {
        // Passwort überprüfen
        if (password_verify($password, $user['password_hash'])) {
            // Erfolgreiches Login, setze die Session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Wenn du die Rolle des Benutzers speichern möchtest

            // Weiterleitung zur entsprechenden Seite basierend auf dem Referrer
            $redirect_url = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : '/admin/admincenter.php'; // Standard zu admincenter.php

            // Lösche den Referrer, um Endlosschleifen zu vermeiden
            unset($_SESSION['login_redirect']);

            return (object)[
                'state' => 'success',
                'message' => 'Login erfolgreich',
                'redirect' => $redirect_url // Weiterleitung zur entsprechenden Seite
            ];
        } else {
            // Fehler: Passwort stimmt nicht überein
            return (object)[
                'state' => 'failed',
                'message' => 'Falsches Passwort'
            ];
        }
    } else {
        // Fehler: Benutzer nicht gefunden
        return (object)[
            'state' => 'failed',
            'message' => 'Benutzer nicht gefunden'
        ];
    }
}*/

function loginCheck($username, $password) {
    global $_database;

    // Benutzer anhand des Benutzernamens oder der E-Mail abrufen
    $query = $_database->query("SELECT * FROM users WHERE username = '" . $_database->escape_string($username) . "' OR email = '" . $_database->escape_string($username) . "'");
    $user = $query->fetch_assoc();

    // Überprüfen, ob der Benutzer existiert
    if ($user) {
        // Passwort überprüfen
        if (password_verify($password, $user['password_hash'])) {
            // Erfolgreiches Login, setze die Session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Session-Daten speichern
            saveSessionToDatabase($user['userID'], $_SESSION);
            // Weiterleitung zur entsprechenden Seite basierend auf dem Referrer
            $redirect_url = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : '/admin/admincenter.php'; // Standard zu admincenter.php

            // Lösche den Referrer, um Endlosschleifen zu vermeiden
            unset($_SESSION['login_redirect']);

            return (object)[
                'state' => 'success',
                'message' => 'Login erfolgreich',
                'redirect' => $redirect_url // Weiterleitung zur entsprechenden Seite
            ];
        } else {
            return (object)[
                'state' => 'failed',
                'message' => 'Falsches Passwort'
            ];
        }
    } else {
        return (object)[
            'state' => 'failed',
            'message' => 'Benutzer nicht gefunden'
        ];
    }
}





function saveSessionToDatabase($userID, $sessionData) {
    global $_database;

    // Session-Daten als JSON codieren, um komplexe Daten zu speichern
    $sessionData = json_encode($sessionData);

    // Session-Daten in die Datenbank speichern
    $query = $_database->prepare("INSERT INTO user_sessions (userID, session_id, session_data) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE session_data = ?, last_activity = NOW()");
    $query->bind_param('isss', $userID, session_id(), $sessionData, $sessionData);
    $query->execute();
}

function deleteSessionFromDatabase($userID) {
    global $_database;

    // Die Session-Daten aus der Datenbank löschen
    $query = $_database->prepare("DELETE FROM user_sessions WHERE userID = ?");
    $query->bind_param('i', $userID);
    $query->execute();
}

function getSessionFromDatabase($sessionId) {
    global $_database;

    // Die Session-Daten aus der Datenbank abrufen
    $query = $_database->prepare("SELECT session_data FROM user_sessions WHERE session_id = ?");
    $query->bind_param('s', $sessionId);
    $query->execute();
    $result = $query->get_result();
    $sessionData = $result->fetch_assoc();

    if ($sessionData) {
        return json_decode($sessionData['session_data'], true); // Rückgabe der Session-Daten als Array
    } else {
        return null;
    }
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
