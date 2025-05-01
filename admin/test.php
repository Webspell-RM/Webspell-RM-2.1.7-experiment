<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Eingaben validieren und sanitieren
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Stelle sicher, dass E-Mail gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Ungültige E-Mail-Adresse.";
        header("Location: admincenter.php?site=user_roles");
        exit();
    }

    // Passwortlänge validieren (z. B. mindestens 8 Zeichen)
    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Das Passwort muss mindestens 8 Zeichen lang sein.";
        header("Location: admincenter.php?site=user_roles");
        exit();
    }

    // Überprüfe, ob die E-Mail bereits existiert
    $query = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $_database->prepare($query)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error_message'] = "Diese E-Mail-Adresse wird bereits verwendet.";
            header("Location: admincenter.php?site=user_roles");
            exit();
        }
    }

    // Benutzer in die Datenbank einfügen, um userID zu erhalten
    $query = "INSERT INTO users (username, email, registerdate) VALUES (?, ?, UNIX_TIMESTAMP())";
    if ($stmt = $_database->prepare($query)) {
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $userID = $_database->insert_id;

        // Klartext-Pepper erzeugen, über LoginSecurity
        $pepper_plain = LoginSecurity::generatePepper();

        // Pepper verschlüsseln, über LoginSecurity
        $pepper_encrypted = openssl_encrypt($pepper_plain, 'aes-256-cbc', LoginSecurity::AES_KEY, 0, LoginSecurity::AES_IV);

        // Passwort mit Pepper hashen, über LoginSecurity
        $password_with_pepper = $password . $pepper_plain;
        $password_hash = password_hash($password_with_pepper, PASSWORD_BCRYPT);

        // Passwort und Pepper in der Datenbank aktualisieren
        $query = "UPDATE users SET password_hash = ?, password_pepper = ? WHERE userID = ?";
        if ($stmt = $_database->prepare($query)) {
            $stmt->bind_param('ssi', $password_hash, $pepper_encrypted, $userID);
            $stmt->execute();

            $_SESSION['success_message'] = $_language->module['user_created_successfully'];
            header("Location: admincenter.php?site=user_roles");
            exit();
        } else {
            $_SESSION['error_message'] = $_language->module['user_creation_error'];
        }
    } else {
        $_SESSION['error_message'] = "Fehler bei der Benutzererstellung.";
    }
}