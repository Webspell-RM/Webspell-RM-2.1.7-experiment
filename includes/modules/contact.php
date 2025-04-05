<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2025 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

try {
    $result = safe_query("SELECT * FROM `" . PREFIX . "settings_recaptcha`");
    if (!$result) {
        // Fehlerbehandlung bei Datenbankabfrage
        die("Datenbankabfrage fehlgeschlagen: " . mysqli_error($_database));
    }
    $get = mysqli_fetch_assoc($result);
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    $recaptcha = ($get['activated'] == "1") ? 1 : 0;
} catch (Exception $e) {
    $recaptcha = 0;
}

// Sprache laden
$_language->readModule('contact');
$_language->readModule('formvalidation', true);

$data_array = [
    'title' => $_language->module['title'],
    'subtitle' => 'Contact Us'
];

$template = $tpl->loadTemplate("contact", "head", $data_array);
echo $template;

$action = isset($_POST["action"]) ? $_POST["action"] : '';

if ($action == "send") {
    $getemail = $_POST['getemail'];
    $subject = $_POST['subject'];
    $text = str_replace('\r\n', "\n", $_POST['text']);
    $name = $_POST['name'];
    $from = $_POST['from'];
    $run = 0;
    
    $fehler = array();
    if (!mb_strlen(trim($name))) {
        $fehler[] = $_language->module['enter_name'];
    }
    
    if (!validate_email($from)) {
        $fehler[] = $_language->module['enter_mail'];
    }
    
    if (!mb_strlen(trim($subject))) {
        $fehler[] = $_language->module['enter_subject'];
    }
    
    if (!mb_strlen(trim($text))) {
        $fehler[] = $_language->module['enter_message'];
    }

    // Sicherstellen, dass das E-Mail-Format validiert ist
    $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "contact WHERE email = ?");
    $stmt->bind_param("s", $getemail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $fehler[] = $_language->module['unknown_receiver'];
    }

    if ($userID) {
        $run = 1;
    } else {
        if ($recaptcha != 1) {
            $CAPCLASS = new \webspell\Captcha;
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $fehler[] = "Securitycode Error";
                $runregister = false;
            } else {
                $run = 1;
                $runregister = true;
            }
        } else {
            $runregister = false;
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $recaptcha = $_POST['g-recaptcha-response'];
                if (!empty($recaptcha)) {
                    include("system/curl_recaptcha.php");
                    $google_url = "https://www.google.com/recaptcha/api/siteverify";
                    $secret = $seckey;
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha . "&remoteip=" . $ip;
                    $res = getCurlData($url);
                    $res = json_decode($res, true);

                    if ($res['success']) {
                        $runregister = true;
                        $run = 1;
                    } else {
                        $fehler[] = "reCAPTCHA Error";
                        $runregister = false;
                    }
                } else {
                    $fehler[] = "reCAPTCHA Error";
                    $runregister = false;
                }
            }
        }
    }

    if (!count($fehler) && $run) {
        $message = stripslashes(
            'This mail was sent over your Webspell-RM - Website (IP ' . $GLOBALS['ip'] . '): ' . $hp_url .
            '<br><br><strong>' . htmlspecialchars(getinput($name)) . ' writes:</strong><br>' . htmlspecialchars($text)
        );
        
        // E-Mail senden
        $sendmail = \webspell\Email::sendEmail($from, 'Contact', $getemail, stripslashes($subject), $message);

        if ($sendmail['result'] == 'fail') {
            $fehler[] = isset($sendmail['debug']) ? $sendmail['error'] . ' ' . $sendmail['debug'] : $sendmail['error'];
            $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
        } else {
            redirect('index.php?site=contact', $_language->module['send_successfull'], 3);
            unset($_POST['name'], $_POST['from'], $_POST['text'], $_POST['subject']);
        }
    } else {
        $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
    }
}

