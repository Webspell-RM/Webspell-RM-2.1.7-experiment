<?php

use webspell\LoginSecurity;
use webspell\Email;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $_language;
$_language->readModule('register');

// Fehler- und Feldspeicher
$form_data = $_POST ?? [];

// CSRF vorbereiten
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Formulardaten erfassen
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';
$terms = isset($_POST['terms']);
$ip_address = $_SERVER['REMOTE_ADDR'];

$registrierung_erfolgreich = false;
$isreg = false;
$message = '';

// Zu viele Registrierungsversuche?
$stmt = $_database->prepare("
    SELECT COUNT(*) FROM register_attempts 
    WHERE ip_address = ? AND attempt_time > (NOW() - INTERVAL 30 MINUTE)
");
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$stmt->bind_result($attempt_count);
$stmt->fetch();
$stmt->close();


// Registrierung verarbeiten
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ungültiges Formular (CSRF-Schutz).");
    }

    // reCAPTCHA prüfen (optional)
    /*if (!empty($_POST['g-recaptcha-response'])) {
        $response = $_POST['g-recaptcha-response'];
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=DEIN_SECRET_KEY&response={$response}&remoteip={$ip_address}");
        $captcha_result = json_decode($verify);
        if (!$captcha_result->success) {
            $_SESSION['error_message'] = "reCAPTCHA-Überprüfung fehlgeschlagen.";
            header("Location: index.php?site=register");
            exit;
        }
    }*/


    $captcha_valid = true; // Standardwert für Tests

    // Validierungen
    $errors = false;

    // Validierungen
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
        $errors = true;
    } elseif (!preg_match('/^[a-zA-Z0-9_-]{3,30}$/', $username)) {
        $_SESSION['error_message'] = "Benutzername enthält ungültige Zeichen.";
        $errors = true;
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $_SESSION['error_message'] = "Passwort muss mindestens 8 Zeichen lang sein, eine Zahl und einen Großbuchstaben enthalten.";
        $errors = true;
    } elseif ($password !== $password_repeat) {
        $_SESSION['error_message'] = "Passwörter stimmen nicht überein.";
        $errors = true;
    } elseif (!$terms) {
        $_SESSION['error_message'] = "Bitte akzeptiere die Nutzungsbedingungen.";
        $errors = true;
    }

    // E-Mail prüfen
    $stmt = $_database->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "E-Mail wird bereits verwendet.";
    }
    $stmt->close();

    // Wenn Fehler vorhanden
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: index.php?site=register");
        exit;
    }

    // Benutzer anlegen
    $avatar = 'noavatar.png'; // Standardwert für Avatar
    $role = 1; // Standardwert für Role (Normaler Benutzer)
    $is_active = 0; // Standardwert für Aktivierungsstatus (nicht aktiv bis Bestätigung)

    $stmt = $_database->prepare("INSERT INTO users (username, email, registerdate, role, is_active, avatar) VALUES (?, ?, UNIX_TIMESTAMP(), ?, ?, ?)");
    $stmt->bind_param("ssiis", $username, $email, $role, $is_active, $avatar);
    if (!$stmt->execute()) {
        die("Fehler beim Einfügen: " . $stmt->error);
    }

    $userID = $_database->insert_id;
    $pepper_plain = LoginSecurity::generatePepper();
    $pepper_encrypted = openssl_encrypt($pepper_plain, 'aes-256-cbc', LoginSecurity::AES_KEY, 0, LoginSecurity::AES_IV);
    $password_hash = LoginSecurity::createPasswordHash($password, $email, $pepper_plain);

    $stmt = $_database->prepare("UPDATE users SET password_hash = ?, password_pepper = ? WHERE userID = ?");
    $stmt->bind_param("ssi", $password_hash, $pepper_encrypted, $userID);
    $stmt->execute();

    // Versuch loggen
    $stmt = $_database->prepare("
        INSERT INTO user_register_attempts (ip_address, status, reason, username, email)
        VALUES (?, ?, ?, ?, ?)
    ");

    if ($captcha_valid && !$errors) {
        $status = 'success';
        $reason = null;
    } else {
        $status = 'failed';
        $reason = !$captcha_valid ? 'Captcha falsch' : 'Unbekannter Fehler';
    }

    $stmt->bind_param("sssss", $ip_address, $status, $reason, $username, $email);
    $stmt->execute();
    $stmt->close();

    $activation_code = bin2hex(random_bytes(32)); // 64 Zeichen sicherer Code

    $stmt = $_database->prepare("UPDATE users SET activation_code = ? WHERE userID = ?");
    $stmt->bind_param("si", $activation_code, $userID);
    $stmt->execute();

    $activation_link = 'https://' . $_SERVER['HTTP_HOST'] . '/index.php?site=activate&code=' . urlencode($activation_code);

    $settings_result = safe_query("SELECT * FROM `settings`");
    $settings = mysqli_fetch_assoc($settings_result);
    $hp_title = $settings['title'] ?? 'Webspell-RM';
    $hp_url = $settings['hpurl'] ?? 'https://' . $_SERVER['HTTP_HOST'];
    $admin_email = $settings['adminemail'] ?? 'info@' . $_SERVER['HTTP_HOST'];

    $vars = ['%username%', '%activation_link%', '%hp_title%', '%hp_url%'];
    $repl = [$username, $activation_link, $hp_title, $hp_url];

    $subject = str_replace($vars, $repl, $_language->module['mail_subject']);
    $message = str_replace($vars, $repl, $_language->module['mail_text']);
   
    $module = 'Aktiviere deinen Account';            // Modulname für Absenderbezeichnung
                      // Optional: POP3 vor SMTP verwenden (kann auch weggelassen werden, Standard ist true)
    $sendmail = Email::sendEmail($admin_email, $module, $email, $subject, $message);

    if (is_array($sendmail) && isset($sendmail['result']) && $sendmail['result'] === 'done') {
        $_SESSION['success_message'] = $_language->module['register_successful'];
        $registrierung_erfolgreich = true;
    } else {
        $_SESSION['error_message'] = 'Fehler beim Senden der Bestätigungs-E-Mail.';
    }

    $isreg = true;
}

