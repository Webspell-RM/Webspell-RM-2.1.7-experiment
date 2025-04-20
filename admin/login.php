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

$message = null;

// Wenn ein POST-Login-Versuch gemacht wird
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ws_user'], $_POST['password'])) {
    $ws_user = trim($_POST['ws_user']);
    $password = $_POST['password'];

    // loginCheck-Funktion aufrufen, die den Benutzer validiert
    $result = loginCheck($ws_user, $password);

    if ($result->state == "success") {
        // Weiterleitung zur entsprechenden Seite basierend auf der loginCheck()-Antwort
        header("Location: " . $result->redirect);
        exit;
    } else {
        // Fehlermeldung anzeigen, wenn Login fehlgeschlagen ist
        $message = $result->message;
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
                  <input class="form-control" name="ws_user" type="text" placeholder="Email Address" id="login" required>
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
