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
// Fehlerprotokollierung aktivieren (in der Produktion sollte 'display_errors' auf 0 gesetzt sein)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'system/error.log'); // Korrigierter Pfad zur Fehlerprotokolldatei

$_language->readModule('login');

// Prüfen, ob Benutzer eingeloggt ist
if ($loggedin) {

    // Wenn userID vorhanden ist, aber keine via GET oder POST übergeben wurde
    if ($userID && !isset($_GET['userID']) && !isset($_POST['userID'])) {
        try {
            ob_start(); // Startet Output Buffering für die Weiterleitung

            if (!empty($_SESSION['HTTP_REFERER'])) {
                $ref = $_SESSION['HTTP_REFERER'];

                // Weiterleitung basierend auf Referrer
                if ($ref === 'index.php?site=login') {
                    header('Location: index.php?site=news');
                } elseif ($ref !== '') {
                    header('Location: index.php');
                } else {
                    header("Location: $ref");
                }

                ob_end_clean();
                exit;
            } else {
                // Keine Referrer-URL vorhanden: zurück zur vorherigen Seite via JavaScript
                echo '<html><head><script type="text/javascript">history.back();</script></head><body></body></html>';
                exit;
            }

        } catch (Exception $e) {
            error_log("Fehler bei der Weiterleitung: " . $e->getMessage());
            echo "Entschuldigung, ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
        }

    } else {
        echo $_language->module['you_have_to_be_logged_in'];
    }

} else {
    global $logo, $theme_name, $themes;

    try {
        $_SESSION['ws_sessiontest'] = true; // Teste Session-Speicherung

        // Sprachdaten für das Loginformular
        $data_array = [
            'modulepath' => rtrim(MODULE, '/'),
            'login_titel' => $_language->module['login_titel'],
            'login' => $_language->module['login'],
            'lang_register' => $_language->module['register'],
            'cookie_title' => $_language->module['cookie_title'],
            'cookie_text' => $_language->module['cookie_text'],
            'register_now' => $_language->module['register_now'],
            'lost_password' => $_language->module['lost_password'],
            'have_an_account' => $_language->module['have_an_account'],
            'info1' => $_language->module['info1'],
            'info2' => $_language->module['info2'],
            'reg' => $_language->module['reg'],
            'forgotten_your_login' => $_language->module['forgotten_your_login'],
            'info_login' => $_language->module['info_login'],
            'enter_your_email' => $_language->module['enter_your_email'],
            'enter_password' => $_language->module['enter_password'],
            'need_account' => $_language->module['need_account']
        ];

        // Prüfen, ob Cookie für Login gesetzt ist
        if (isset($_COOKIE['ws_session'])) {
            $loginform = $tpl->loadTemplate("login", "content", $data_array);
            echo $loginform;
        } else {
            // Cookie fehlt – Hinweis anzeigen
            $cookie_data = [
                'login_titel' => $_language->module['login_titel'],
                'cookie_title' => $_language->module['cookie_title'],
                'cookie_text' => $_language->module['cookie_text'],
                'login' => $_language->module['login'],
                'info3' => $_language->module['info3'],
                'info4' => $_language->module['info4'],
                'log_in' => $_language->module['log_in'],
                'return_to' => $_language->module['return_to']
            ];

            $loginform = $tpl->loadTemplate("login", "cookie_error", $cookie_data);
            echo $loginform;
        }

    } catch (Exception $e) {
        error_log("Fehler beim Laden des Login-Formulars: " . $e->getMessage());
        echo "Ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
    }
}
