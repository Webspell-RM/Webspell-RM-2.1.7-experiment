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

$_language->readModule('lock', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_lock');

echo '<div class="card">
        <div class="card-header"><i class="bi bi-gear"></i> 
            '.$_language->module['settings'].' 
        </div>
            <div class="card-body">
                        <a href="admincenter.php?site=settings" class="white">' . $_language->module[ 'settings' ] .
    '</a> &raquo; ' . $_language->module[ 'pagelock' ] . '<br><br>';

if (!$closed) {
    if (isset($_POST[ 'submit' ]) != "" && ispageadmin($userID)) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
            if (mysqli_num_rows(safe_query("SELECT * FROM `" . PREFIX . "lock`"))) {
                safe_query(
                    "UPDATE " . PREFIX . "lock SET reason='" . $_POST[ 'reason' ] . "', time='" . time() .
                    "'"
                );
            } else {
                safe_query(
                    "INSERT INTO " . PREFIX . "lock (`time`, `reason`) values( '" . time() . "', '" .
                    $_POST[ 'reason' ] . "') "
                );
            }
            safe_query("UPDATE " . PREFIX . "settings SET closed='1'");

            redirect("admincenter.php?site=lock", $_language->module[ 'page_locked' ], 3);
        } else {
            redirect("admincenter.php?site=lock", $_language->module[ 'transaction_invalid' ], 3);
        }
    } else {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "lock");
        $ds = mysqli_fetch_array($ergebnis);
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        if(empty($ds[ 'reason' ])) { $reason = ''; } else { $reason = getinput($ds[ 'reason' ]); }
        echo '<form method="post" action="admincenter.php?site=lock"><i class="bi bi-lock"></i> <b>' . $_language->module[ 'pagelock' ] .
            '</b><br /><small>' . $_language->module[ 'you_can_use_html' ] . '</small><br /><br />
            <textarea class="ckeditor" id="ckeditor" name="reason" rows="30" cols="" style="width: 100%;">'.$reason.'
            </textarea><br /><br /><input type="hidden" name="captcha_hash" value="' . $hash . '" />
			<button class="btn btn-danger" type="submit" name="submit"><i class="bi bi-lock"></i> ' . $_language->module['lock'] . '</button>
			</form>';
    }
} else {
    if (isset($_POST[ 'submit' ]) != "" && isset($_POST[ 'unlock' ]) && ispageadmin($userID)) {
        $CAPCLASS = new \webspell\Captcha;
        if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {

            safe_query("UPDATE " . PREFIX . "settings SET closed='0'");

            redirect("admincenter.php?site=lock", $_language->module[ 'page_unlocked' ], 3);
        } else {
            redirect("admincenter.php?site=lock", $_language->module[ 'transaction_invalid' ], 3);
        }
    } else {
        $ergebnis = safe_query("SELECT * FROM " . PREFIX . "lock");
        $ds = mysqli_fetch_array($ergebnis);
        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        echo '<form method="post" action="admincenter.php?site=lock">
    ' . $_language->module[ 'locked_since' ] . '&nbsp;' . date("d.m.Y - H:i", $ds[ 'time' ]) . '.<br /><br />
    <input type="checkbox" name="unlock" /> ' . $_language->module[ 'unlock_page' ] . '<br /><br />
    <input type="hidden" name="captcha_hash" value="' . $hash . '" />
	<button class="btn btn-success" type="submit" name="submit"><i class="bi bi-unlock"></i> ' . $_language->module['unlock'] . '</button>
    </form>
    </div></div>';
    }
}
