<?php

use webspell\LoginSecurity;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#require_once('../system/classes/login_security.php');

#$_language->readModule('login');

global $_database;

#session_start();
include('../system/config.inc.php');  // config.inc.php einbinden (anstelle von sql.php)
include('../system/settings.php');
include('../system/functions.php');
include('../system/plugin.php');
include('../system/widget.php');
include('../system/version.php');
include('../system/multi_language.php');



$ip = $_SERVER['REMOTE_ADDR'];
$message = '';
$isIpBanned = false;
$email = '';

// Login-Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password_hash = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "❌ Ungültige E-Mail-Adresse.";
        header("Location: login.php");
        exit;
    }

    $loginResult = LoginSecurity::verifyLogin($email, $password_hash, $ip, $is_active, $is_locked);

    if ($loginResult['success']) {
        if (LoginSecurity::isIpBanned($ip)) {
            $message = 'Diese IP-Adresse wurde gesperrt. Bitte kontaktiere den Support.';
            $isIpBanned = true;
        }

        $stmt = $_database->prepare("SELECT userID, username, email, is_locked FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            if ((int)$user['is_locked'] === 1) {
                $message = 'Dein Konto wurde gesperrt. Bitte kontaktiere den Support.';
                $isIpBanned = true;
            } else {
                $_SESSION['userID'] = (int)$user['userID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                LoginSecurity::saveSession($user['userID']);

                $_SESSION['success_message'] = "Login erfolgreich!";
                header("Location: admincenter.php");
                exit;
            }
        } else {
            $message = 'Benutzer nicht gefunden oder falsche E-Mail-Adresse.';
        }
    } else {
        $stmt = $_database->prepare("SELECT userID, is_active FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $userID = (int)$row['userID'];

            if ((int)$row['is_active'] === 0) {
                $message = 'Dein Konto wurde noch nicht aktiviert. Bitte überprüfe deine E-Mail.';
                $isIpBanned = true;
            } else {
                if (!LoginSecurity::isEmailOrIpBanned($email, $ip)) {
                    LoginSecurity::trackFailedLogin($userID, $email, $ip);
                    $failCount = LoginSecurity::getFailCount($ip, $email);

                    if ($failCount >= 5) {
                        LoginSecurity::banIp($ip, $userID, "Zu viele Fehlversuche", $email);
                        $_SESSION['error_message'] = "Zu viele Fehlversuche – Deine IP wurde gesperrt.";
                    } else {
                        $_SESSION['error_message'] = "Falsche E-Mail oder Passwort. Versuche: $failCount / 5";
                    }
                } else {
                    $message = 'Diese E-Mail-Adresse oder IP wurde gesperrt. Bitte kontaktiere den Support.';
                    $isIpBanned = true;
                }
            }
        } else {
            $message = 'Benutzer nicht gefunden oder falsche E-Mail.';
            $isIpBanned = true;
        }
    }

    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }
}

// Letzte Prüfung auf gebannte E-Mail
if (!empty($email) && LoginSecurity::isEmailBanned($ip, $email)) {
    $message = 'Diese E-Mail-Adresse wurde gesperrt. Bitte kontaktiere den Support.';
    $isIpBanned = true;
}


?>

<!DOCTYPE html>
<html lang="<?= $_language->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="webSPELL-RM CMS Admin Login">
    <title>webSpell | RM - Admin Login</title>

    <link href="/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../components/css/styles.css.php" />
    <link rel="stylesheet" href="../components/cookies/css/cookieconsent.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="../components/cookies/css/iframemanager.css" media="print" onload="this.media='all'">
</head>
<body>

<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image">
        <div class="logo">
            <img class="mw-100 mh-100" src="/admin/images/logo.png" alt="Logo">
            <p class="text1">webspell <span>rm</span></p>
        </div>
    </div>
    <div class="col-md-8 col-lg-6 no-bg">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
                <h2 class="login-heading mb-4"><span><?= $_language->module['signup'] ?></span></h2>
                <div>
                    <h5><?= $_language->module['dashboard'] ?></h5><br />
                    <div class="alert alert-info">
                        <?= $_language->module['welcome2'] ?> Login<br><br>
                        <?= $_language->module['insertmail'] ?>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email"><?= $_language->module['email_address'] ?></label>
                        <input class="form-control" name="email" type="email" placeholder="Email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Passwort</label>
                        <input class="form-control" name="password" type="password" placeholder="Passwort" required>
                    </div>

                    <input type="submit" name="submit" value="<?= $_language->module['signup'] ?>" class="btn btn-primary btn-block">
                </form>

                <?php if (!empty($message)) : ?>
                    <div class="alert alert-danger mt-3"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Cookie-Skripte -->
<script defer src="../components/cookies/js/iframemanager.js"></script>
<script defer src="../components/cookies/js/cookieconsent.js"></script>
<script defer src="../components/cookies/js/cookieconsent-init.js"></script>
<script defer src="../components/cookies/js/app.js"></script>

</body>
</html>