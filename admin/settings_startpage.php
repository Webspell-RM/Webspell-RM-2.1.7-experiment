<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*  
 *                                    Webspell-RM      /                        /   /                                                 *
 *                                    -----------__---/__---__------__----__---/---/-----__---- _  _ -                                *
 *                                     | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                                 *
 *                                    _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                                 *
 *                                                 Free Content / Management System                                                   *
 *                                                             /                                                                      *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         Webspell-RM                                                                                                       *
 *                                                                                                                                    *
 * @copyright       2018-2022 by webspell-rm.de <https://www.webspell-rm.de>                                                          *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de <https://www.webspell-rm.de/forum.html>  *
 * @WIKI            webspell-rm.de <https://www.webspell-rm.de/wiki.html>                                                             *
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                                                  *
 *                  It's NOT allowed to remove this copyright-tag <http://www.fsf.org/licensing/licenses/gpl.html>                    *
 *                                                                                                                                    *
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                                                 *
 * @copyright       2005-2018 by webspell.org / webspell.info                                                                         *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                                                                                                                                    *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 */

$_language->readModule('startpage', false, true);

use webspell\AccessControl;

// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_startpage');
$CAPCLASS = new \webspell\Captcha;
$tpl = new Template();

// Wenn das Formular gesendet wurde
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $startpage_text = $_POST['message'];
    $editor = isset($_POST['editor']) ? '1' : '0';

    // Umwandlung der Zeilenumbrüche in <br /> für die Speicherung
    $startpage_text = nl2br($startpage_text);

    
    $current_datetime = date("Y-m-d H:i:s");

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (mysqli_num_rows(safe_query("SELECT * FROM settings_startpage"))) {
            safe_query("UPDATE settings_startpage SET date=CURRENT_TIMESTAMP, title='" . $title . "', startpage_text='" . $startpage_text . "', editor='" . $editor . "'");
        } else {
            safe_query("INSERT INTO settings_startpage (date, startpage_text, editor) VALUES (NOW(), '" . $startpage_text . "', '" . $editor . "')");
        }
        echo '<div class="alert alert-success" role="alert">' . $_language->module['changes_successful'] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_startpage";
                }, 3000); // 3 Sekunden warten
            </script>';
    } else {
        echo $_language->module['transaction_invalid'];
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_startpage";
                }, 3000); // 3 Sekunden warten
            </script>';
    }
}

// Daten abrufen
$ergebnis = safe_query("SELECT * FROM settings_startpage");
$ds = mysqli_fetch_array($ergebnis);

// CAPTCHA vorbereiten
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

// Editor aktivieren, wenn Checkbox aktiviert war
  $editor_checked = '';
if (isset($ds['editor']) && $ds['editor'] == 1) {
    $editor_checked = 'checked'; // Wenn der Wert 1 ist, wird die Checkbox aktiviert
}

// Template laden
$data_array = [
    'startpage_label' => $_language->module['startpage'],
    'title_head' => $_language->module['title_head'],
    'title' => htmlspecialchars($ds['title']),
    'startpage_text' => htmlspecialchars($ds['startpage_text']),
    'editor_is_editor' => $_language->module['editor_is_editor'], // Fügt die Label für "Editor anzeigen" hinzu
    'editor_checked' => $editor_checked, // Setzt den Wert für die Checkbox
    'captcha_hash' => $hash,
    'update_button_label' => $_language->module['update']
];

echo $tpl->loadTemplate("startpage", "content", $data_array, 'admin');

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