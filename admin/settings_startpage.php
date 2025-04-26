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
$_language->readModule('startpage', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_startpage');

// Formulareingaben prüfen
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $startpage_text = $_POST['message'];

    // Überprüfung, ob der Editor angezeigt wird
    $displayed = isset($_POST["displayed"]) ? 'ckeditor' : '';

    // CAPTCHA-Überprüfung
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        // Sicherer SQL-Befehl mit vorbereiteten Statements
        $stmt = $_database->prepare("SELECT * FROM `settings_startpage`");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Datensatz existiert, also aktualisieren
            $stmt = $_database->prepare("UPDATE `settings_startpage` SET title = ?, date = ?, startpage_text = ?, displayed = ?");
            $current_time = time(); // Aktuelle Zeit
            $stmt->bind_param("siss", $title, $current_time, $startpage_text, $displayed);
            $stmt->execute();
            $stmt->close();
        } else {
            // Datensatz existiert nicht, also neuen Eintrag erstellen
            $stmt = $_database->prepare("INSERT INTO `settings_startpage` (date, startpage_text, displayed, title) VALUES (?, ?, ?, ?)");
            $current_time = time();
            $stmt->bind_param("isss", $current_time, $startpage_text, $displayed, $title);
            $stmt->execute();
            $stmt->close();
        }

        // Erfolgreiche Speicherung: Weiterleitung oder Bestätigung
        redirect("admincenter.php?site=settings_startpage", "", 0);
    } else {
        echo $_language->module['transaction_invalid'];  // Fehlermeldung bei ungültigem CAPTCHA
    }
}

// Abrufen der bestehenden Startseiteneinstellungen
$stmt = $_database->prepare("SELECT * FROM `settings_startpage`");
$stmt->execute();
$result = $stmt->get_result();
$ds = $result->fetch_array();

// CAPTCHA-Transaktion erstellen
$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

// Bestimmen, ob der Editor angezeigt wird
$displayedChecked = ($ds['displayed'] == 'ckeditor') ? 'checked="checked"' : '';

echo '<script>
        <!--
        function chkFormular() {
            if (!validbbcode(document.getElementById("message").value, "admin")) {
                return false;
            }
        }
        -->
    </script>';

echo '<div class="card">
        <div class="card-header">
            ' . $_language->module['startpage'] . '
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=settings_startpage">' . $_language->module['startpage'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
            </ol>
        </nav>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=settings_startpage" onsubmit="return chkFormular();">
                        <div class="mb-3 row">
                            <label class="col-sm-2 control-label">' . $_language->module['title_head'] . ':</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" size="60" value="' . getinput($ds['title']) . '" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-2 control-label">' . $_language->module['editor_is_displayed'] . ':</label>
                            <div class="col-sm-8 form-check form-switch" style="padding: 0px 43px;">
                                <input class="form-check-input" type="checkbox" name="displayed" value="ckeditor" ' . $displayedChecked . ' />
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" id="ckeditor" rows="25" name="message" style="width: 100%;">' . getinput($ds['startpage_text']) . '</textarea>
                            </div>
                        </div>

                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <button class="btn btn-warning" type="submit" name="submit" />' . $_language->module['update'] . '</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    
?>
