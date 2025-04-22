<?php
#session_start();
include('../system/config.inc.php');  // config.inc.php einbinden (anstelle von sql.php)
include('../system/settings.php');
include('../system/functions.php');
include('../system/plugin.php');
include('../system/widget.php');
include('../system/version.php');
include('../system/multi_language.php');

// Wenn der Benutzer bereits eingeloggt ist, weiterleiten zum Admincenter
if (isset($_SESSION['userID'])) {
    header("Location: admincenter.php");
    exit;
}

function escape($string) {
    global $_database;
    return $_database->real_escape_string($string);
}




$message = null;

// POST-Login-Versuch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Benutzer anhand der E-Mail holen
    $result = safe_query("SELECT * FROM `users` WHERE `email` = '" . escape($email) . "'");
    
    if (mysqli_num_rows($result)) {
        $user = mysqli_fetch_assoc($result);

        if (!empty($user['password_hash']) && !empty($user['password_pepper'])) {
            $inputPasswordWithPepper = $password . $user['password_pepper'];

            if (password_verify($inputPasswordWithPepper, $user['password_hash'])) {
                // Erfolgreich eingeloggt
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['username'] = $user['username'];

                // Session in DB speichern
                saveSessionToDatabase($user['userID'], $_SESSION);

                $redirect_url = $_SESSION['login_redirect'] ?? '/admin/admincenter.php';
                unset($_SESSION['login_redirect']);
                // Erfolgreiche Anmeldung
                $message = '<div class="alert alert-success" role="alert">✅ Login erfolgreich!</div>';
                
                header("Location: /admin/admincenter.php"); // oder Weiterleitung ins Dashboard
                exit;
            } else {
                // Fehler: Passwort falsch
                $message = '<div class="alert alert-danger" role="alert">⚠️ Falsches Passwort!</div>';
            }
        } else {
            // Fehler: Kein gültiges Passwort gespeichert
            $message = '<div class="alert alert-warning" role="alert">⚠️ Benutzer hat kein gültiges Passwort gespeichert.</div>';
        }
    } else {
        // Fehler: Benutzer nicht gefunden
        $message = '<div class="alert alert-danger" role="alert">⚠️ Benutzer nicht gefunden!</div>';
    }
}

$_language->readModule('admincenter', false, true);

// HTML-Ausgabe
echo '
<!DOCTYPE html>
<html lang="'.$_language->language.'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Website using webSPELL-RM CMS">
    <meta name="copyright" content="Copyright &copy; 2017-2023 by webspell-rm.de">
    <meta name="author" content="webspell-rm.de">
    <link rel="SHORTCUT ICON" href="./favicon.ico">
    
    <!-- CSS einbinden -->
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">    
    <link href="/admin/css/style.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../components/css/styles.css.php" />
    <link rel="stylesheet" href="../components/cookies/css/cookieconsent.css" media="print" onload="this.media=\'all\'">
    <link rel="stylesheet" href="../components/cookies/css/iframemanager.css" media="print" onload="this.media=\'all\'">

    <title>webSpell | RM - Admin Login</title>
</head>
<body>

<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image">
        <div class="logo">
            <img class="mw-100 mh-100" src="/admin/images/logo.png" width="auto" height="auto">
            <p class="text1">webspell <span>rm</span>
        </div>
    </div>
    <div class="col-md-8 col-lg-6 no-bg">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
                <h2 class="login-heading mb-4"><span>'.$_language->module[ 'signup' ].'</span></h2>
                <div>
                    <h5>'.$_language->module[ 'dashboard' ].'</h5><br />
                    <div class="alert alert-info" role="alert">
                    '.$_language->module[ 'welcome2' ].' '.$version.' Login.<br><br>
                    '.$_language->module[ 'insertmail' ].'                        
                    </div>
                </div>
              <form method="POST" action="">
                <div class="form-label-group">
                    <label for="exampleInputEmail1">'.$_language->module[ 'email_address' ].'</label>
                  <input class="form-control" name="email" type="text" placeholder="Email Address" id="login" required>
                </div>
                  
                <div class="form-label-group">
                    <label for="exampleInputPassword1">Password</label>
                  <input class="form-control" name="password" type="password" placeholder="Password" id="password" required>
                </div>

                <input type="submit" name="submit" value="'.$_language->module[ 'signup' ].'" class="fourth btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2">
              </form>
              ';

if ($message) {
    echo '<div class="alert alert-danger mt-3" role="alert">' . htmlspecialchars($message) . '</div>';
}

echo '
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Cookies Abfrage -->
    <script defer src="../components/cookies/js/iframemanager.js"></script>
    <script defer src="../components/cookies/js/cookieconsent.js"></script>
    <script defer src="../components/cookies/js/cookieconsent-init.js"></script>
    <script defer src="../components/cookies/js/app.js"></script>

</body>
</html>
';
