<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */


// by ZENITH-Developments.de

// Passwortregeln
$_admin_minpasslen = "6";
$_admin_maxpasslen = ""; // keine Obergrenze
$_admin_musthavelow = true;
$_admin_musthaveupp = true;
$_admin_musthavenum = true;
$_admin_musthavespec = true;

// Passwort-Komplexität prüfen
function pass_complex($pwd, $_admin_minpasslen, $_admin_maxpasslen, $_admin_musthavelow, $_admin_musthaveupp, $_admin_musthavenum, $_admin_musthavespec) {
    $_pwd_low  = $_admin_musthavelow  ? "(?=\S*[a-z])" : "";
    $_pwd_upp  = $_admin_musthaveupp  ? "(?=\S*[A-Z])" : "";
    $_pwd_num  = $_admin_musthavenum  ? "(?=\S*[\d])"  : "";
    $_pwd_spec = $_admin_musthavespec ? "(?=\S*[\W])"  : "";

    if (!preg_match_all('$\S*(?=\S{' . $_admin_minpasslen . ',' . $_admin_maxpasslen . '})' . $_pwd_low . $_pwd_upp . $_pwd_num . $_pwd_spec . '\S*$', $pwd)) {
        return false;
    }
    return true;
}

// reCAPTCHA-Einstellungen aus DB laden
try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    $recaptcha = $get['activated'] == "1" ? 1 : 0;
} catch (Exception $e) {
    $recaptcha = 0;
}

// Sprachmodul laden
$_language->readModule('register');

// Template-Vorbereitung
$data_array = [
    '$title' => $_language->module['title'],
];
$template = $tpl->loadTemplate("register", "head", $data_array);
echo $template;

#########################################

$show = true;

