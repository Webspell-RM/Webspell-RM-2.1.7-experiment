<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use webspell\LoginSecurity;
use webspell\Email;
use webspell\LanguageService;

global $languageService;

$lang = $languageService->detectLanguage();
$languageService->readModule('lostpassword');

// Einstellungen laden
$settings_result = safe_query("SELECT * FROM `settings`");
$settings = mysqli_fetch_assoc($settings_result);

$hp_title = $settings['title'] ?? 'Webspell-RM';
$hp_url = $settings['hpurl'] ?? 'https://' . $_SERVER['HTTP_HOST'];
$admin_email = $settings['adminemail'] ?? 'info@' . $_SERVER['HTTP_HOST'];

$success = isset($_GET['success']) && $_GET['success'] == 1;

if ($success && isset($_SESSION['success_message'])) {
    $data_array = [
        'title' => $languageService->get('title'),
        'forgotten_your_password' => $languageService->get('forgotten_your_password'),
        'message' => '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>',
        'return_to_login' => '<a href="index.php?site=login" class="btn btn-success">' . $languageService->get('login') . '</a>'
    ];
    unset($_SESSION['success_message']);
    echo $tpl->loadTemplate("lostpassword", "success", $data_array, 'theme');
    return;
}

if (isset($_POST['submit'])) {
    $email = LoginSecurity::escape(trim($_POST['email']));

    if ($email !== '') {
        $result = safe_query("SELECT * FROM `users` WHERE `email` = '" . $email . "'");

        if (mysqli_num_rows($result)) {
            $ds = mysqli_fetch_array($result);

            if (!empty($ds['password_pepper'])) {
                $new_password_plain = LoginSecurity::generateReadablePassword();
                $pepper_plain = LoginSecurity::decryptPepper($ds['password_pepper']);

                if ($pepper_plain === false || $pepper_plain === '') {
                    $_SESSION['error_message'] = $languageService->get('error_decrypt_pepper');
                    header("Location: index.php?site=lostpassword");
                    exit;
                }

                $new_password_hash = password_hash($new_password_plain . $ds['email'] . $pepper_plain, PASSWORD_BCRYPT);

                safe_query("
                    UPDATE `users`
                    SET `password_hash` = '" . LoginSecurity::escape($new_password_hash) . "'
                    WHERE `userID` = '" . intval($ds['userID']) . "'
                ");

                $vars = ['%pagetitle%', '%email%', '%new_password%', '%homepage_url%'];
                $repl = [$hp_title, $ds['email'], $new_password_plain, $hp_url];

                $subject = str_replace($vars, $repl, $languageService->get('email_subject'));
                $message = str_replace($vars, $repl, $languageService->get('email_text'));

                $sendmail = Email::sendEmail($admin_email, 'Passwort zurückgesetzt', $ds['email'], $subject, $message);

                if ($sendmail['result'] === 'fail') {
                    $_SESSION['error_message'] = $languageService->get('email_failed') . ' ' . $sendmail['error'];
                    header("Location: index.php?site=lostpassword");
                    exit;
                } else {
                    $_SESSION['success_message'] = str_replace($vars, $repl, $languageService->get('successful'));
                    header("Location: index.php?site=lostpassword&success=1");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = $languageService->get('error_no_pepper');
                header("Location: index.php?site=lostpassword");
                exit;
            }
        } else {
            $_SESSION['error_message'] = $languageService->get('no_user_found');
            header("Location: index.php?site=lostpassword");
            exit;
        }
    } else {
        $_SESSION['error_message'] = $languageService->get('no_mail_given');
        header("Location: index.php?site=lostpassword");
        exit;
    }
}

$message = '';
if (isset($_SESSION['error_message'])) {
    $message = '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}

$data_array = [
    'title' => $languageService->get('title'),
    'forgotten_your_password' => $languageService->get('forgotten_your_password'),
    'info1' => $languageService->get('info1'),
    'info2' => $languageService->get('info2'),
    'info3' => $languageService->get('info3'),
    'your_email' => $languageService->get('your_email'),
    'get_password' => $languageService->get('get_password'),
    'return_to' => $languageService->get('return_to'),
    'login' => $languageService->get('login'),
    'email-address' => $languageService->get('email-address'),
    'reg' => $languageService->get('reg'),
    'need_account' => $languageService->get('need_account'),
    'error_message' => $message,
    'lastpassword_txt' => $languageService->get('lastpassword_txt'),
    'register_link' => $languageService->get('register_link'),
    'welcome_back' => $languageService->get('welcome_back'),
    'reg_text' => $languageService->get('reg_text'),
    'login_text' => $languageService->get('login_text'),
];

echo $tpl->loadTemplate("lostpassword", "content_area", $data_array, 'theme');
