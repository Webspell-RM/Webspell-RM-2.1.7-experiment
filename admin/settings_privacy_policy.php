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

// Sprachmodul laden
$_language->readModule('privacy_policy', false, true);

use webspell\AccessControl;
use webspell\Captcha;

// Zugriffskontrolle für Admin
AccessControl::checkAdminAccess('ac_privacy_policy');

// Initialisierung der Captcha-Klasse
$CAPCLASS = new \webspell\Captcha;
$tpl = new Template();

// Verarbeitung und Speichern des Datenschutztexts
// Wenn das Formular gesendet wurde
if (isset($_POST['submit'])) {
    // Datenschutztext und Editor aus dem Formular
    $privacy_policy_text = $_POST['message'];
    $current_datetime = date("Y-m-d H:i:s");

    // Überprüfen, ob der Editor aktiviert wurde (Checkbox)
    $editor = isset($_POST['editor']) ? '1' : '0';

    // Überprüfen des Captchas
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        // Wenn bereits ein Eintrag in der Datenbank existiert, dann aktualisieren
        if (mysqli_num_rows(safe_query("SELECT * FROM settings_privacy_policy"))) {
            safe_query("UPDATE settings_privacy_policy SET date='" . $current_datetime . "', privacy_policy_text='" . $privacy_policy_text . "', editor='" . $editor . "'");
        } else {
            // Wenn noch kein Eintrag existiert, dann einen neuen hinzufügen
            safe_query("INSERT INTO settings_privacy_policy (date, privacy_policy_text, editor) VALUES (NOW(), '" . $privacy_policy_text . "', '" . $editor . "')");
        }
        echo '<div class="alert alert-success" role="alert">' . $_language->module['changes_successful'] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_privacy_policy";
                }, 3000); // 3 Sekunden warten
            </script>';
    } else {
        echo '<div class="alert alert-success" role="alert">' . $_language->module['transaction_invalid'] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_privacy_policy";
                }, 3000); // 3 Sekunden warten
            </script>';
    }
}

// Daten aus der Datenbank abrufen
$ergebnis = safe_query("SELECT * FROM settings_privacy_policy");
$ds = mysqli_fetch_array($ergebnis);

// Captcha vorbereiten
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

// Den Wert des Editors aus der Datenbank überprüfen und die Checkbox entsprechend setzen
$editor_checked = '';
if (isset($ds['editor']) && $ds['editor'] == 1) {
    $editor_checked = 'checked'; // Checkbox aktivieren, wenn Editor auf 1 gesetzt ist
}

// Template-Daten vorbereiten
$data_array = [
    'privacy_policy_label' => $_language->module['privacy_policy'] ?? 'Datenschutzerklärung',
    'privacy_policy_text' => htmlspecialchars($ds['privacy_policy_text'], ENT_QUOTES, 'UTF-8'),
    'editor_is_editor' => $_language->module['editor_is_editor'], // Label für "Editor anzeigen"
    'editor_checked' => $editor_checked, // Checkbox für Editor-Status
    'captcha_hash' => $hash,
    'update_button_label' => $_language->module['update'] ?? 'Aktualisieren'
];

echo $tpl->loadTemplate("privacy_policy", "content", $data_array, 'admin');
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-editor');
    const textarea = document.getElementById('ckeditor');

    // Funktion zum Editor aktivieren/deaktivieren
    function toggleEditor() {
        if (toggle.checked) {
            if (!CKEDITOR.instances['ckeditor']) {
                CKEDITOR.replace('ckeditor');
            }
        } else {
            if (CKEDITOR.instances['ckeditor']) {
                CKEDITOR.instances['ckeditor'].destroy(true);
            }
        }
    }

    // Initialer Zustand (z. B. bei Seiten-Reload)
    toggleEditor();

    // Reaktion auf Umschalten
    toggle.addEventListener('change', toggleEditor);
});
</script>
