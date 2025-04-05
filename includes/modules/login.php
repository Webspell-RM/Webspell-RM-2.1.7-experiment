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

// Fehlerprotokollierung aktivieren (in einer realen Produktionsumgebung sollte 'display_errors' auf 0 gesetzt sein)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'sytem/error.log'); // Pfad zur Fehlerprotokolldatei

$_language->readModule('login');

// Überprüfen, ob der Benutzer eingeloggt ist
if ($loggedin) {
    // Wenn der Benutzer eingeloggt ist und keine userID gesetzt wurde
    if ($userID && !isset($_GET['userID']) && !isset($_POST['userID'])) {
        
        try {
            // Wenn eine Referrer-URL gesetzt ist, leite den Benutzer weiter
            if (isset($_SESSION['HTTP_REFERER']) && !empty($_SESSION['HTTP_REFERER'])) {
                ob_start();  // Puffer starten, um die Weiterleitung korrekt zu handhaben
                if ($_SESSION['HTTP_REFERER'] == 'index.php?site=login') {
                    // Wenn der Benutzer von der Login-Seite kommt, leite zur News-Seite weiter
                    header('Location: index.php?site=news');
                } elseif ($_SESSION['HTTP_REFERER'] != "") {
                    // Ansonsten, leite zur Index-Seite weiter
                    header('Location: index.php');
                } else {
                    // Wenn keine Referenz-URL vorhanden ist, leite zur gespeicherten Referrer-URL weiter
                    header('Location: ' . $_SESSION['HTTP_REFERER']);
                }
                ob_end_clean();  // Puffer beenden
                exit(1);  // Beende das Script nach der Weiterleitung
            } else {
                // Wenn keine Referrer-URL vorhanden ist, sende den Benutzer zurück zur vorherigen Seite
                print '<html><head><script type="text/javascript">history.back();</script></head><body /></html>';
                exit(1);  // Beende das Script
            }

        } catch (Exception $e) {
            // Falls ein Fehler auftritt, logge den Fehler und zeige eine benutzerfreundliche Nachricht an
            error_log("Fehler bei der Weiterleitung: " . $e->getMessage());
            echo "Entschuldigung, ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
        }

    } else {
        // Wenn der Benutzer eingeloggt ist, aber keine gültige userID übergeben wurde
        echo $_language->module['you_have_to_be_logged_in'];  // Benutzer muss eingeloggt sein
    }
} else {
    // Wenn der Benutzer nicht eingeloggt ist
    GLOBAL $logo, $theme_name, $themes;
    
    try {
        // Teste, ob die Session korrekt funktioniert
        $_SESSION['ws_sessiontest'] = true;

        // Erstelle ein Array für die Daten, die an das Template übergeben werden
        $data_array = [
            'modulepath' => substr(MODULE, 0, -1),  // Der Pfad zum Modul
            'login_titel' => $_language->module['login_titel'],  // Titel des Login-Bereichs
            'login' => $_language->module['login'],  // Login-Button
            'lang_register' => $_language->module['register'],  // Register-Link
            'cookie_title' => $_language->module['cookie_title'],  // Cookie-Informationen Titel
            'cookie_text' => $_language->module['cookie_text'],  // Cookie-Informationen Text
            'register_now' => $_language->module['register_now'],  // Aufforderung zum Registrieren
            'lost_password' => $_language->module['lost_password'],  // Link für Passwort vergessen
            'have_an_account' => $_language->module['have_an_account'],  // Hinweis auf bereits vorhandenes Konto
            'info1' => $_language->module['info1'],  // Zusätzliche Informationen
            'info2' => $_language->module['info2'],  // Weitere Informationen
            'reg' => $_language->module['reg'],  // Registrierung
            'forgotten_your_login' => $_language->module['forgotten_your_login'],  // Login vergessen?
            'info_login' => $_language->module['info_login'],  // Login-Info
            'enter_your_email' => $_language->module['enter_your_email'],  // Aufforderung zur Eingabe der E-Mail
            'enter_password' => $_language->module['enter_password'],  // Aufforderung zur Eingabe des Passworts
            'need_account' => $_language->module['need_account']  // Hinweis, dass man ein Konto benötigt
        ];

        // Wenn das Login-Cookie gesetzt ist
        if (isset($_COOKIE['ws_session'])) {
            // Lade das Login-Formular und zeige es an
            $loginform = $tpl->loadTemplate("login", "content", $data_array);
            echo $loginform;
        } else {
            // Wenn das Login-Cookie nicht gesetzt ist, zeige eine Fehlermeldung an
            $data_array = [
                'login_titel' => $_language->module['login_titel'],  // Titel des Login-Bereichs
                'cookie_title' => $_language->module['cookie_title'],  // Cookie-Titel
                'cookie_text' => $_language->module['cookie_text'],  // Cookie-Text
                'login' => $_language->module['login'],  // Login-Button
                'info3' => $_language->module['info3'],  // Weitere Info
                'info4' => $_language->module['info4'],  // Weitere Info
                'log_in' => $_language->module['log_in'],  // Log-in Button
                'return_to' => $_language->module['return_to']  // Zurück-Link
            ];

            // Zeige die Fehlermeldung an
            $loginform = $tpl->loadTemplate("login", "cookie_error", $data_array);
            echo $loginform;
        }
    } catch (Exception $e) {
        // Fehlerprotokollierung
        error_log("Fehler beim Laden des Login-Formulars: " . $e->getMessage());
        // Benutzerfreundliche Fehlermeldung
        echo "Ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
    }
}
?>
