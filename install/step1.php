<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Wenn der Benutzer das Formular abgesendet hat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Überprüfen, ob die Checkbox angekreuzt wurde
    if (isset($_POST['accept_license']) && $_POST['accept_license'] == '1') {
        $_SESSION['license_accepted'] = true;
        header("Location: step2.php");  // Weiterleitung zur nächsten Installationsseite
        exit;
    } else {
        $error = "❌ Du musst die Lizenzbedingungen akzeptieren, um fortzufahren.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Schritt 1: Lizenzbedingungen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Schritt 1: Lizenzbedingungen akzeptieren</h2>
    <div class="card mt-4">
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="alert alert-info">
                <h4>GNU General Public License (GPL)</h4>
                <p>Webspell-RM ist freie Software, lizenziert unter der GNU GPL v3.</p>
                <p>Du darfst den Code frei nutzen, verändern und verbreiten, solange du die Lizenzbedingungen einhältst.</p>
                <p><a href="https://www.gnu.org/licenses/gpl-3.0.html" target="_blank">Lizenz anzeigen (externer Link)</a></p>
            </div>

            <form method="post">
                <div class="form-check">
                    <input type="checkbox" name="accept_license" value="1" class="form-check-input" required>
                    <label class="form-check-label">Ich habe die Lizenz gelesen und akzeptiere sie.</label>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Weiter zur Installation</button>
            </form>
        </div>
    </div>    
</div>
</body>
</html>
