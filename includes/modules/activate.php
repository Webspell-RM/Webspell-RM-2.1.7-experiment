<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $_language;
$_language->readModule('register');

echo '<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h4 class="card-title mb-4">Registrieren</h4>';

$code = $_GET['code'] ?? '';

if (!empty($code)) {
    // 1. E-Mail des aktivierten Users holen
    $stmt = $_database->prepare("SELECT email FROM users WHERE activation_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($email);
        $stmt->fetch();

        // 2. Account aktivieren
        $stmt_update = $_database->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE email = ?");
        $stmt_update->bind_param("s", $email);
        $stmt_update->execute();

        // 3. Einträge in user_register_attempts löschen
        $stmt_delete = $_database->prepare("DELETE FROM user_register_attempts WHERE email = ?");
        $stmt_delete->bind_param("s", $email);
        $stmt_delete->execute();

        echo '<div class="alert alert-success" role="alert">Konto erfolgreich aktiviert. Du kannst dich jetzt einloggen.</div>';
        redirect("index.php?site=login", "", 3);
    } else {
        echo '<div class="alert alert-danger" role="alert">Ungültiger oder abgelaufener Aktivierungslink.</div>';
        redirect("index.php", "", 3);
    }
} else {
    echo '<div class="alert alert-danger" role="alert">Kein Aktivierungscode angegeben.</div>';
    redirect("index.php", "", 3);
}

echo '
                </div>
            </div>
        </div>
    </div>
</div>';

die();