if (isset($_POST['save'])) {
    if (!$loggedin) {
        $username = htmlspecialchars(mb_substr(trim($_POST['username']), 0, 30));
        if (strpos($username, "'") !== false) $username = "";

        $password   = $_POST['password'];
        $password2  = $_POST['password2'];
        $gender     = $_POST['gender'];
        $birthday   = $_POST['birthday'];
        $homepage   = $_POST['homepage'];
        $firstname  = $_POST['firstname'];
        $lastname   = $_POST['lastname'];
        $town       = $_POST['town'];
        $twitch     = $_POST['twitch'];
        $youtube    = $_POST['youtube'];
        $twitter    = $_POST['twitter'];
        $instagram  = $_POST['instagram'];
        $facebook   = $_POST['facebook'];
        $steam      = $_POST['steam'];
        $topics     = '|';
        $mail       = $_POST['mail'];
        $CAPCLASS   = new \webspell\Captcha;

        $error = [];

        // username prüfen
        if (!(mb_strlen(trim($username)))) {
            $error[] = $_language->module['enter_username'];
        }

        // username bereits vergeben?
        $result = safe_query("SELECT * FROM `users` WHERE `username` = '$username'");
        if (mysqli_num_rows($result)) {
            $error[] = $_language->module['username_inuse'];
        }

        // Passwort überprüfen
        if ($password != $password2) {
            $error[] = $_language->module['repeat_invalid'];
        } elseif (!(strlen(trim($password)))) {
            $error[] = $_language->module['enter_password'];
        }

        // Passwortkomplexität prüfen
        if (!pass_complex($password, $_admin_minpasslen, $_admin_maxpasslen, $_admin_musthavelow, $_admin_musthaveupp, $_admin_musthavenum, $_admin_musthavespec)) {
            $error[] = $_language->module['enter_password2'];
        }

        // Mail prüfen
        if (!validate_email($mail)) {
            $error[] = $_language->module['invalid_mail'];
        }

        // Mail bereits registriert?
        $result = safe_query("SELECT `userID` FROM `users` WHERE `email` = '$mail'");
        if (mysqli_num_rows($result)) {
            $error[] = $_language->module['mail_inuse'];
        }

        // Captcha prüfen
        if ($recaptcha == 0) {
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $error[] = $_language->module['wrong_securitycode'];
            } else {
                $runregister = "false";
            }
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $recaptcha_response = $_POST['g-recaptcha-response'];
                if (!empty($recaptcha_response)) {
                    include("system/curl_recaptcha.php");
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$seckey&response=$recaptcha_response&remoteip=" . $_SERVER['REMOTE_ADDR'];
                    $res = json_decode(getCurlData($url), true);
                    $runregister = $res['success'] ? "true" : "false";
                    if (!$res['success']) {
                        $error[] = "reCAPTCHA Error";
                    }
                } else {
                    $error[] = "reCAPTCHA Error";
                    $runregister = "false";
                }
            }
        }

        // Ein Account pro IP
        if (!$register_per_ip) {
            $check_ip = safe_query("SELECT `userID` FROM `users` WHERE `ip` = '" . $GLOBALS['ip'] . "'");
            if (mysqli_num_rows($check_ip)) {
                $error[] = 'Only one Account per IP';
            }
        }

        if (count($error)) {
            $_language->readModule('formvalidation', true);
            $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $error);
        } else {
            // Registrierung
            $registerdate = time();
            $activationkey = md5(RandPass(20));
            $activationlink = getCurrentUrl() . '&key=' . $activationkey;

            $newusername = htmlspecialchars(mb_substr(trim($_POST['username']), 0, 30));
            safe_query("
                INSERT INTO `users` (
                    `registerdate`, `lastlogin`, `username`, `email`, `firstname`, `lastname`, `gender`,
                    `birthday`, `homepage`, `town`, `twitch`, `youtube`, `twitter`, `instagram`,
                    `facebook`, `steam`, `topics`, `activated`, `ip`, `date_format`, `time_format`
                ) VALUES (
                    '$registerdate', '$registerdate', '$newusername', '$mail', '$firstname', '$lastname', '$gender',
                    '$birthday', '$homepage', '$town', '$twitch', '$youtube', '$twitter', '$instagram',
                    '$facebook', '$steam', '|', '$activationkey', '" . $GLOBALS['ip'] . "', '$default_format_date', '$default_format_time'
                )
            ");

            $insertid = mysqli_insert_id($_database);

            safe_query("INSERT INTO `user_username` (`userID`, `username`) VALUES ('$insertid', '$newusername')");

            $pass = Gen_PasswordHash(stripslashes($password), $insertid);
            safe_query("UPDATE `users` SET `password_hash` = '$pass' WHERE `userID` = '$insertid'");

            safe_query("INSERT INTO `user_groups` (`userID`) VALUES ('$insertid')");

            // Mail versenden
            $ToEmail = $mail;
            $header = str_replace(
                ['%username%', '%activationlink%', '%pagetitle%', '%homepage_url%'],
                [stripslashes($username), stripslashes($activationlink), $hp_title, $hp_url],
                $_language->module['mail_subject']
            );
            $Message = str_replace(
                ['%username%', '%activationlink%', '%pagetitle%', '%homepage_url%'],
                [stripslashes($username), stripslashes($activationlink), $hp_title, $hp_url],
                $_language->module['mail_text']
            );
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Register', $ToEmail, $header, $Message);

            if ($sendmail['result'] == 'fail') {
                $fehler = [$sendmail['error']];
                if (isset($sendmail['debug'])) $fehler[] = $sendmail['debug'];
                redirect("index.php", generateErrorBoxFromArray($_language->module['mail_failed'], $fehler), 10);
                $show = false;
            } else {
                $meldung = isset($sendmail['debug']) ? generateBoxFromArray($_language->module['register_successful'], 'alert-success', [$sendmail['debug']]) : $_language->module['register_successful'];
                redirect("index.php", $meldung, isset($sendmail['debug']) ? 10 : 3);
                $show = false;
            }
        }
    } else {
        redirect("index.php?site=register", str_replace('%pagename%', $GLOBALS['hp_title'], $_language->module['no_register_when_loggedin']), 3);
    }
}






################################
// by ZENITH-Developments.de

