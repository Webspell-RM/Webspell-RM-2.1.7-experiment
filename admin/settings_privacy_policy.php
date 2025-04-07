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

// Überprüfen, ob der Benutzer die erforderliche Berechtigung hat
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='ac_privacy_policy'");
while ($db = mysqli_fetch_array($ergebnis)) {
    $accesslevel = $db['accesslevel'];
    if (!AccessControl::hasRole($userID, $accesslevel)) {
        die($_language->module['access_denied']);
    }
}

if (isset($_POST['submit'])) {
    // Benutzereingabe
    $privacy_policy_text = $_POST['message'];
    
    // CAPTCHA-Überprüfung
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        // Sichere SQL-Abfrage mit vorbereiteten Statements
        $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_privacy_policy`");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Datensatz existiert, also aktualisieren
            $stmt = $_database->prepare("UPDATE `" . PREFIX . "settings_privacy_policy` SET date = ?, privacy_policy_text = ?");
            $current_time = time(); // Aktuelle Zeit
            $stmt->bind_param("is", $current_time, $privacy_policy_text);
            $stmt->execute();
            $stmt->close();
        } else {
            // Datensatz existiert nicht, also neuen Eintrag erstellen
            $stmt = $_database->prepare("INSERT INTO `" . PREFIX . "settings_privacy_policy` (date, privacy_policy_text) VALUES (?, ?)");
            $current_time = time();
            $stmt->bind_param("is", $current_time, $privacy_policy_text);
            $stmt->execute();
            $stmt->close();
        }

        // Erfolgreiche Speicherung: Weiterleitung oder Bestätigung
        redirect("admincenter.php?site=settings_privacy_policy", "", 0);
    } else {
        echo $_language->module['transaction_invalid'];  // Fehlermeldung bei ungültigem CAPTCHA
    }
}

// Abrufen der bestehenden Datenschutzrichtlinie
$stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_privacy_policy`");
$stmt->execute();
$result = $stmt->get_result();
$ds = $result->fetch_array();

// CAPTCHA-Transaktion erstellen
$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

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
            ' . $_language->module['privacy_policy'] . '
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=settings_privacy_policy">' . $_language->module['privacy_policy'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
            </ol>
        </nav>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=settings_privacy_policy" onsubmit="return chkFormular();">
                        <br /><br />
                        <textarea class="ckeditor" id="ckeditor" rows="25" name="message" style="width: 100%;">' . getinput($ds['privacy_policy_text']) . '</textarea>
                        <br /><br />
                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <button class="btn btn-warning" type="submit" name="submit" />' . $_language->module['update'] . '</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    
?>
