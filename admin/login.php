<?php
// Überprüfen, ob eine Session bereits gestartet wurde
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Nur starten, wenn noch keine Session läuft
}

// In Root-Verzeichnis wechseln
chdir('../');

// Systemdateien einbinden
include('system/sql.php');
include('system/settings.php');
include('system/functions.php');
include('system/plugin.php');
include('system/widget.php');
include('system/version.php');
include('system/multi_language.php');

// Zurück ins Admin-Verzeichnis
chdir('admin');

// Plugin-Manager und Sprache laden
$load = new plugin_manager();
$_language->readModule('admincenter', false, true);

// Session-Benutzer-ID prüfen
$userID = $_SESSION['userID'] ?? 0;
#$cookievalue = 'false';
#if (isset($_COOKIE['ws_cookie'])) {
#    $cookievalue = 'accepted';
#}

$_language->readModule('cookie', false, true);

// >>>>>>> Jetzt beginnt das HTML <<<<<<<<
echo'

<!DOCTYPE html>
<html lang="'.$_language->language.'">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Website using webSPELL-RM CMS">
    <meta name="copyright" content="Copyright &copy; 2017-2023 by webspell-rm.de">
    <meta name="author" content="webspell-rm.de">
    <link rel="SHORTCUT ICON" href="./favicon.ico">

    <!-- CSS STUFF -->
    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">    
    <link href="./login/style.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../components/css/styles.css.php" />
    <link rel="stylesheet" href="../components/cookies/css/cookieconsent.css" media="print" onload="this.media=\'all\'">
    <link rel="stylesheet" href="../components/cookies/css/iframemanager.css" media="print" onload="this.media=\'all\'">

    <title>webSpell | RM - Bootstrap Admin Theme</title>

    <style>
        /* CSS für das Cookie-Banner */
        .cookie-banner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); /* Dunkler Hintergrund */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Sehr hoch, um sicherzustellen, dass es über allem liegt */
            text-align: center;
        }

        .cookie-banner .cookie-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            color: #000;
            width: 80%;
            max-width: 500px;
        }

        .cookie-banner button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cookie-banner button:hover {
            background-color: #0056b3;
        }
    </style>

  </head>

<body>

<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image">
        <div class="logo">
            <img class="mw-100 mh-100" src="./login/images/logo.png" width="auto" height="auto">
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

                    '.$_language->module[ 'insertmail' ].'.
                        
                    </div>
                </div>
              <form method="post" name="login" action="login/admincheck.php">
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

?>
