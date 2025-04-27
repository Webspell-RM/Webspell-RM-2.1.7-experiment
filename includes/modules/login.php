<?php
$_language->readModule('login');
use webspell\LoginSecurity;
global $_database;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ip = $_SERVER['REMOTE_ADDR'];
$message_zusatz = '';
$isIpBanned = '';
$is_active ='';
$is_locked = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password_hash = $_POST['password_hash'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
        header("Location: index.php?site=login");
        exit;
    }

    // Login-Versuch prüfen
    $loginResult = LoginSecurity::verifyLogin($email, $password_hash, $ip, $is_active, $is_locked);

    if ($loginResult['success']) {
        // Überprüfen, ob die IP des Benutzers gebannt ist
        $ip = $_SERVER['REMOTE_ADDR'];  // IP des Benutzers
        if (LoginSecurity::isIpBanned($ip)) {
            $message = '<div class="alert alert-danger" role="alert">Diese IP-Adresse wurde gesperrt. Bitte kontaktiere den Support.</div>';
            $isIpBanned = true;
        }

        // Hole die Benutzerdaten aus der Datenbank
        $stmt = $_database->prepare("SELECT userID, username, email, is_locked FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Überprüfen, ob der Benutzer gefunden wurde und ob er gesperrt ist
        if ($user) {
            if (!empty($user['is_locked']) && (int)$user['is_locked'] === 1) {
                // Konto ist gesperrt
                $message = '<div class="alert alert-danger" role="alert">Dein Konto wurde gesperrt. Bitte kontaktiere den Support.</div>';
                $isIpBanned = true;
            } else {
                // Benutzerinformationen in der Session speichern
                $_SESSION['userID'] = (int)$user['userID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Session speichern
                LoginSecurity::saveSession($user['userID']);

                $_SESSION['success_message'] = "Login erfolgreich!";
                header("Location: index.php");
                exit;
            }
        } else {
            // Benutzer nicht gefunden
            $message = '<div class="alert alert-danger" role="alert">Benutzer nicht gefunden oder falsche E-Mail-Adresse.</div>';
        }

    } else {
        // Benutzerfehlerbehandlung bei Login
        $userID = null;
        $stmt = $_database->prepare("SELECT userID, is_active FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $userID = (int)$row['userID'];

            // Überprüfen, ob der Benutzer aktiviert ist
            if ((int)$row['is_active'] === 0) {
                $message = '<div class="alert alert-danger" role="alert">Dein Konto wurde noch nicht aktiviert. Bitte überprüfe deine E-Mail.</div>';
                $isIpBanned = true;
            } else {
                // Account ist aktiv -> Login prüfen oder fehlgeschlagenen Login behandeln
                if (!LoginSecurity::isEmailOrIpBanned($email, $ip)) {
                    LoginSecurity::trackFailedLogin($userID, $email, $ip);
                    
                    // Anzahl der Versuche abrufen
                    $failCount = LoginSecurity::getFailCount($ip, $email);
                    if ($failCount >= 5) {
                        LoginSecurity::banIp($ip, $userID, "Zu viele Fehlversuche", $email);
                        $_SESSION['error_message'] = "Zu viele Fehlversuche – Deine IP wurde gesperrt.";
                    } else {
                        $_SESSION['error_message'] = "Falsche E-Mail oder Passwort. Versuche: $failCount / 5";
                    }
                } else {
                    $message = '<div class="alert alert-danger" role="alert">Diese E-Mail-Adresse oder IP wurde gesperrt. Bitte kontaktiere den Support.</div>';
                    $isIpBanned = true;
                }
            }
        } else {
            $message = '<div class="alert alert-danger" role="alert">Benutzer nicht gefunden oder falsche E-Mail.</div>';
            $isIpBanned = true;
        }
    }

    // Überprüfen, ob eine Fehlermeldung in der Session gesetzt wurde
    if (isset($_SESSION['error_message'])) {
        $message = '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }

}

// Prüfen, ob E-Mail gebannt ist
if (!empty($email)) {
    $isEmailBanned = LoginSecurity::isEmailBanned($ip, $email);
} else {
    $isEmailBanned = false; // Falls keine E-Mail gesetzt ist, kein Bann überprüfen
}

if ($isEmailBanned) {
    $message = '<div class="alert alert-danger" role="alert">Diese E-Mail-Adresse wurde gesperrt. Bitte kontaktiere den Support.</div>';
    $isIpBanned = true; // Setze hier isIpBanned auf true, da die E-Mail ebenfalls gesperrt ist
}

// Formular anzeigen
$data_array = [
    'login_headline' => $_language->module['title'],    
    'email_label' => $_language->module['email_label'],
    'your_email' => $_language->module['your_email'],
    'pass_label' => $_language->module['pass_label'],
    'your_pass' => $_language->module['your_pass'],
    'remember_me' => $_language->module['remember_me'],
    'login_button' => $_language->module['login_button'],
    'register_link' => $_language->module['register_link'],
    'lostpassword_link' => $_language->module['lostpassword_link'],
    'error_message' => $message,
    'message_zusatz' => $message_zusatz,
    'isIpBanned' => $isIpBanned,
];

echo $tpl->loadTemplate("login", "content", $data_array);