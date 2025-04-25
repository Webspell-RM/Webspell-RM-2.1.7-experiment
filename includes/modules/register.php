<?php

use webspell\SecurityHelper;

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
$ip_address = $_SERVER['REMOTE_ADDR'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$terms = isset($_POST['terms']);
$password = $_POST['password'] ?? '';
$password_repeat = $_POST['password_repeat'] ?? '';

// Versuchszähler (letzte 30 Min)
$stmt = $_database->prepare("SELECT COUNT(*) FROM register_attempts WHERE ip_address = ? AND attempt_time > (NOW() - INTERVAL 30 MINUTE)");
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$stmt->bind_result($attempt_count);
$stmt->fetch();
$stmt->close();

if ($attempt_count >= 5) {
    $_SESSION['error_message'] = "Zu viele Registrierungsversuche. Bitte versuche es später erneut.";
    header("Location: index.php?site=register");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ungültiges Formular (CSRF-Schutz).");
    }

    // reCAPTCHA prüfen (optional)
    if (!empty($_POST['g-recaptcha-response'])) {
        $response = $_POST['g-recaptcha-response'];
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=DEIN_SECRET_KEY&response={$response}&remoteip={$ip_address}");
        $captcha_result = json_decode($verify);
        if (!$captcha_result->success) {
            $_SESSION['error_message'] = "reCAPTCHA-Überprüfung fehlgeschlagen.";
            header("Location: index.php?site=register");
            exit;
        }
    }

    // Validierungen
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]{3,30}$/', $username)) {
        $_SESSION['error_message'] = "Benutzername enthält ungültige Zeichen.";
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $_SESSION['error_message'] = "Passwort muss mindestens 8 Zeichen lang sein, eine Zahl und einen Großbuchstaben enthalten.";
    } elseif ($password !== $password_repeat) {
        $_SESSION['error_message'] = "Passwörter stimmen nicht überein.";
    } elseif (!$terms) {
        $_SESSION['error_message'] = "Bitte akzeptiere die Nutzungsbedingungen.";
    }

    if (isset($_SESSION['error_message'])) {
        header("Location: index.php?site=register");
        exit;
    }

    // E-Mail bereits registriert?
    $stmt = $_database->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "E-Mail wird bereits verwendet.";
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
    $pepper_plain = SecurityHelper::generatePepper();
    $pepper_encrypted = openssl_encrypt($pepper_plain, 'aes-256-cbc', SecurityHelper::AES_KEY, 0, SecurityHelper::AES_IV);
    $password_hash = SecurityHelper::createPasswordHash($password, $email, $pepper_plain);

    $stmt = $_database->prepare("UPDATE users SET password_hash = ?, password_pepper = ? WHERE userID = ?");
    $stmt->bind_param("ssi", $password_hash, $pepper_encrypted, $userID);
    $stmt->execute();

    // Versuch loggen
    $_database->prepare("INSERT INTO register_attempts (ip_address) VALUES (?)")->bind_param("s", $ip_address)->execute();

    $_SESSION['success_message'] = "Registrierung erfolgreich.";
    header("Location: index.php?site=login");
    exit;
}



// Vorbefüllung aus Session
$values = $_SESSION['formdata'] ?? [];
unset($_SESSION['formdata']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h4 class="card-title mb-4">Registrieren</h4>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                    <?php endif; ?>

                    <form action="index.php?site=register" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                        <div class="mb-3">
                            <label for="username" class="form-label">Benutzername</label>
                            <input type="text" name="username" class="form-control" id="username" required
                                   pattern="[a-zA-Z0-9_-]{3,20}"
                                   value="<?= htmlspecialchars($values['username'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail-Adresse</label>
                            <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($email) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Passwort</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                            <small class="form-text text-muted">Mind. 8 Zeichen, ein Großbuchstabe, eine Zahl</small>
                            <div id="passwordStrength" class="mt-2 bg-secondary"></div> <!-- Hier wird die Passwortstärke angezeigt -->
                        </div>

                        <div class="mb-3">
                            <label for="password_repeat" class="form-label">Passwort wiederholen</label>
                            <input type="password" name="password_repeat" class="form-control" id="password_repeat" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required
                                   <?= isset($values['terms']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="terms">
                                Ich akzeptiere die <a href="#">Nutzungsbedingungen</a>.
                            </label>
                        </div>

                        <!-- reCAPTCHA (optional) -->
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="DEIN_SITE_KEY"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrieren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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

