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
 * @copyright       2018-2023 by webspell-rm.de                                                              *
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
    $get = mysqli_fetch_assoc(safe_query("SELECT * FROM `settings_recaptcha`"));
    $webkey = $get['webkey'];
    $seckey = $get['seckey'];
    if ($get['activated']=="1") { $recaptcha=1; } else { $recaptcha=0; }
} Catch (EXCEPTION $e) {
    $recaptcha=0;
}



$_language->readModule('contact');
$_language->readModule('formvalidation', true);

    // Array mit Daten für das Template
    $data_array = [
        'title' => $_language->module['title'],
        'subtitle' => 'Contact Us'
    ];

    // Template laden und ausgeben
    $template = $tpl->loadTemplate("contact", "head", $data_array);
    echo $template;

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = '';
}

if ($action == "send") {
    $getemail = $_POST['getemail'];
    $subject = $_POST['subject'];
    $text = $_POST['text'];
    $text = str_replace('\r\n', "\n", $text);
    $name = $_POST['name'];
    $from = $_POST['from'];
    $run = 0;

    $fehler = array();
    if (!(mb_strlen(trim($name)))) {
        $fehler[] = $_language->module['enter_name'];
    }

    if (!validate_email($from)) {
        $fehler[] = $_language->module['enter_mail'];
    }
    if (!(mb_strlen(trim($subject)))) {
        $fehler[] = $_language->module['enter_subject'];
    }
    if (!(mb_strlen(trim($text)))) {
        $fehler[] = $_language->module['enter_message'];
    }

    $ergebnis = safe_query("SELECT * FROM contact WHERE email='" . $getemail . "'");
    if (mysqli_num_rows($ergebnis) == 0) {
        $fehler[] = $_language->module['unknown_receiver'];
    }

    if ($userID) {
        $run = 1;
    } else {

        if($recaptcha!=1) {
            $CAPCLASS = new \webspell\Captcha;
            if (!$CAPCLASS->checkCaptcha($_POST['captcha'], $_POST['captcha_hash'])) {
                $fehler[] = "Securitycode Error";
                $runregister = "false";
            } else {
                $run = 1;
                $runregister = "true";
            }
        } else {
            $runregister = "false";
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $recaptcha=$_POST['g-recaptcha-response'];
                if(!empty($recaptcha)) {
                    include("system/curl_recaptcha.php");
                    $google_url="https://www.google.com/recaptcha/api/siteverify";
                    $secret=$seckey;
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                    $res=getCurlData($url);
                    $res= json_decode($res, true);
                    //reCaptcha success check 
                        if($res['success'])     {
                            $runregister="true"; $run=1;
                        } else {
                            $fehler[] = "reCAPTCHA Error";
                            $runregister="false";
                        }
                } else {
                    $fehler[] = "reCAPTCHA Error";
                    $runregister="false";
                }
            }
        }
    }
    
    if (!count($fehler) && $run) {
        $message = stripslashes(
        'This mail was send over your webSPELL - Website (IP ' . $GLOBALS['ip'] . '): ' . $hp_url .
        '<br><br><strong>' . getinput($name) . ' writes:</strong><br>' . $text
        );
        $sendmail = \webspell\Email::sendEmail($from, 'Contact', $getemail, stripslashes($subject), $message);

        if ($sendmail['result'] == 'fail') {
            if (isset($sendmail['debug'])) {
                $fehler[] = $sendmail['error'];
                $fehler[] = $sendmail['debug'];
                $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
            } else {
                $fehler[] = $sendmail['error'];
                $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
            }
        } else {
            if (isset($sendmail['debug'])) {
                $fehler[] = $sendmail[ 'debug' ];
                redirect(
                    'index.php?site=contact',
                    generateBoxFromArray($_language->module['send_successfull'], 'alert-success', $fehler),
                    3
                );
                unset($_POST['name']);
                unset($_POST['from']);
                unset($_POST['text']);
                unset($_POST['subject']);
            } else {
                redirect('index.php?site=contact', $_language->module['send_successfull'], 3);
                unset($_POST['name']);
                unset($_POST['from']);
                unset($_POST['text']);
                unset($_POST['subject']);
            }
        }
    } else {
        $showerror = generateErrorBoxFromArray($_language->module['errors_there'], $fehler);
    }
}

$getemail = '';
$ergebnis = safe_query("SELECT * FROM `contact` ORDER BY `sort`");
if(mysqli_num_rows($ergebnis)<1) {
	$data_array = array();
    $data_array['$showerror'] = generateErrorBoxFromArray($_language->module['errors_there'], array($_language->module['no_contact_setup']));
    $template = $tpl->loadTemplate("contact","failure", $data_array);
    echo $template;
	return false;
} else {
	while ($ds = mysqli_fetch_array($ergebnis)) {
		if ($getemail === $ds['email']) {
			$getemail .= '<option value="' . $ds['email'] . '" selected="selected">' . $ds['name'] . '</option>';
		} else {
			$getemail .= '<option value="' . $ds['email'] . '">' . $ds['name'] . '</option>';
		}
	}
}
if ($loggedin) {
    if (!isset($showerror)) {
        $showerror = '';
    }
    $name = getinput(stripslashes(getusername($userID)));
    $from = getinput(getemail($userID));
    if (isset($_POST['subject'])) {
        $subject = getforminput($_POST['subject']);
    } else {
        $subject = '';
    }
    if (isset($_POST['text'])) {
        $text = getforminput($_POST['text']);
    } else {
        $text = '';
    }


    $data_array = [
        'showerror' => $showerror,
        'getemail' => $getemail,
        'name' => htmlspecialchars($name),
        'from' => htmlspecialchars($from),
        'subject' => htmlspecialchars($subject),
        'text' => htmlspecialchars($text),
        /*'info_captcha' => $info_captcha,  // Sicherstellen, dass der Wert übergeben wird*/
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


    
    $template = $tpl->loadTemplate("contact","loggedin", $data_array);
    echo $template;


} else {
    $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();
    if (!isset($showerror)) {
        $showerror = '';
    }
    if (isset($_POST['name'])) {
        $name = getforminput($_POST['name']);
    } else {
        $name = '';
    }
    if (isset($_POST['from'])) {
        $from = getforminput($_POST['from']);
    } else {
        $from = '';
    }
    if (isset($_POST['subject'])) {
        $subject = getforminput($_POST['subject']);
    } else {
        $subject = '';
    }
    if (isset($_POST['text'])) {
        $text = getforminput($_POST['text']);
    } else {
        $text = '';
    }

    if($recaptcha=="0") { 
        $CAPCLASS = new \webspell\Captcha;
        $captcha = $CAPCLASS->createCaptcha();
        $hash = $CAPCLASS->getHash();
        $CAPCLASS->clearOldCaptcha();
        $_captcha = '<span class="input-group-addon captcha-img">'.$captcha.'</span>
                    <input type="number" name="captcha" class="form-control" id="input-security-code" required>
                    <input name="captcha_hash" type="hidden" value="'.$hash.'">';
    } else {
        $_captcha = '<div class="g-recaptcha" style="width: 70%; float: left;" data-sitekey="'.$webkey.'"></div>';
    }
    
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
    
    $template = $tpl->loadTemplate("contact","notloggedin", $data_array);
    echo $template;
}

