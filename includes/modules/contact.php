<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*
// Sprachdatei laden
$_language->readModule('contact');

// Variablen initialisieren
$name = '';
$from = '';
$subject = '';
$text = '';
$showerror = '';
$getemail = '';
$loggedin = '';
$recaptcha = (int)htmlspecialchars('recaptcha'); // falls verwendet
$webkey = htmlspecialchars('recaptcha_sitekey'); // falls verwendet

$loggedin = (isset($_SESSION['userID']) && $_SESSION['userID'] > 0);

// Empfänger-Auswahl generieren (kann angepasst werden)
$emails = getContactRecipients(); // eigene Funktion oder statisch definiert
foreach ($emails as $email => $label) {
    $getemail .= '<option value="' . htmlspecialchars($email) . '">' . htmlspecialchars($label) . '</option>';
}

// Wenn Formular abgeschickt wurde
if (isset($_POST['action']) && $_POST['action'] == 'send') {

    $name = trim($_POST['name']);
    $from = trim($_POST['from']);
    $subject = trim($_POST['subject']);
    $text = trim($_POST['text']);
    $to = trim($_POST['getemail']);

    // Validierung
    if (empty($name) || empty($from) || empty($subject) || empty($text) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
        $showerror = getErrorBox($_language->module['fill_out_all_fields']);
    } else {
        // Optional: ReCaptcha prüfen (nur wenn nicht eingeloggt)
        if (!$loggedin && $recaptcha == 0) {
            if (!validate_recaptcha()) {
                $showerror = getErrorBox($_language->module['wrong_security_code']);
            }
        }

        if (empty($showerror)) {
            // Mail senden
            $mail_body = $_language->module['contact_mail_from'] . ": $name <$from>\n\n";
            $mail_body .= $_language->module['subject'] . ": $subject\n\n";
            $mail_body .= $_language->module['message'] . ":\n$text\n";

            mail($to, $subject, $mail_body, "From: $from");

            $showerror = getSuccessBox($_language->module['contact_success']);
            // Felder zurücksetzen
            $name = $from = $subject = $text = '';
        }
    }
}



$data_array = [
    'description' => $_language->module['description'],
    'showerror' => $showerror,
    'getemail' => $getemail,
    'name' => htmlspecialchars($name),
    'from' => htmlspecialchars($from),
    'subject' => htmlspecialchars($subject),
    'text' => htmlspecialchars($text),
    'security_code' => $_language->module['security_code'],
    'user' => $_language->module['user'],
    'mail' => $_language->module['mail'],
    'e_mail_info' => $_language->module['e_mail_info'],
    'subject' => $_language->module['subject'],
    'message' => $_language->module['message'],
    'lang_GDPRinfo' => $_language->module['GDPRinfo'],
    'send' => $_language->module['send'],
    'info_captcha' => (!$loggedin && $recaptcha == 0)
        ? '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="' . htmlspecialchars($webkey) . '"></div>'
        : '',
    'loggedin' => $loggedin,
    'userID' => $_SESSION['userID'] ?? 0, // User-ID direkt aus der Session übergeben
];

// Template laden und anzeigen
echo $tpl->loadTemplate("contact", "form", $data_array, 'theme');




*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM settings_recaptcha"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    $recaptcha = ($get['activated'] == "1") ? 1 : 0;
} catch (Exception $e) {
    $recaptcha = 0;
}

$loggedin = (isset($_SESSION['userID']) && $_SESSION['userID'] > 0);
$_language->readModule('contact');

$data_array = [
    'title' => $_language->module['title'],
    'subtitle' => 'Contact Us',
];
echo $tpl->loadTemplate("contact", "head", $data_array, 'theme');

// Default-Initialisierung, falls Formular neu geladen
$name = '';
$from = '';
$subject = '';
$text = '';
$showerror = '';

$action = $_POST["action"] ?? '';

