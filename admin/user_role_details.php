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
#$_language->readModule('user_role_details', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_user_role_details');

require_once("../system/sql.php");
require_once("../system/functions.php");

if (isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Benutzername und Rolle abfragen
    $query = "
        SELECT u.nickname, r.role_name AS name
        FROM " . PREFIX . "user u
        JOIN " . PREFIX . "user_role_assignments ur ON u.userID = ur.adminID
        JOIN " . PREFIX . "user_roles r ON ur.roleID = r.roleID
        WHERE u.userID = '$userID'
    ";

    $result = safe_query($query);
    if ($row = mysqli_fetch_assoc($result)) {
        $nickname = htmlspecialchars($row['nickname'] ?? '');
        $role_name = htmlspecialchars($row['name']);

        // Modul-/Kategorie-Rechte der Rolle abfragen + Anzeigename holen
        $rights_query = "
            SELECT ar.type, ar.modulname, ndl.name
            FROM " . PREFIX . "user_admin_access_rights ar
            JOIN " . PREFIX . "user_role_assignments ur ON ar.roleID = ur.roleID
            JOIN " . PREFIX . "navigation_dashboard_links ndl ON ar.accessID = ndl.linkID
            WHERE ur.adminID = '$userID'
            ORDER BY ar.type, ar.modulname
        ";
        $rights_result = safe_query($rights_query);
        $role_rights_table = '';

        if (mysqli_num_rows($rights_result)) {
            $role_rights_table .= '
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Typ</th>
                            <th>Modulname</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            while ($r = mysqli_fetch_assoc($rights_result)) {
                $type = $r['type'] === 'category' ? 'Kategorie' : 'Modul';
                $modulname = htmlspecialchars($r['modulname']);
                $name = htmlspecialchars($r['name']);
                $role_rights_table .= "
                    <tr>
                        <td>$type</td>
                        <td>$modulname</td>
                        <td>$name</td>
                    </tr>
                ";
            }
            $role_rights_table .= '</tbody></table>';
        } else {
            $role_rights_table = '<p class="text-muted">Keine Rechte für diese Rolle eingetragen.</p>';
        }

    } else {
        $nickname = 'Unbekannter Benutzer';
        $role_name = 'Keine Rolle zugewiesen';
        $role_rights_table = '<p class="text-muted">Keine Rechte gefunden.</p>';
    }
} else {
    echo "Kein Benutzer ausgewählt.";
    exit;
}
?>

<div class="container py-5">
    <h2 class="mb-4">Benutzerrechte und -rolle anzeigen</h2>

    <h3>Benutzerinformationen</h3>
    <p><strong>Benutzername:</strong> <?= $nickname ?></p>
    <p><strong>Rolle:</strong> <?= $role_name ?></p>

    <h4 class="mt-4">Zugewiesene Rechte</h4>
    <?= $role_rights_table ?>

    <a href="admincenter.php?site=user_roles" class="btn btn-primary mt-3">Zurück zu den Rollen</a>
</div>
