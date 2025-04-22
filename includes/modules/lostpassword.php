<?php

use webspell\SecurityHelper;
use webspell\Email;

global $_language;
$_language->readModule('lostpassword');

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if ($email !== '') {
        $result = safe_query(
            "SELECT * FROM `users` WHERE `email` = '" . escape($email) . "'"
        );

        if (mysqli_num_rows($result)) {
            $ds = mysqli_fetch_array($result);

            if (!empty($ds['password_pepper'])) {
                // 1. Neues Passwort und Hash
                $new_password_plain = SecurityHelper::generateReadablePassword(); // Generiere ein neues Passwort
                $pepper_plain = SecurityHelper::decryptPepper($ds['password_pepper']); // Entschlüssle den Pepper

                if ($pepper_plain === false || $pepper_plain === '') {
                    redirect('index.php?site=lostpassword', '❌ Fehler beim Entschlüsseln des Peppers.', 5);
                    exit;
                }

                // 2. Passwort mit E-Mail und Pepper hashen (bcrypt wird hier automatisch verwendet)
                $new_password_hash = password_hash($new_password_plain . $ds['email'] . $pepper_plain, PASSWORD_BCRYPT);

                // 3. Passwort speichern
                safe_query("
                    UPDATE `users`
                    SET `password_hash` = '" . escape($new_password_hash) . "'
                    WHERE `userID` = '" . intval($ds['userID']) . "'
                ");

                // 4. E-Mail senden
                $vars = ['%pagetitle%', '%email%', '%new_password%', '%homepage_url%'];
                $repl = [$hp_title, $ds['email'], $new_password_plain, $hp_url];

                $subject = str_replace($vars, $repl, $_language->module['email_subject']);
                $message = str_replace($vars, $repl, $_language->module['email_text']);

                $sendmail = Email::sendEmail($admin_email, 'Passwort zurückgesetzt', $ds['email'], $subject, $message);

                if ($sendmail['result'] === 'fail') {
                    echo generateErrorBoxFromArray($_language->module['email_failed'], [$sendmail['error']]);
                } else {
                    echo str_replace($vars, $repl, $_language->module['successful']);
                }
            } else {
                redirect('index.php?site=lostpassword', '❌ Kein Pepper in der Datenbank.', 5);
                exit;
            }
        } else {
            redirect('index.php?site=lostpassword', $_language->module['no_user_found'], 3);
            exit;
        }
    } else {
        redirect('index.php?site=lostpassword', $_language->module['no_mail_given'], 3);
        exit;
    }
}



 else {
    // Formular anzeigen
    $data_array = [
        'title' => $_language->module['title'],
        'forgotten_your_password' => $_language->module['forgotten_your_password'],
        'info1' => $_language->module['info1'],
        'info2' => $_language->module['info2'],
        'info3' => $_language->module['info3'],
        'your_email' => $_language->module['your_email'],
        'get_password' => $_language->module['get_password'],
        'return_to' => $_language->module['return_to'],
        'login' => $_language->module['login'],
        'email-address' => $_language->module['email-address'],
        'reg' => $_language->module['reg'],
        'need_account' => $_language->module['need_account']
    ];

    echo $tpl->loadTemplate("lostpassword", "content_area", $data_array);
}
?>