$getemail = '';
$ergebnis = safe_query("SELECT * FROM `" . PREFIX . "contact` ORDER BY `sort`");
if (mysqli_num_rows($ergebnis) < 1) {
    $data_array = array();
    $data_array['$showerror'] = generateErrorBoxFromArray($_language->module['errors_there'], array($_language->module['no_contact_setup']));
    $template = $tpl->loadTemplate("contact", "failure", $data_array);
    echo $template;
    return false;
} else {
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $selected = ($getemail === $ds['email']) ? 'selected="selected"' : '';
        $getemail .= '<option value="' . htmlspecialchars($ds['email']) . '" ' . $selected . '>' . htmlspecialchars($ds['name']) . '</option>';
    }
}

##################################################
function getCommonTemplateData($showerror, $getemail, $name, $from, $subject, $text, $captcha = '', $info_captcha = '', $hash = '') {
    global $_language, $webkey, $recaptcha;
    
    $data_array = [
        'showerror' => $showerror,
        'getemail' => $getemail,
        'name' => htmlspecialchars($name),
        'from' => htmlspecialchars($from),
        'subject' => htmlspecialchars($subject),
        'text' => htmlspecialchars($text),
        'info_captcha' => $info_captcha,  // Sicherstellen, dass der Wert übergeben wird
        'title_contact' => $_language->module['title_contact'],
        'description' => $_language->module['description'],
        'receiver' => $_language->module['receiver'],
        'user' => $_language->module['user'],
        'mail' => $_language->module['mail'],
        'e_mail_info' => $_language->module['e_mail_info'],
        'subject' => $_language->module['subject'],
        'message' => $_language->module['message'],
        'security_code' => $_language->module['security_code'],
        'send' => $_language->module['send'],
        'lang_GDPRinfo' => $_language->module['GDPRinfo'],
        'lang_GDPRaccept' => $_language->module['GDPRaccept'],
        'lang_privacy_policy' => $_language->module['privacy_policy'],
    ];

    return $data_array;
}

if ($loggedin) {
    // Benutzer ist eingeloggt
    if (!isset($showerror)) {
        $showerror = ''; // Falls keine Fehler vorhanden sind, leere Fehleranzeige
    }

    // Benutzerinformationen holen
    $name = getinput(stripslashes(getnickname($userID)));
    $from = getinput(getemail($userID));
    $subject = isset($_POST['subject']) ? getforminput($_POST['subject']) : '';
    $text = isset($_POST['text']) ? getforminput($_POST['text']) : '';

    // Gemeinsame Template-Daten für eingeloggt
    $data_array = getCommonTemplateData($showerror, $getemail, $name, $from, $subject, $text);

    // Template laden und ausgeben
    $template = $tpl->loadTemplate("contact", "loggedin", $data_array);
    echo $template;

} else {
    // Benutzer ist nicht eingeloggt

    // Captcha-Instanz erstellen
    $CAPCLASS = new \webspell\Captcha;
    $captcha = $CAPCLASS->createCaptcha();
    $hash = $CAPCLASS->getHash();
    $CAPCLASS->clearOldCaptcha();
    
    if (!isset($showerror)) {
        $showerror = ''; // Falls keine Fehler vorhanden sind, leere Fehleranzeige
    }

    // Formulareingaben (sicherstellen, dass sie gesetzt sind)
    $name = isset($_POST['name']) ? getforminput($_POST['name']) : '';
    $from = isset($_POST['from']) ? getforminput($_POST['from']) : '';
    $subject = isset($_POST['subject']) ? getforminput($_POST['subject']) : '';
    $text = isset($_POST['text']) ? getforminput($_POST['text']) : '';

    // CAPTCHA handling based on recaptcha setting
    if ($recaptcha == "0") { 
        $info_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                     <input type="number" name="captcha" class="form-control" id="input-security-code" required>
                     <input name="captcha_hash" type="hidden" value="'.$hash.'">';
    } else {
        $info_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
    }
    
    // Gemeinsame Template-Daten für nicht eingeloggt
    $data_array = getCommonTemplateData($showerror, $getemail, $name, $from, $subject, $text, $captcha, $info_captcha, $hash);

    // Template laden und ausgeben
    $template = $tpl->loadTemplate("contact", "notloggedin", $data_array);
    echo $template;
}
?>

