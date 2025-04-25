<?php
$_language->readModule('login');
use webspell\SecurityHelper;
global $_database;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ip = $_SERVER['REMOTE_ADDR'];
$message_zusatz = '';
$isIpBanned = '';
$is_active ='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
        header("Location: index.php?site=login");
        exit;
    }

   
    // Login-Versuch prüfen
    $loginResult = SecurityHelper::verifyLogin($email, $password, $ip, $is_active);

    if ($loginResult['success']) {
        // Überprüfen, ob die IP des Benutzers gebannt ist
        $ip = $_SERVER['REMOTE_ADDR'];  // IP des Benutzers
        if (SecurityHelper::isIpBanned($ip)) {
            #$_SESSION['error_message'] = "Deine IP-Adresse ist gesperrt. Bitte versuche es später noch einmal.";
            $message = '<div class="alert alert-danger" role="alert">Diese E-Mail-Adresse wurde gesperrt. Bitte kontaktiere den Support.</div>';
            $isIpBanned = true;
        }

        // Hole die Benutzerdaten aus der Datenbank
        $stmt = $_database->prepare("SELECT userID, username, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Benutzerinformationen in der Session speichern
        $_SESSION['userID'] = (int)$user['userID'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Session speichern
        SecurityHelper::saveSession($user['userID']);

        $_SESSION['success_message'] = "Login erfolgreich!";
        header("Location: index.php");
        exit;
    } else {
        // userID auslesen (falls vorhanden)
        $userID = null;
        $stmt = $_database->prepare("SELECT userID, is_active FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $userID = (int)$row['userID'];
        }

        if ((int)$row['is_active'] === 0) {
            $message = '<div class="alert alert-danger" role="alert">Dein Konto wurde noch nicht aktiviert. Bitte überprüfe deine E-Mail.</div>';
            $isIpBanned = true;
        } else {

            // Fehlversuch protokollieren
            if (!SecurityHelper::isEmailOrIpBanned($email, $ip)) {
                SecurityHelper::trackFailedLogin($userID, $email, $ip);
                
                // Anzahl der Versuche abrufen
                $failCount = SecurityHelper::getFailCount($ip, $email);
                // Überprüfen, ob eine Fehlermeldung in der Session gesetzt wurde
                if ($failCount >= 5) {
                    SecurityHelper::banIp($ip, $userID, "Zu viele Fehlversuche", $email);
                    
                    $_SESSION['error_message'] = "Zu viele Fehlversuche – Deine IP wurde gesperrt.";
                } else {
                    $_SESSION['error_message'] = "Falsche E-Mail oder Passwort. Versuche: $failCount / 5";
                }

            }else {
                $message = '<div class="alert alert-danger" role="alert">Diese E-Mail-Adresse oder ID wurde gesperrt. Bitte kontaktiere den Support.</div>';
                $isIpBanned = true; // Setze hier isIpBanned auf true, da die E-Mail ebenfalls gesperrt ist
            }
        }    
        // Überprüfen, ob eine Fehlermeldung in der Session gesetzt wurde
        if (isset($_SESSION['error_message'])) {
            // Speichern der Fehlermeldung in $message
            $message = '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            // Lösche die Fehlermeldung nach der Anzeige
            unset($_SESSION['error_message']);
        }
    }
}



// Prüfen, ob E-Mail gebannt ist
if (!empty($email)) {
    $isEmailBanned = SecurityHelper::isEmailBanned($ip, $email);
} else {
    $isEmailBanned = false; // Falls keine E-Mail gesetzt ist, kein Bann überprüfen
}

if ($isEmailBanned) {
    $message = '<div class="alert alert-danger" role="alert">Diese E-Mail-Adresse wurde gesperrt. Bitte kontaktiere den Support.</div>';
    $isIpBanned = true; // Setze hier isIpBanned auf true, da die E-Mail ebenfalls gesperrt ist
}
 // Formular anzeigen
   $data_array = [
    'login_headline' => $_language->module['title'],  // Beispiel, anpassen
    
    'email_label' => $_language->module['email_label'],
    'your_email' => $_language->module['your_email'],
    'pass_label' => $_language->module['pass_label'],
    'your_pass' => $_language->module['your_pass'],

    'remember_me' => $_language->module['remember_me'],
    'login_button' => $_language->module['login_button'],
    'register_link' => $_language->module['register_link'],
    'lostpassword_link' => $_language->module['lostpassword_link'],
    'error_message' => $message,  // Hier kannst du Fehlernachrichten dynamisch setzen, falls erforderlich
    'message_zusatz'     => $message_zusatz,
    'isIpBanned'         => $isIpBanned,
];

    echo $tpl->loadTemplate("login", "content", $data_array);