// Überprüfung des Aktivierungsschlüssels für den Account
if (isset($_GET['key'])) {
    safe_query("UPDATE `users` SET activated='1' WHERE activated='" . $_GET['key'] . "'");
    
    if (mysqli_affected_rows($_database)) {
        redirect('index.php?site=login', $_language->module['activation_successful'], 3);
    } else {
        redirect('index.php?site=login', $_language->module['wrong_activationkey'], 3);
    }
}
// Überprüfung des Aktivierungsschlüssels für die E-Mail-Adresse
elseif (isset($_GET['mailkey'])) {
    if (mb_strlen(trim($_GET['mailkey'])) == 32) {
        safe_query(
            "UPDATE `users`
            SET email_activate='1', email=email_change, email_change=''
            WHERE email_activate='" . $_GET['mailkey'] . "'"
        );
        
        if (mysqli_affected_rows($_database)) {
            redirect('index.php?site=login', $_language->module['mail_activation_successful'], 3);
        } else {
            redirect('index.php?site=login', $_language->module['wrong_activationkey'], 3);
        }
    }
} else {
    // Registrierung anzeigen, wenn der Benutzer nicht eingeloggt ist
    if ($show === true) {
        if (!$loggedin) {
            if (isset($_COOKIE['ws_session'])) {

                // CAPTCHA erstellen je nach Einstellung
                if ($recaptcha == "0") {
                    $CAPCLASS = new \webspell\Captcha;
                    $captcha = $CAPCLASS->createCaptcha();
                    $hash = $CAPCLASS->getHash();
                    $CAPCLASS->clearOldCaptcha();
                    $_captcha = '
                        <span class="input-group-addon captcha-img">' . $captcha . '</span>
                        <input type="number" name="captcha" class="form-control" id="input-security-code" required>
                        <input name="captcha_hash" type="hidden" value="' . $hash . '">
                    ';
                } else {
                    $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="' . $webkey . '"></div>';
                }

                // Standardwerte für Felder setzen, falls sie nicht gesetzt sind
                $username = isset($_POST['username']) ? getforminput($_POST['username']) : '';
                $password = isset($_POST['password']) ? getforminput($_POST['password']) : '';
                $mail = isset($_POST['mail']) ? getforminput($_POST['mail']) : '';
                $firstname = isset($_POST['firstname']) ? getforminput($_POST['firstname']) : '';
                $lastname = isset($_POST['lastname']) ? getforminput($_POST['lastname']) : '';

                // Geschlecht als Auswahloptionen
                $gender = '
                    <option selected disabled value="select_gender">' . $_language->module['select_gender'] . '</option>
                    <option value="male">' . $_language->module['male'] . '</option>
                    <option value="female">' . $_language->module['female'] . '</option>
                    <option value="diverse">' . $_language->module['diverse'] . '</option>';

                // Alle Felder für das Template vorbereiten
                $data_array = [
                    'showerror' => $showerror ?? '',
                    'username' => $username,
                    'password' => $password,
                    'mail' => $mail,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'captcha' => $_captcha,
                    'gender' => $gender,
                    'registration' => $_language->module['registration'],
                    'info' => $_language->module['info'],
                    'username_label' => $_language->module['username'],
                    'for_login' => $_language->module['for_login'],
                    'password_label' => $_language->module['password'],
                    'mail_label' => $_language->module['mail'],
                    'security_code' => $_language->module['security_code'],
                    'register_now' => $_language->module['register_now'],
                    'profile_info' => $_language->module['profile_info'],
                    'pass_ver' => $_language->module['pass_ver'],
                    'pass_text' => $_language->module['pass_text'],
                    'GDPRinfo' => $_language->module['GDPRinfo'],
                    'GDPRaccept' => $_language->module['GDPRaccept'],
                    'GDPRterm' => $_language->module['GDPRterm'],
                    'privacy_policy' => $_language->module['privacy_policy'],
                    'pw1' => $_language->module['pw1'],
                    'pw2' => $_language->module['pw2'],
                    'pw3' => $_language->module['pw3'],
                    'pw4' => $_language->module['pw4'],
                    'pw5' => $_language->module['pw5'],
                    'pw6' => $_language->module['pw6'],
                    'login' => $_language->module['login'],
                    'email_address' => $_language->module['email_address'],
                    'already_have_an_account' => $_language->module['already_have_an_account'],
                    'enter_your_email' => $_language->module['enter_your_email'],
                    'enter_your_name' => $_language->module['enter_your_name'],
                    'enter_password' => $_language->module['enter_password'],
                    'repeat' => $_language->module['repeat'],
                    'info1' => $_language->module['info1'],
                    'info2' => $_language->module['info2'],
                    'date_of_birth' => $_language->module['date_of_birth'],
                    'gender_label' => $_language->module['gender'],
                    'homepage1' => $_language->module['homepage1'],
                    'homepage2' => $_language->module['homepage2'],
                    'town1' => $_language->module['town1'],
                    'town2' => $_language->module['town2'],
                    'fields_star_required' => $_language->module['fields_star_required'],
                    'enter_firstname' => $_language->module['enter_your_firstname'],
                    'enter_lastname' => $_language->module['enter_your_lastname'],
                    'firstname_label' => $_language->module['firstname'],
                    'lastname_label' => $_language->module['lastname'],
                    'already_account' => $_language->module['already_account'],
                    'social_security_code' => $_language->module['social_&_security_code'],
                    'login_data' => $_language->module['login_data'],
                    'personal_data' => $_language->module['personal_data'],
                    'next' => $_language->module['next'],
                    'previous' => $_language->module['previous']
                ];

                // Template laden und ausgeben
                $template = $tpl->loadTemplate("register", "content", $data_array);
                echo $template;
            } else {
                // Fehler: Cookie nicht akzeptiert
                redirect(
                    "index.php",
                    str_replace('%pagename%', $GLOBALS['hp_title'], $_language->module['no_cookie_accept']),
                    3
                );
            }
        } else {
            // Fehler: Benutzer ist bereits eingeloggt
            redirect(
                "index.php",
                str_replace('%pagename%', $GLOBALS['hp_title'], $_language->module['no_register_when_loggedin']),
                3
            );
        }
    }
}
