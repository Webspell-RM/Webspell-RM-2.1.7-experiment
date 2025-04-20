<?php

#session_start();

// Wenn der Benutzer bereits eingeloggt ist, weiterleiten zum Admincenter oder zur vorherigen Seite
if (isset($_SESSION['userID'])) {
    $redirect_url = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : 'index.php';
    header("Location: " . $redirect_url);
    exit;
}

$message = null;

// Wenn ein POST-Login-Versuch gemacht wird
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ws_user'], $_POST['password'])) {
    $ws_user = trim($_POST['ws_user']);
    $password = $_POST['password'];

    // loginCheck-Funktion aufrufen, die den Benutzer validiert
    $result = loginCheck($ws_user, $password);

    if ($result->state == "success") {
        // Weiterleitung zur Seite, die der Benutzer nach dem Login sehen soll
        $redirect_url = isset($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : 'index.php';
        unset($_SESSION['login_redirect']); // Redirect-URL löschen, um unnötige Weiterleitungen zu verhindern
        header("Location: " . $redirect_url);
        exit;
    } else {
        // Fehlermeldung anzeigen, wenn Login fehlgeschlagen ist
        $message = $result->message;
    }
}

/*if ($result->state == "success") {
    // Setze das Cookie
    setcookie("user_cookie", $_SESSION['userID'], time() + 3600, "/");  // Beispiel: Speichern des userID-Cookies

    // Jetzt kannst du das Cookie in der Datenbank speichern
    $query = $_database->prepare("INSERT INTO user_cookies (userID, cookie_value) VALUES (?, ?)");
    $query->bind_param("is", $_SESSION['userID'], $_SESSION['userID']);
    $query->execute();
}*/


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Login</h2>

            <?php if (isset($message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="ws_user" class="form-label">Benutzername oder E-Mail</label>
                    <input type="text" id="ws_user" name="ws_user" class="form-control" required placeholder="E-Mail-Adresse" autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Passwort</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Passwort">
                </div>

                <button type="submit" class="btn btn-primary w-100">Einloggen</button>
            </form>

            <p class="mt-3 text-center">Noch keinen Account? <a href="register.php">Jetzt registrieren</a></p>
        </div>
    </div>
</div>
</body>
</html>
