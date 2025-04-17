<?php


// Prüfen, ob es sich um eine AJAX-Anfrage handelt
$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

// Rückgabeobjekt vorbereiten
$return = new stdClass();
$return->state = "failed";
$return->message = "";
$reenter = false;

// Wenn Login-Daten übermittelt wurden
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ws_user'], $_POST['password'])) {
    $ws_user = $_POST['ws_user'];
    $password = $_POST['password'];

    // Login überprüfen
    $return = loginCheck($ws_user, $password);

    // AJAX-Rückgabe oder Weiterleitung
    if ($ajax === true) {
        // JSON-Ausgabe für AJAX-Anfragen
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($return);
    } else {
        // Weiterleitung zur Index-Seite bei erfolgreichem Login
        if ($return->state == "success") {
            header("Location: index.php");
            exit;
        } else {
            // Fehlermeldung anzeigen und erneut Login-Formular anzeigen
            $message = $return->message;
        }
    }
}

// Wenn nicht via POST angemeldet, Zeige das Login-Formular
?>

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