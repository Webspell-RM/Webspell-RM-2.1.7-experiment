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

<?php
// Sprachdatei für das Modul "Passwort vergessen" laden
$_language->readModule('lostpassword');

// Überprüfen, ob das Formular gesendet wurde
if (isset($_POST['submit'])) {
    // E-Mail-Adresse aus dem Formular holen und Leerzeichen entfernen
    $email = trim($_POST['email']);
    
    if ($email !== '') {
        // Überprüfen, ob die E-Mail-Adresse in der Datenbank existiert
        $result = safe_query(
            "SELECT * FROM `users` WHERE `email` = '" . escape($email) . "'"
        );
        $count = mysqli_num_rows($result);

        if ($count > 0) {
            // Benutzerdaten abrufen
            $ds = mysqli_fetch_array($result);

            // Neues zufälliges Passwort generieren
            $new_password_plain = Gen_PasswordPepper();

            // Neues Passwort hashen
            $new_password_hash = Gen_PasswordHash($new_password_plain, $ds['userID']);

            // Passwort aktualisieren
            safe_query(
                "UPDATE `users`
                 SET `password` = '', `password_hash` = '" . escape($new_password_hash) . "'
                 WHERE `userID` = '" . intval($ds['userID']) . "'"
            );

            // E-Mail-Inhalt vorbereiten
            $to_email = $ds['email'];
            $vars = ['%pagetitle%', '%email%', '%new_password%', '%homepage_url%'];
            $repl = [$hp_title, $ds['email'], $new_password_plain, $hp_url];
            $subject = str_replace($vars, $repl, $_language->module['email_subject']);
            $message = str_replace($vars, $repl, $_language->module['email_text']);

            // E-Mail senden
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Lost Password', $to_email, $subject, $message);

            // Erfolg oder Fehler anzeigen
            if ($sendmail['result'] === 'fail') {
                echo generateErrorBoxFromArray($_language->module['email_failed'], [$sendmail['error']]);
            } else {
                echo str_replace($vars, $repl, $_language->module['successful']);
            }
        } else {
            // Kein Benutzer mit dieser E-Mail gefunden
            redirect('index.php?site=lostpassword', $_language->module['no_user_found'], 3);
        }
    } else {
        // Keine E-Mail eingegeben
        redirect('index.php?site=lostpassword', $_language->module['no_mail_given'], 3);
    }
} else {
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

    $template = $tpl->loadTemplate("lostpassword", "content_area", $data_array);
    echo $template;
}
