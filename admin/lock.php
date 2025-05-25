<?php

// Überprüfen, ob die Session bereits gestartet wurde
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_language->readModule('lock', false, true);

use webspell\AccessControl;

// Admin-Zugriff für das Modul prüfen
AccessControl::checkAdminAccess('ac_lock');

// Seiteneinleitung
echo '<div class="card">
        <div class="card-header"><i class="bi bi-gear"></i> 
            '.$_language->module['settings'].' 
        </div>
        <div class="card-body">
            <a href="admincenter.php?site=settings" class="white">' . $_language->module['settings'] . '</a> 
            &raquo; ' . $_language->module['pagelock'] . '<br><br>';

if (!$closed) {

    // Seite sperren
    if (!empty($_POST['submit']) && ispageadmin($userID)) {
        $CAPCLASS = new \webspell\Captcha;

        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {

            if (mysqli_num_rows(safe_query("SELECT * FROM `lock`"))) {
                safe_query("UPDATE `lock` SET `reason`='" . $_POST['reason'] . "', `time`='" . time() . "'");
            } else {
                safe_query("INSERT INTO `lock` (`time`, `reason`) VALUES ('" . time() . "', '" . $_POST['reason'] . "')");
            }

            safe_query("UPDATE `settings` SET `closed`='1'");
            redirect("admincenter.php?site=lock", $_language->module['page_locked'], 3);
        } else {
            redirect("admincenter.php?site=lock", $_language->module['transaction_invalid'], 3);
        }

    } else {
        // Formular anzeigen für Sperrung
        $result = safe_query("SELECT * FROM `lock`");
        $ds = mysqli_fetch_array($result);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();
        $reason = !empty($ds['reason']) ? getinput($ds['reason']) : '';

        echo '<form method="post" action="admincenter.php?site=lock">
                <i class="bi bi-lock"></i> <b>' . $_language->module['pagelock'] . '</b><br />
                <small>' . $_language->module['you_can_use_html'] . '</small><br /><br />
                <textarea class="ckeditor" id="ckeditor" name="reason" rows="30" style="width: 100%;">' . $reason . '</textarea><br /><br />
                <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                <button class="btn btn-danger" type="submit" name="submit">
                    <i class="bi bi-lock"></i> ' . $_language->module['lock'] . '
                </button>
            </form>';
    }

} else {

    // Seite entsperren
    if (!empty($_POST['submit']) && isset($_POST['unlock']) && ispageadmin($userID)) {
        $CAPCLASS = new \webspell\Captcha;

        if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
            safe_query("UPDATE `settings` SET `closed`='0'");
            redirect("admincenter.php?site=lock", $_language->module['page_unlocked'], 3);
        } else {
            redirect("admincenter.php?site=lock", $_language->module['transaction_invalid'], 3);
        }

    } else {
        // Formular anzeigen für Entsperrung
        $result = safe_query("SELECT * FROM `lock`");
        $ds = mysqli_fetch_array($result);

        $CAPCLASS = new \webspell\Captcha;
        $CAPCLASS->createTransaction();
        $hash = $CAPCLASS->getHash();

        echo '<form method="post" action="admincenter.php?site=lock">
                ' . $_language->module['locked_since'] . '&nbsp;' . date("d.m.Y - H:i", $ds['time']) . '.<br /><br />
                <input type="checkbox" name="unlock" /> ' . $_language->module['unlock_page'] . '<br /><br />
                <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                <button class="btn btn-success" type="submit" name="submit">
                    <i class="bi bi-unlock"></i> ' . $_language->module['unlock'] . '
                </button>
            </form>
        </div></div>';
    }
}
