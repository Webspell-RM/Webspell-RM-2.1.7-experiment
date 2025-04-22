<?php



/*function loginCheck($email, $password) {
    global $_database;

    // E-Mail-Adresse wird überprüft (nur E-Mail, kein Benutzername)
    $email = $_database->escape_string($email);  // Sicherheitsmaßnahme: Escape der Eingabedaten
    $query = $_database->query("SELECT * FROM users WHERE email = '$email'");
    
    // Prüfen, ob ein Benutzer mit dieser E-Mail existiert
    $user = $query->fetch_assoc();

    if ($user) {
        // Überprüfen, ob das Passwort korrekt ist
        if (password_verify($password, $user['password_hash'])) {
            // Erfolgreiches Login, setze die Session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];  // Benutzername bleibt in der Session gespeichert, falls benötigt
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Session-Daten speichern (optional, je nach Bedarf)
            saveSessionToDatabase($user['userID'], $_SESSION);

            // Weiterleitung zur entsprechenden Seite basierend auf dem Referrer
            $redirect_url = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : '/admin/admincenter.php'; // Standard zur Adminseite

            // Lösche den Referrer, um Endlosschleifen zu vermeiden
            unset($_SESSION['login_redirect']);

            return (object)[
                'state' => 'success',
                'message' => 'Login erfolgreich',
                'redirect' => $redirect_url // Weiterleitung zur Zielseite
            ];
        } else {
            return (object)[
                'state' => 'failed',
                'message' => 'Falsches Passwort' // Fehlermeldung bei falschem Passwort
            ];
        }
    } else {
        return (object)[
            'state' => 'failed',
            'message' => 'Benutzer nicht gefunden' // Fehlermeldung, wenn Benutzer nicht existiert
        ];
    }
}*/

// Überprüfen des Logins
// Funktion zur Überprüfung des Logins
/*function loginCheck($email, $password) {
    global $_database;

    // Escape der Benutzereingaben, um SQL-Injektionen zu vermeiden
    $email = mysqli_real_escape_string($_database, $email);
    $password = mysqli_real_escape_string($_database, $password);

    // Abfrage des Benutzers mit dem angegebenen Email
    $query = "SELECT userID, email, password_hash, password_pepper FROM users WHERE email = '$email' LIMIT 1";
    $result = $_database->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Kombiniere das Passwort mit dem Pepper und prüfe es mit dem gespeicherten Hash
        $password_with_pepper = $password . $user['password_pepper'];

        if (password_verify($password_with_pepper, $user['password_hash'])) {
            // Erfolgreiche Anmeldung
            return (object) [
                'state' => 'success',
                'userID' => $user['userID']
            ];
        } else {
            // Falsches Passwort
            return (object) [
                'state' => 'error',
                'message' => 'Falsches Passwort.'
            ];
        }
    } else {
        // Benutzer nicht gefunden
        return (object) [
            'state' => 'error',
            'message' => 'Benutzer nicht gefunden.'
        ];
    }
}*/








// Funktion zum Speichern der Session-Daten in der Datenbank
/*function saveSessionToDatabase($userID, $sessionData) {
    global $_database;

    // Session-Daten als JSON codieren, um komplexe Daten zu speichern
    $sessionData = json_encode($sessionData);

    // Session-Daten in die Datenbank speichern
    $query = $_database->prepare("INSERT INTO user_sessions (userID, session_id, session_data) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE session_data = ?, last_activity = NOW()");
    $query->bind_param('isss', $userID, session_id(), $sessionData, $sessionData);
    $query->execute();
}*/

/*
function saveSessionToDatabase($userID, $sessionData) {
    global $_database;

    // Session-Daten als JSON codieren, um komplexe Daten zu speichern
    $sessionData = json_encode($sessionData);

    // IP-Adresse und Browser des Benutzers ermitteln
    $userIp = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];

    // Aktuellen Unix-Timestamp ermitteln
    $lastActivity = time();  // Zeitstempel der letzten Aktion

    // Session-Daten in die Datenbank speichern
    $query = $_database->prepare("INSERT INTO user_sessions (userID, session_id, user_ip, session_data, browser, last_activity) 
                                  VALUES (?, ?, ?, ?, ?, ?) 
                                  ON DUPLICATE KEY UPDATE session_data = ?, last_activity = ?");
    $query->bind_param('issssssi', $userID, session_id(), $userIp, $sessionData, $browser, $lastActivity, $sessionData, $lastActivity);
    $query->execute();
}



// Funktion zum Löschen der Session-Daten aus der Datenbank
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



// Escape-Funktion
function escape($string) {
    global $_database;
    return $_database->escape_string($string);
}
*/
/*
// Funktion für fehlgeschlagene Logins
function trackFailedLogin($userID = null, $email = null) {
    global $_database;

    $ip = $_SERVER['REMOTE_ADDR'];
    $timestamp = time();

    // Logge den fehlgeschlagenen Login-Versuch
    $insert = $_database->query("INSERT INTO failed_login_attempts (ip, time) VALUES ('$ip', $timestamp)");

    if (!$insert) {
        die("Fehler beim Speichern des Logins: " . $_database->error);
    }

    // Zähle die fehlgeschlagenen Versuche der letzten 15 Minuten
    $result = $_database->query("SELECT COUNT(*) AS attempts FROM failed_login_attempts WHERE ip = '$ip' AND time > ($timestamp - 900)");
    $row = $result->fetch_assoc();

    // Wenn mehr als 5 fehlgeschlagene Versuche, sperre die IP für 1 Stunde
    if ($row['attempts'] >= 2) {
        $reason = 'Zu viele Login-Versuche';
        $deltime = $timestamp + 3600; // 1 Stunde Sperre

        // IP in der banned_ips-Tabelle sperren
        $_database->query("
            INSERT INTO banned_ips (ip, userID, email, reason, deltime)
            VALUES (
                '" . $_database->real_escape_string($ip) . "',
                " . ($userID !== null ? (int)$userID : 'NULL') . ",
                '" . $_database->real_escape_string($email) . "',
                '" . $_database->real_escape_string($reason) . "',
                $deltime
            )
            ON DUPLICATE KEY UPDATE
                deltime = $deltime, reason = '$reason'
        ");

        // Lösche alle fehlgeschlagenen Login-Versuche von dieser IP
        $_database->query("DELETE FROM failed_login_attempts WHERE ip = '$ip'");
    }
}



// Hole die IP-Adresse des Benutzers
$ip = $_SERVER['REMOTE_ADDR'];
function isIpBanned($ip) {
    global $_database;
    $timestamp = time();
    $query = "
        SELECT * FROM banned_ips 
        WHERE ip = '" . $_database->real_escape_string($ip) . "' 
        AND (deltime = 0 OR deltime > $timestamp)
    ";
    $result = $_database->query($query);
    if ($result->num_rows > 0) {
        $bannedData = $result->fetch_assoc();
        return [
            'banned' => true,
            'reason' => $bannedData['reason'],
            'userID' => $bannedData['userID']
        ];
    }
    return ['banned' => false];
}


// Beispiel für Sperrung einer IP, wobei die userID gespeichert wird
function banIp($ip, $userID) {
    global $_database;
    $bantime = time() + 60 * 60 * 3; // 3 Stunden Sperre
    $reason = 'Brute force';

    $_database->query("
        INSERT INTO banned_ips (ip, userID, deltime, reason) 
        VALUES (
            '" . $_database->real_escape_string($ip) . "', 
            " . (int)$userID . ", 
            $bantime, 
            '" . $_database->real_escape_string($reason) . "'
        )
    ");
}

*/