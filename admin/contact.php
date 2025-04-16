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
$_language->readModule('contact', false, true);

use webspell\AccessControl;

// Admin-Zugriff für das Modul prüfen
AccessControl::checkAdminAccess('ac_contact');

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

// Kontakt löschen
if (isset($_GET['delete'])) {
    $contactID = (int)$_GET['contactID'];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET['captcha_hash'])) {
        safe_query("DELETE FROM `contact` WHERE `contactID` = '$contactID'");
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

// Kontakte sortieren
elseif (isset($_POST['sortieren'])) {
    $sortcontact = $_POST['sortcontact'];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (is_array($sortcontact)) {
            foreach ($sortcontact as $sortstring) {
                list($id, $sort) = explode("-", $sortstring);
                safe_query("UPDATE `contact` SET `sort` = '$sort' WHERE `contactID` = '$id'");
            }
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

// Kontakt hinzufügen
elseif (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(['name', 'email'])) {
            safe_query("INSERT INTO `contact` (`name`, `email`, `sort`) VALUES ('$name', '$email', '1')");
        } else {
            echo $_language->module['information_incomplete'];
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

// Kontakt bearbeiten
elseif (isset($_POST['saveedit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactID = (int)$_POST['contactID'];
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {
        if (checkforempty(['name', 'email'])) {
            safe_query("UPDATE `contact` SET `name` = '$name', `email` = '$email' WHERE `contactID` = '$contactID'");
        } else {
            echo $_language->module['information_incomplete'];
        }
    } else {
        echo $_language->module['transaction_invalid'];
    }
}

// Kontaktformular anzeigen (Add/Edit)
if (isset($_GET['action'])) {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    if ($_GET['action'] == "add") {
        echo '
        <div class="card">
            <div class="card-header">' . $_language->module['contact'] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admincenter.php?site=contact">' . $_language->module['contact'] . '</a></li>
                    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add_contact'] . '</li>
                </ol>
            </nav>
            <div class="card-body">
                <form method="post" action="admincenter.php?site=contact">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">' . $_language->module['contact_name'] . ':</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="name" /></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">' . $_language->module['email'] . ':</label>
                        <div class="col-sm-8"><input type="text" name="email" class="form-control" /></div>
                    </div>
                    <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                    <button class="btn btn-success" type="submit" name="save">' . $_language->module['add_contact'] . '</button>
                </form>
            </div>
        </div>';
    } elseif ($_GET['action'] == "edit") {
        $contactID = (int)$_GET['contactID'];
        $result = safe_query("SELECT * FROM `contact` WHERE `contactID` = '$contactID'");
        $ds = mysqli_fetch_array($result);

        echo '
        <div class="card">
            <div class="card-header">' . $_language->module['contact'] . '</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admincenter.php?site=contact">' . $_language->module['contact'] . '</a></li>
                    <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit_contact'] . '</li>
                </ol>
            </nav>
            <div class="card-body">
                <form method="post" action="admincenter.php?site=contact">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">' . $_language->module['contact_name'] . ':</label>
                        <div class="col-sm-8"><input type="text" class="form-control" name="name" value="' . getinput($ds['name']) . '" /></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">' . $_language->module['email'] . ':</label>
                        <div class="col-sm-8"><input type="text" name="email" class="form-control" value="' . getinput($ds['email']) . '" /></div>
                    </div>
                    <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                    <input type="hidden" name="contactID" value="' . $contactID . '" />
                    <button class="btn btn-warning" type="submit" name="saveedit">' . $_language->module['edit_contact'] . '</button>
                </form>
            </div>
        </div>';
    }
}

// Kontaktliste
else {
    echo '
    <div class="card">
        <div class="card-header">' . $_language->module['contact'] . '</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">' . $_language->module['contact'] . '</li>
            </ol>
        </nav>
        <div class="card-body">
            <div class="mb-3 row">
                <label class="col-md-1 control-label">' . $_language->module['options'] . ':</label>
                <div class="col-md-8">
                    <a href="admincenter.php?site=contact&amp;action=add" class="btn btn-primary">' . $_language->module['new_contact'] . '</a>
                </div>
            </div>
            <form method="post" action="admincenter.php?site=contact">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>' . $_language->module['contact_name'] . '</th>
                            <th>' . $_language->module['email'] . '</th>
                            <th>' . $_language->module['actions'] . '</th>
                            <th>' . $_language->module['sort'] . '</th>
                        </tr>
                    </thead>
                    <tbody>';

    $result = safe_query("SELECT * FROM `contact` ORDER BY `sort`");
    $count = mysqli_fetch_assoc(safe_query("SELECT COUNT(*) AS cnt FROM `contact`"))['cnt'];
    $i = 1;

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    while ($ds = mysqli_fetch_array($result)) {
        echo '
        <tr>
            <td>' . getinput($ds['name']) . '</td>
            <td>' . getinput($ds['email']) . '</td>
            <td>
                <a href="admincenter.php?site=contact&amp;action=edit&amp;contactID=' . $ds['contactID'] . '" class="btn btn-warning">' . $_language->module['edit'] . '</a>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=contact&amp;delete=true&amp;contactID=' . $ds['contactID'] . '&amp;captcha_hash=' . $hash . '">
                    ' . $_language->module['delete'] . '
                </button>
                <!-- Button trigger modal END -->

                <!-- Modal -->
                <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog"><div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">' . $_language->module['contact'] . '</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="' . $_language->module['close'] . '"></button>
                        </div>
                        <div class="modal-body"><p>' . $_language->module['really_delete'] . '</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . $_language->module['close'] . '</button>
                            <a class="btn btn-danger btn-ok">' . $_language->module['delete'] . '</a>
                        </div>
                    </div></div>
                </div>
                <!-- Modal END -->
            </td>
            <td>
                <select name="sortcontact[]">';
        for ($n = 1; $n <= $count; $n++) {
            $selected = ($ds['sort'] == $n) ? ' selected' : '';
            echo '<option value="' . $ds['contactID'] . '-' . $n . '"' . $selected . '>' . $n . '</option>';
        }
        echo '</select>
            </td>
        </tr>';
        $i++;
    }

    echo '
                    <tr>
                        <td colspan="4" class="text-end">
                            <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                            <input class="btn btn-primary" type="submit" name="sortieren" value="' . $_language->module['to_sort'] . '" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>';
}
