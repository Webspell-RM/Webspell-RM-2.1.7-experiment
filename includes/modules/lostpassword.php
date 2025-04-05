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

// Sprachdatei für das Modul "Passwort vergessen" laden
$_language->readModule('lostpassword');

// Überprüfen, ob das Formular gesendet wurde
if (isset($_POST['submit'])) {
    // E-Mail-Adresse aus dem Formular holen und Leerzeichen entfernen
    $email = trim($_POST['email']);
    
    if ($email != '') {
        // Überprüfung, ob die E-Mail-Adresse in der Datenbank existiert
        $ergebnis = safe_query(
            "SELECT * FROM " . PREFIX . "user WHERE email = '" . $email . "'"
        );
        $anz = mysqli_num_rows($ergebnis);

        if ($anz) {
            // Benutzerdaten aus der Datenbank abrufen
            $ds = mysqli_fetch_array($ergebnis);

            // Neues zufälliges Passwort generieren
            $newpass_random = Gen_PasswordPepper();
            // Neues Passwort mit Benutzer-ID hashen
            $newpass_hash = Gen_PasswordHash($newpass_random, $ds['userID']);

            // Passwort in der Datenbank aktualisieren (altes Passwort wird überschrieben)
            safe_query(
                "UPDATE " . PREFIX . "user 
                SET password='', password_hash='" . $newpass_hash . "' 
                WHERE userID='" . intval($ds['userID']) . "'"
            );

            // E-Mail-Versand vorbereiten
            $ToEmail = $ds['email'];
            $vars = array('%pagetitle%', '%email%', '%new_password%', '%homepage_url%');
            $repl = array($hp_title, $ds['email'], $newpass_random, $hp_url);
            $header = str_replace($vars, $repl, $_language->module['email_subject']);
            $Message = str_replace($vars, $repl, $_language->module['email_text']);

            // E-Mail senden
            $sendmail = \webspell\Email::sendEmail($admin_email, 'Lost Password', $ToEmail, $header, $Message);

            // Überprüfen, ob die E-Mail erfolgreich gesendet wurde
            if ($sendmail['result'] == 'fail') {
                // Falls die E-Mail nicht gesendet werden konnte, Fehlermeldung ausgeben
                echo generateErrorBoxFromArray($_language->module['email_failed'], array($sendmail['error']));
            } else {
                // Falls die E-Mail erfolgreich versendet wurde, Erfolgsmeldung ausgeben
                echo str_replace($vars, $repl, $_language->module['successful']);
            }
        } else {
            // Falls die E-Mail-Adresse nicht gefunden wurde, eine Weiterleitung mit Fehlermeldung durchführen
            redirect('index.php?site=lostpassword', $_language->module['no_user_found'], 3);
        }
    } else {
        // Falls keine E-Mail-Adresse eingegeben wurde, eine Weiterleitung mit Fehlermeldung durchführen
        redirect('index.php?site=lostpassword', $_language->module['no_mail_given'], 3);
    }
} else {
    // Falls das Formular noch nicht gesendet wurde, die Eingabemaske anzeigen
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

    // Formular in das Template laden und ausgeben
    $template = $tpl->loadTemplate("lostpassword", "content_area", $data_array);
    echo $template;
}
?>
