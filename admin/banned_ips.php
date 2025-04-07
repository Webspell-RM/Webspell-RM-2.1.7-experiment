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
$_language->readModule('banned_ips', false, true);

use webspell\AccessControl;

// Überprüfen, ob der Benutzer die erforderliche Berechtigung hat
$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='ac_banned_ips'");
while ($db = mysqli_fetch_array($ergebnis)) {
    $accesslevel = $db['accesslevel'];
    if (!AccessControl::hasRole($userID, $accesslevel)) {
        die($_language->module['access_denied']);
    }
}    

// CAPTCHA-Prüfung und Löschen eines Banns
if (isset($_GET['delete']) && isset($_GET['banID']) && isset($_GET['captcha_hash'])) {
    $CAPCLASS = new \webspell\Captcha;

    if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
        // Sicheres Löschen eines Banns
        $banID = (int) $_GET['banID'];
        $stmt = $_database->prepare("DELETE FROM " . PREFIX . "banned_ips WHERE banID = ?");
        $stmt->bind_param("i", $banID);
        $stmt->execute();
        $stmt->close();
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

// CSRF-Token erstellen und die Liste der Banns abrufen
$CAPCLASS = new \webspell\Captcha;
$CAPCLASS->createTransaction();
$hash = $CAPCLASS->getHash();

echo '<div class="card">
        <div class="card-header">' . $_language->module['bannedips'] . '</div>
        <div class="card-body"><br>';

$row = safe_query("SELECT * FROM " . PREFIX . "banned_ips");
$tmp = mysqli_fetch_assoc(safe_query("SELECT count(banID) AS cnt FROM " . PREFIX . "banned_ips"));
$anzpartners = $tmp['cnt'];

echo '<table class="table table-striped">
        <thead>
            <th><b>' . $_language->module['id'] . '</b></th>
            <th><b>' . $_language->module['ip'] . '</b></th>
            <th><b>' . $_language->module['deltime'] . '</b></th>
            <th><b>' . $_language->module['reason'] . '</b></th>
            <th><b>' . $_language->module['actions'] . '</b></th>
        </thead>';

$i = 1;
while ($db = mysqli_fetch_array($row)) {
    echo '<tr>
            <td>' . getinput($db['banID']) . '</td>
            <td>' . getinput($db['ip']) . '</td>
            <td>' . getformatdate($db['deltime']) . '</td>
            <td>' . getinput($db['reason']) . '</td>
            <td>
                <input class="btn btn-danger" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete'] . '\', \'admincenter.php?site=banned_ips&amp;delete=true&amp;banID=' . $db['banID'] . '&amp;captcha_hash=' . $hash . '\')" value="' . $_language->module['delete'] . '" />
            </td>
        </tr>';
}

echo '</table></div></div>';
?>