$errormessage = '';
$successmessage = '';

// Meldung aus Session übernehmen, falls vorhanden
if (isset($_SESSION['error_message'])) {
    $errormessage = '' . $_SESSION['error_message'] . '';
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $successmessage = '' . $_SESSION['success_message'] . '';
    unset($_SESSION['success_message']);
}


if ($registrierung_erfolgreich) {
    $isreg = true;
}
// Vorbefüllung aus Session
$values = $_SESSION['formdata'] ?? [];
unset($_SESSION['formdata']);

$registration_successful = !$isreg;  // <<< Diese Zeile hinzufügen

$data_array = [
    'csrf_token' => htmlspecialchars($csrf_token),
    'error_message' => $errormessage,
    'success_message' => $successmessage,
    /*'success_message' => $registrierung_erfolgreich ? $message : '',*/
    'message_zusatz' => '',
    'isreg' => $registrierung_erfolgreich,
    'username' => $username,
    'email' => $email,
    'password_repeat' => $password_repeat,
    'recaptcha_site_key' => 'DEIN_SITE_KEY'
];

// Wenn die Registrierung abgeschlossen ist
// Wenn die Registrierung abgeschlossen ist


echo $tpl->loadTemplate("register", "content", $data_array);



?>
<!--<script src="https://www.google.com/recaptcha/api.js" async defer></script>-->

<script>
document.getElementById("password").addEventListener("input", function () {
    const strengthText = document.getElementById("passwordStrength");
    const val = this.value;
    let strength = 0;

    // Stärkeprüfungen
    if (val.length >= 8) strength++; // Mindestlänge
    if (/[A-Z]/.test(val)) strength++; // Ein Großbuchstabe
    if (/\d/.test(val)) strength++; // Eine Zahl
    if (/[\W_]/.test(val)) strength++; // Ein Sonderzeichen

    // Stärkegrade und zugehörige Farben
    const levels = ["Sehr schwach", "Schwach", "Okay", "Stark"];
    const colors = ["#f44336", "#ff9800", "#ffeb3b", "#4caf50"]; // Rot, Orange, Gelb, Grün

    // Setze den Textinhalt und die Farbe basierend auf der Stärke
    strengthText.textContent = levels[strength - 1] || "";
    strengthText.style.color = colors[strength - 1] || "#f44336"; // Standardfarbe ist Rot
});
</script>