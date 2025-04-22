<?php

use webspell\SecurityHelper;



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $_language;
$_language->readModule('register');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
        header("Location: index.php?site=register");
        exit;
    }

    $stmt = $_database->prepare("SELECT userID FROM users WHERE email = ?");
    if (!$stmt) {
        die("SELECT prepare failed: " . $_database->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "E-Mail wird bereits verwendet.";
        header("Location: index.php?site=register");
        exit;
    }

    $stmt = $_database->prepare("INSERT INTO users (username, email, registerdate) VALUES (?, ?, UNIX_TIMESTAMP())");
    if (!$stmt) {
        die("INSERT prepare failed: " . $_database->error);
    }
    $stmt->bind_param("ss", $username, $email);
    if (!$stmt->execute()) {
    echo "<pre>";
    echo "Fehler beim Einfügen:<br>";
    echo "Username: " . $username . "\n";
    echo "E-Mail: " . $email . "\n";
    echo "SQL-Fehler: " . $stmt->error . "\n";
    echo "</pre>";
    exit;
}

    $userID = $_database->insert_id;

    $pepper_plain = SecurityHelper::generatePepper();
    $pepper_encrypted = openssl_encrypt($pepper_plain, 'aes-256-cbc', SecurityHelper::AES_KEY, 0, SecurityHelper::AES_IV);
    $password_hash = SecurityHelper::createPasswordHash($password, $email, $pepper_plain);

    $stmt = $_database->prepare("UPDATE users SET password_hash = ?, password_pepper = ? WHERE userID = ?");
    if (!$stmt) {
        die("UPDATE prepare failed: " . $_database->error);
    }
    $stmt->bind_param("ssi", $password_hash, $pepper_encrypted, $userID);
    if (!$stmt->execute()) {
        die("UPDATE execute failed: " . $stmt->error);
    }

    $_SESSION['success_message'] = "Benutzer erfolgreich registriert.";
    header("Location: index.php?site=login");
    exit;
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h4 class="card-title mb-4">Registrieren</h4>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?site=register" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Benutzername</label>
                            <input type="text" name="username" class="form-control" id="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail-Adresse</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Passwort</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrieren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