if ($action == "send") {
    $getemail = $_POST['getemail'];
    $subject = $_POST['subject'];
    $text = str_replace('\r\n', "\n", $_POST['text']);
    $name = $_POST['name'];
    $from = $_POST['from'];
    $run = 0;

    $fehler = array();
    if (!mb_strlen(trim($name))) $fehler[] = $_language->module['enter_name'];
    if (!validate_email($from)) $fehler[] = $_language->module['enter_mail'];
    if (!mb_strlen(trim($subject))) $fehler[] = $_language->module['enter_subject'];
    if (!mb_strlen(trim($text))) $fehler[] = $_language->module['enter_message'];

    $ergebnis = safe_query("SELECT * FROM contact WHERE email='" . $getemail . "'");
    if (mysqli_num_rows($ergebnis) == 0) {
        $fehler[] = $_language->module['unknown_receiver'];
    }

    if ($loggedin) {
        $run = 1;
    } else {
        $runregister = "false";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $recaptcha_response = $_POST['g-recaptcha-response'];
            if (!empty($recaptcha_response)) {
                include("system/curl_recaptcha.php");
                $google_url = "https://www.google.com/recaptcha/api/siteverify";
                $secret = $seckey;
                $ip = $_SERVER['REMOTE_ADDR'];
                $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha_response . "&remoteip=" . $ip;
                $res = getCurlData($url);
                $res = json_decode($res, true);
                if ($res['success']) {
                    $runregister = "true";
                    $run = 1;
                } else {
                    $fehler[] = "reCAPTCHA Error";
                }
            } else {
                $fehler[] = "reCAPTCHA Error";
            }
        }
    }

    if (!count($fehler) && $run) {
        $message = stripslashes(
            'Diese E-Mail wurde über das Kontaktformular auf deiner Webspell-RM Website gesendet (IP-Adresse: ' . $GLOBALS['ip'] . ').<br><br>' .
            'Die Nachricht von ' . htmlspecialchars($name) . ' lautet:<br><br>' .
            '<strong>Nachricht:</strong><br>' . nl2br(htmlspecialchars($text))
        );

        $sendmail = \webspell\Email::sendEmail($from, 'Contact', $getemail, stripslashes($subject), $message);

        if ($sendmail['result'] == 'fail') {
            $fehler[] = $sendmail['error'];
            if (isset($sendmail['debug'])) $fehler[] = $sendmail['debug'];
            $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
        } else {
            if (isset($sendmail['debug'])) {
                $fehler[] = $sendmail['debug'];
                redirect('index.php?site=contact', generateBoxFromArray($_language->module['send_successfull'], 'alert-success', $fehler), 3);
            } else {
                redirect('index.php?site=contact', $_language->module['send_successfull'], 3);
            }
            unset($_POST['name'], $_POST['from'], $_POST['text'], $_POST['subject']);
        }
    } else {
        $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
    }
}

$getemail = '';
$ergebnis = safe_query("SELECT * FROM contact ORDER BY `sort`");
if (mysqli_num_rows($ergebnis) < 1) {
    $data_array = array();
    $data_array['$showerror'] = generateErrorBoxFromArray($_language->module['errors_there'], [$_language->module['no_contact_setup']]);
    echo $tpl->loadTemplate("contact", "failure", $data_array);
    return false;
} else {
    while ($ds = mysqli_fetch_array($ergebnis)) {
        $getemail .= '<option value="' . $ds['email'] . '"' . ($getemail === $ds['email'] ? ' selected="selected"' : '') . '>' . $ds['name'] . '</option>';
    }
}

if ($loggedin) {
    if (!isset($showerror)) $showerror = '';
    $name = htmlspecialchars(stripslashes(getusername($_SESSION['userID'])));
    $from = htmlspecialchars(getemail($_SESSION['userID']));
    $subject = isset($_POST['subject']) ? getforminput($_POST['subject']) : '';
    $text = isset($_POST['text']) ? getforminput($_POST['text']) : '';
}

// Template vorbereiten
$data_array = [
    'description' => $_language->module['description'],
    'showerror' => $showerror ?? '',
    'getemail' => $getemail,
    'name' => htmlspecialchars($name ?? ''),
    'from' => htmlspecialchars($from ?? ''),
    'subject' => htmlspecialchars($subject ?? ''),
    'text' => htmlspecialchars($text ?? ''),
    'security_code' => $_language->module['security_code'],
    'user' => $_language->module['user'],
    'mail' => $_language->module['mail'],
    'e_mail_info' => $_language->module['e_mail_info'],
    'subject' => $_language->module['subject'],
    'message' => $_language->module['message'],
    'lang_GDPRinfo' => $_language->module['GDPRinfo'],
    'send' => $_language->module['send'],
    'info_captcha' => !$loggedin
        ? '<div class="g-recaptcha" data-sitekey="' . htmlspecialchars($webkey) . '"></div>'
        : '',
    'loggedin' => $loggedin,
    'userID' => $_SESSION['userID'] ?? 0
];

echo $tpl->loadTemplate("contact", "form", $data_array, 'theme');
