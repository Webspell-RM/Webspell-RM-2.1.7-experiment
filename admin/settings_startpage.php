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

// Wenn das Formular gesendet wurde
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $startpage_text = $_POST['message'];

    // Umwandlung der Zeilenumbrüche in <br /> für die Speicherung
    $startpage_text = nl2br($startpage_text);

    if (isset($_POST["displayed"])) {
        $displayed = 'ckeditor';
    } else {
        $displayed = '';
    }

    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (mysqli_num_rows(safe_query("SELECT * FROM settings_startpage"))) {
            safe_query("UPDATE settings_startpage SET title='" . $title . "', date='" . time() . "', startpage_text='" . $startpage_text . "', displayed='" . $displayed . "'");
        } else {
            safe_query("INSERT INTO settings_startpage (date, startpage_text, displayed) VALUES ('" . time() . "', '" . $startpage_text . "', '" . $displayed . "')");
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

$ergebnis = safe_query("SELECT * FROM settings_startpage");
$ds = mysqli_fetch_array($ergebnis);
// CAPTCHA-Transaktion erstellen
$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

// HTML-Formular anzeigen
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
                    <form class="form-horizontal" method="post" id="post" name="post" action="admincenter.php?site=settings_startpage">
                        <div class="mb-3 row">
                            <label class="col-sm-2 control-label">' . $_language->module['title_head'] . ':</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" size="60" value="' . htmlspecialchars($ds['title']) . '" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="col-sm-12">
                                <textarea class="ckeditor" id="ckeditor" rows="25" name="message" style="width: 100%;">' . htmlspecialchars($ds['startpage_text']) . '</textarea>
                            </div>
                        </div>

                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <button class="btn btn-warning" type="submit" name="submit" />' . $_language->module['update'] . '</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
