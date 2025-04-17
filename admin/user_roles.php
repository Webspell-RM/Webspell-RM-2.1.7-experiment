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
#$_language->readModule('user_roles', false, true);
$_language->readModule('access_rights', false, true);
$_language->readModule('user_roles', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_user_roles');

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "edit_role_rights") {


 
// CSRF-Token generieren
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Überprüfen, ob der Benutzer berechtigt ist
if (!$userID || !checkUserRoleAssignment($userID)) {
    die('<div class="alert alert-danger" role="alert">' . $_language->module['no_role_assigned'] . '</div>');
}

// Initialisierung der Rechte-Arrays
$categoryRights = [];
$moduleRights = [];

if (isset($_GET['roleID'])) {
    $roleID = (int)$_GET['roleID'];

    // Modul-Liste abrufen
    $modules = [];
    $result = $_database->query("SELECT linkID, modulname, name FROM navigation_dashboard_links ORDER BY sort ASC");
    if (!$result) {
        die($_language->module['error_fetching_modules'] . ": " . $_database->error);
    }
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }

    // Kategorie-Liste abrufen
    $categories = [];
    $result = $_database->query("SELECT catID, name, modulname FROM navigation_dashboard_categories ORDER BY sort ASC");
    if (!$result) {
        die($_language->module['error_fetching_categories'] . ": " . $_database->error);
    }
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    // Bestehende Rechte laden
    $stmt = $_database->prepare("SELECT type, modulname, accessID 
                                 FROM user_role_admin_navi_rights 
                                 WHERE roleID = ?");
    $stmt->bind_param('i', $roleID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['type'] === 'link') {
            $moduleRights[] = $row['modulname'];
        } elseif ($row['type'] === 'category') {
            $categoryRights[] = $row['modulname'];
        }
    }

    // Rechte speichern
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roleID']) && isset($_POST['save_rights'])) {
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('<div class="alert alert-danger" role="alert">' . $_language->module['invalid_csrf'] . '</div>');
        }

        $roleID = (int)$_POST['roleID'];

        // Module speichern
        $grantedModules = $_POST['modules'] ?? [];
        if (!empty($grantedModules)) {
            $insertStmt = $_database->prepare("INSERT INTO user_role_admin_navi_rights (roleID, type, modulname, accessID) 
                                               VALUES (?, 'link', ?, ?)
                                               ON DUPLICATE KEY UPDATE accessID = VALUES(accessID)");
            foreach ($grantedModules as $modulname) {
                $linkID = null;
                foreach ($modules as $module) {
                    if ($module['modulname'] === $modulname) {
                        $linkID = $module['linkID'];
                        break;
                    }
                }

                if ($linkID !== null) {
                    $insertStmt->bind_param('ssi', $roleID, $modulname, $linkID);
                    if (!$insertStmt->execute()) {
                        die($_language->module['error_saving_module'] . ": " . $insertStmt->error);
                    }
                }
            }
        }

        // Kategorien speichern
        $grantedCategories = $_POST['category'] ?? [];
        if (!empty($grantedCategories)) {
            $insertCat = $_database->prepare("INSERT INTO user_role_admin_navi_rights (roleID, type, modulname, accessID) 
                                              VALUES (?, 'category', ?, ?)
                                              ON DUPLICATE KEY UPDATE accessID = VALUES(accessID)");
            foreach ($grantedCategories as $modulname) {
                $catID = null;
                foreach ($categories as $category) {
                    if ($category['modulname'] === $modulname) {
                        $catID = $category['catID'];
                        break;
                    }
                }

                if ($catID !== null) {
                    $insertCat->bind_param('isi', $roleID, $modulname, $catID);
                    if (!$insertCat->execute()) {
                        die($_language->module['error_saving_category'] . ": " . $insertCat->error);
                    }
                }
            }
        }

        $_SESSION['success_message'] = $_language->module['rights_updated'];
        header("Location: /admin/admincenter.php?site=user_roles&action=roles");
        exit;
    }
}
?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['edit_role_rights'] ?></li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['edit_role_rights'] ?></h2>

            <form method="post">
                <input type="hidden" name="roleID" value="<?= $roleID ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <h4><?= $_language->module['categories'] ?></h4>
                <table class="table table-bordered table-striped bg-white shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th><?= $_language->module['module'] ?></th>
                            <th><?= $_language->module['access'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $cat):
                        $translate = new multiLanguage(detectCurrentLanguage());
                        $translate->detectLanguages($cat['name']);
                        $cats = $translate->getTextByLanguage($cat['name']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($cats) ?></td>
                            <td><input type="checkbox" name="category[]" value="<?= $cat['modulname'] ?>" <?= in_array($cat['modulname'], $categoryRights) ? 'checked' : '' ?>></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h4><?= $_language->module['modules'] ?></h4>
                <table class="table table-bordered table-striped bg-white shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th><?= $_language->module['module'] ?></th>
                            <th><?= $_language->module['access'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($modules as $mod):
                        $translate->detectLanguages($mod['name']);
                        $title = $translate->getTextByLanguage($mod['name']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($title) ?></td>
                            <td><input type="checkbox" name="modules[]" value="<?= $mod['modulname'] ?>" <?= in_array($mod['modulname'], $moduleRights) ? 'checked' : '' ?>></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <button type="submit" name="save_rights" class="btn btn-warning"><?= $_language->module['save_rights'] ?></button>
            </form>
        </div>
    </div>
</div>

    <?php

} elseif ($action == "user_role_details") {



require_once("../system/sql.php");
require_once("../system/functions.php");

if (isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Benutzername und Rolle abfragen
    $query = "
        SELECT u.username, r.role_name AS name
        FROM users u
        JOIN user_role_assignments ur ON u.userID = ur.adminID
        JOIN user_roles r ON ur.roleID = r.roleID
        WHERE u.userID = '$userID'
    ";

    $result = safe_query($query);
    if ($row = mysqli_fetch_assoc($result)) {
        $username = htmlspecialchars($row['username'] ?? '');
        $role_name = htmlspecialchars($row['name']);

        // Modul-/Kategorie-Rechte der Rolle abfragen + Anzeigename holen
        $rights_query = "
            SELECT ar.type, ar.modulname, ndl.name
            FROM user_role_admin_navi_rights ar
            JOIN user_role_assignments ur ON ar.roleID = ur.roleID
            JOIN navigation_dashboard_links ndl ON ar.accessID = ndl.linkID
            WHERE ur.adminID = '$userID'
            ORDER BY ar.type, ar.modulname
        ";
        $rights_result = safe_query($rights_query);
        $role_rights_table = '';

        if (mysqli_num_rows($rights_result)) {
            $role_rights_table .= '
                <table class="table table-bordered table-striped bg-white shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>' . $_language->module['type'] . '</th>
                            <th>' . $_language->module['modulname'] . '</th>
                            <th>' . $_language->module['side_name'] . '</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            while ($r = mysqli_fetch_assoc($rights_result)) {
                $type = $r['type'] === 'category' ? $_language->module['category'] : $_language->module['module'];
                $modulname = htmlspecialchars($r['modulname']);
                $name = htmlspecialchars($r['name']);
                $translate = new multiLanguage(detectCurrentLanguage());
                $translate->detectLanguages($name);
                $side_name = $translate->getTextByLanguage($name);
                $role_rights_table .= "
                    <tr>
                        <td>$type</td>
                        <td>$modulname</td>
                        <td>$side_name</td>
                    </tr>
                ";
            }
            $role_rights_table .= '</tbody></table>';
        } else {
            $role_rights_table = '<p class="text-muted">' . $_language->module['no_rights'] . '</p>';
        }

    } else {
        $username = $_language->module['unknown_user'];
        $role_name = $_language->module['no_role_assigned'];
        $role_rights_table = '<p class="text-muted">' . $_language->module['no_rights_found'] . '</p>';
    }
} else {
    echo $_language->module['no_user_selected'];
    exit;
}
?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['user_rights_and_roles'] ?></li>
        </ol>
    </nav>
    <div class="card-body">
        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['user_rights_and_roles'] ?></h2>

            <h3><?= $_language->module['user_info'] ?></h3>
            <p><strong><?= $_language->module['username'] ?>:</strong> <?= $username ?></p>
            <p><strong><?= $_language->module['role'] ?>:</strong> <?= $role_name ?></p>

            <h4 class="mt-4"><?= $_language->module['assigned_rights'] ?></h4>
            <?= $role_rights_table ?>

            <a href="admincenter.php?site=user_roles&action=admins" class="btn btn-primary mt-3"><?= $_language->module['back_to_roles'] ?></a>
        </div>
    </div>
</div>

    <?php




      
} elseif ($action == "admins") {

    // CSRF-Token generieren, wenn es nicht existiert
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-Überprüfung
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['csrf_error'] = $_language->module['csrf_error_message']; // Fehlernachricht aus dem Spracharray
        header("Location: admincenter.php?site=user_roles&action=admins"); // Weiterleitung zur vorherigen Seite
        exit();
    }

    // Rolle zuweisen
    if (isset($_POST['assign_role'])) {
        $userID = (int)$_POST['user_id'];  // Benutzer-ID
        $roleID = (int)$_POST['role_id'];  // Rollen-ID

        // Überprüfen, ob die Rolle bereits zugewiesen wurde
        $existing_assignment = safe_query("SELECT * FROM user_role_assignments WHERE adminID = '$userID' AND roleID = '$roleID'");
        if (mysqli_num_rows($existing_assignment) > 0) {
            $_SESSION['csrf_error'] = $_language->module['role_already_assigned']; // Rolle bereits zugewiesen
            header("Location: admincenter.php?site=user_roles&action=admins");
            exit();
        }

        // Zuweisung in der Tabelle speichern
        safe_query("INSERT INTO user_role_assignments (adminID, roleID) VALUES ('$userID', '$roleID')");

        // Erfolgreiche Zuweisung
        $_SESSION['csrf_success'] = $_language->module['role_assigned_successfully']; // Erfolgsnachricht
        header("Location: admincenter.php?site=user_roles&action=admins");
        exit();
    }
}

// Fehlernachricht anzeigen
if (isset($_SESSION['csrf_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['csrf_error']) ?>
    </div>
    <?php unset($_SESSION['csrf_error']); ?> <!-- Fehlernachricht nach einmaligem Anzeigen entfernen -->
<?php endif; 

// Erfolgsnachricht anzeigen
if (isset($_SESSION['csrf_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['csrf_success']) ?>
    </div>
    <?php unset($_SESSION['csrf_success']); ?> <!-- Erfolgsnachricht nach einmaligem Anzeigen entfernen -->
<?php endif; ?>

 
<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['assign_role_to_user'] ?></li>
        </ol>
    </nav>
    <div class="card-body">
        <div class="container py-5">
            <!-- Benutzerrolle zuweisen -->
            <h3 class="mb-4"><?= $_language->module['assign_role_to_user'] ?></h3>
            <form method="post" class="row g-3 mb-5">
                <div class="col-auto">
                    <label for="user_id" class="form-label"><?= $_language->module['username'] ?></label>
                    <select name="user_id" class="form-select" required>
                        <?php
                        $admins = safe_query("SELECT * FROM users ORDER BY userID");
                        while ($admin = mysqli_fetch_assoc($admins)) : ?>
                            <option value="<?= $admin['userID'] ?>"><?= htmlspecialchars($admin['username']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-auto">
                    <label for="role_id" class="form-label"><?= $_language->module['role_name'] ?></label>
                    <select name="role_id" class="form-select" required>
                        <?php
                        // Hole alle Rollen
                        $roles_for_assign = safe_query("SELECT * FROM user_roles ORDER BY role_name");
                        while ($role = mysqli_fetch_assoc($roles_for_assign)) :
                        ?>
                            <option value="<?= $role['roleID'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" name="assign_role" class="btn btn-primary"><?= $_language->module['assign_role_to_user'] ?></button>
                </div>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            </form>

            <!-- Zuweisungen anzeigen -->
            <h3 class="mb-4"><?= $_language->module['available_roles'] ?></h3>
            <table class="table table-bordered table-striped bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th><?= $_language->module['username'] ?></th>
                        <th><?= $_language->module['role_name'] ?></th>
                        <th style="width: 330px"><?= $_language->module['actions'] ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $assignments = safe_query("SELECT ur.adminID, ur.roleID, u.username, r.role_name AS role_name
                                           FROM user_role_assignments ur
                                           JOIN users u ON ur.adminID = u.userID
                                           JOIN user_roles r ON ur.roleID = r.roleID");
                while ($assignment = mysqli_fetch_assoc($assignments)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($assignment['username']) ?></td>
                        <td><?= htmlspecialchars($assignment['role_name']) ?></td>
                        <td>
                            <a href="admincenter.php?site=user_roles&action=user_role_details&userID=<?= $assignment['adminID'] ?>" class="btn btn-sm btn-warning">
                                <?= $_language->module['view_assigned_rights'] ?>
                            </a>
                            <a href="admincenter.php?site=user_roles&action=admins&delete_assignment=<?= $assignment['adminID'] ?>&roleID=<?= $assignment['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= $_language->module['remove_role_confirm'] ?>')">
                                <?= $_language->module['remove'] ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <?php
    // Überprüfen, ob die Parameter 'delete_assignment' und 'roleID' in der URL gesetzt sind
if (isset($_GET['delete_assignment']) && isset($_GET['roleID'])) {
    // Sichere die Parameter und konvertiere sie in Ganzzahlen
    $adminID = (int)$_GET['delete_assignment'];
    $roleID = (int)$_GET['roleID'];

    // SQL-Abfrage ausführen, um die Zuweisung zu entfernen
    $result = safe_query("DELETE FROM user_role_assignments WHERE adminID = '$adminID' AND roleID = '$roleID'");

    // Erfolgreiche Löschung und Weiterleitung
    if ($result) {
        $_SESSION['success_message'] = "Rolle erfolgreich entfernt.";
    } else {
        $_SESSION['error_message'] = "Fehler beim Entfernen der Rolle.";
    }

    // Weiterleitung zur Admin-Seite für Benutzerrollen
    header("Location: admincenter.php?site=user_roles&action=admins");
    exit();
}


} elseif ($action == "roles") {

require_once("../system/sql.php");
require_once("../system/functions.php");

// CSRF-Token generieren und in der Session speichern
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-Überprüfung
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['csrf_error'] = $_language->module['csrf_error_message'];
        header("Location: admincenter.php?site=user_roles"); // Weiterleitung zur vorherigen Seite
        exit();
    }

    // Rolle zuweisen
    if (isset($_POST['assign_role'])) {
        $userID = (int)$_POST['user_id'];  // Benutzer-ID
        $roleID = (int)$_POST['role_id'];  // Rollen-ID

        // Überprüfen, ob die Rolle bereits zugewiesen wurde
        $existing_assignment = safe_query("SELECT * FROM user_role_assignments WHERE adminID = '$userID' AND roleID = '$roleID'");
        if (mysqli_num_rows($existing_assignment) > 0) {
            $_SESSION['csrf_error'] = $_language->module['role_already_assigned'];
            header("Location: admincenter.php?site=user_roles");
            exit();
        }

        // Zuweisung in der Tabelle speichern
        safe_query("INSERT INTO user_role_assignments (adminID, roleID) VALUES ('$userID', '$roleID')");

        // Erfolgsmeldung
        $_SESSION['success_message'] = $_language->module['role_assigned_successfully'];
        header("Location: admincenter.php?site=user_roles");
        exit();
    }
}

// Fehler nach CSRF-Überprüfung anzeigen
if (isset($_SESSION['csrf_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['csrf_error']) ?>
    </div>
    <?php unset($_SESSION['csrf_error']); ?> <!-- Fehlernachricht nach einmaligem Anzeigen entfernen -->
<?php endif; ?>

<!-- Erfolgsnachricht anzeigen -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
    <?php unset($_SESSION['success_message']); ?> <!-- Erfolgsnachricht nach einmaligem Anzeigen entfernen -->
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['manage_admin_roles'] ?></li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['manage_admin_roles'] ?></h2>

            <!-- Rollenliste -->
            <h3 class="mb-4"><?= $_language->module['available_roles'] ?></h3>
            <table class="table table-bordered table-striped bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th><?= $_language->module['role_name'] ?></th>
                        <th><?= $_language->module['permissions'] ?></th>
                        <th style="width: 250px"><?= $_language->module['actions'] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roles = safe_query("SELECT * FROM user_roles ORDER BY role_name");
                    while ($role = mysqli_fetch_assoc($roles)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($role['role_name']) ?></td>
                            <td><?= htmlspecialchars($role['description'] ?? $_language->module['no_permissions_defined']) ?></td>
                            <td>
                                <a href="admincenter.php?site=user_roles&action=edit_role_rights&roleID=<?= (int)$role['roleID'] ?>" class="btn btn-sm btn-warning">
                                    <?= $_language->module['edit_rights'] ?>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php


} elseif ($action == "edit_user") {

if (isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Abfrage des Benutzers anhand der userID
    $user_query = safe_query("SELECT * FROM users WHERE userID = $userID");
    $user = mysqli_fetch_assoc($user_query);

    // Wenn der Benutzer nicht existiert
    if (!$user) {
        echo $_language->module['unknown_user'];
        exit();
    }
}

// Wenn das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'] ? mysqli_real_escape_string($connection, $_POST['password']) : $user['password']; // Optionales Passwort

    // Update-Query, um die Benutzerdaten zu aktualisieren
    safe_query("UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE userID = $userID");

    // Bestätigungsmeldung
    $_SESSION['success_message'] = $_language->module['user_created_successfully']; // oder eigener Key z. B. 'user_updated'
    header("Location: admincenter.php?site=user_roles");
    exit();
}
?>

<!-- HTML für das Formular -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item">
                <a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['user_edit'] ?></li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['user_edit'] ?></h2>

            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="username" class="form-label"><?= $_language->module['username'] ?></label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label"><?= $_language->module['email'] ?></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label"><?= $_language->module['password'] ?> (<?= $_language->module['optional'] ?? 'optional' ?>)</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-warning"><?= $_language->module['save_user'] ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php


} elseif ($action == "user_create") {






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($_database, $_POST['username']);
    $email = mysqli_real_escape_string($_database, $_POST['email']);
    $password = mysqli_real_escape_string($_database, $_POST['password']);

    // Benutzer einfügen, um userID zu erhalten
    safe_query("INSERT INTO users (username, email, registerdate) 
                VALUES ('$username', '$email', UNIX_TIMESTAMP())");

    $userID = mysqli_insert_id($_database);

    // Individuellen Pepper erzeugen
    $pepper_plain = Gen_PasswordPepper();        // zufälliger Klartext-Pepper
    $pepper_hash = Gen_Hash($pepper_plain, "");  // verschlüsselter Pepper für DB

    // Passwort hashen mit Klartext-Pepper
    $password_hash = password_hash($password . $pepper_plain, PASSWORD_BCRYPT);

    // Passwort und verschlüsselten Pepper speichern
    $query = "UPDATE users 
              SET password_hash = '$password_hash', 
                  password_pepper = '$pepper_hash' 
              WHERE userID = '" . intval($userID) . "'";
    
    if (safe_query($query)) {
        $_SESSION['success_message'] = $_language->module['user_created_successfully'];
        header("Location: admincenter.php?site=user_roles");
        exit();
    } else {
        $_SESSION['error_message'] = $_language->module['user_creation_error'];
    }
}
?>
<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $_language->module['add_user'] ?></li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['add_user'] ?></h2>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label"><?= $_language->module['username'] ?></label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><?= $_language->module['email'] ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><?= $_language->module['password'] ?></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success"><?= $_language->module['add_user'] ?></button>
            </form>
        </div>

    </div>
</div>





<?php
} else { 


// Anzahl der Einträge pro Seite
$users_per_page = 5;

// Aktuelle Seite ermitteln
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $users_per_page;

// Anzahl der Benutzer ermitteln (für die Paginierung)
$total_users_query = safe_query("SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($total_users_query)['total'];
$total_pages = ceil($total_users / $users_per_page);

if (isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Überprüfe, ob der Benutzer existiert
    $user_check = safe_query("SELECT * FROM users WHERE userID = '$userID'");
    if (mysqli_num_rows($user_check) > 0) {
        // Zuerst die zugehörigen Einträge aus der rm_216_user_role_assignments Tabelle löschen
        safe_query("DELETE FROM user_role_assignments WHERE adminID = '$userID'");

        // Jetzt den Benutzer aus der user Tabelle löschen
        safe_query("DELETE FROM users WHERE userID = '$userID'");

        $_SESSION['success_message'] = "Benutzer wurde erfolgreich gelöscht.";
    } else {
        $_SESSION['error_message'] = "Benutzer nicht gefunden.";
    }

    // Weiterleitung zurück zur Benutzerverwaltung
    header("Location: admincenter.php?site=user_roles");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ban_user'])) {
    $userID = $_POST['userID'];
    $userID = intval($userID);  // Sicherheit: Umwandlung in eine ganze Zahl

    // Bann den Benutzer (Setze das Feld 'banned' auf 1)
    $query = "UPDATE users SET banned = 1 WHERE userID = $userID";
    if (safe_query($query)) {
        $_SESSION['success_message'] = "Benutzer wurde erfolgreich gebannt.";
    } else {
        $_SESSION['error_message'] = "Fehler beim Bann des Benutzers.";
    }

    // Weiterleitung oder Fehleranzeige

    header("Location: admincenter.php?site=user_roles");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unban_user'])) {
    $userID = $_POST['userID'];
    $userID = intval($userID);  // Sicherheit: Umwandlung in eine ganze Zahl

    // Hebe den Bann des Benutzers auf (Setze das Feld 'banned' auf 0)
    $query = "UPDATE users SET banned = 0 WHERE userID = $userID";
    if (safe_query($query)) {
        $_SESSION['success_message'] = "Benutzer wurde erfolgreich entbannt.";
    } else {
        $_SESSION['error_message'] = "Fehler beim Entbannen des Benutzers.";
    }

    // Weiterleitung oder Fehleranzeige
    header("Location: admincenter.php?site=user_roles");
    exit();
}


// Abfrage der Benutzer für die aktuelle Seite
$users = safe_query("SELECT * FROM users ORDER BY userID LIMIT $offset, $users_per_page");
?>

<div class="card">
    <div class="card-header">
        <i class="bi bi-paragraph"></i> <?= $_language->module['regular_users'] ?>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb t-5 p-2 bg-light">
            <li class="breadcrumb-item"><a href="admincenter.php?site=user_roles"><?= $_language->module['regular_users'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">New / Edit</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="form-group row">
            <label class="col-md-1 control-label"><?= $_language->module['options'] ?>:</label>
            <div class="col-md-8">
                <a href="admincenter.php?site=user_roles&action=roles" class="btn btn-primary" type="button"><?= $_language->module['manage_admin_roles'] ?></a>      
                <a href="admincenter.php?site=user_roles&action=admins" class="btn btn-primary" type="button"><?= $_language->module['assign_role_to_user'] ?></a>
            </div>
        </div>
        <div class="container py-5">
            <h2 class="mb-4"><?= $_language->module['regular_users'] ?></h2>
        <!-- Button zum Hinzufügen eines neuen Benutzers -->
        <div class="mb-3">
            <a href="admincenter.php?site=user_roles&action=user_create" class="btn btn-sm btn-success">
                <?= $_language->module['add_user'] ?>
            </a>
        </div>

        <!-- Benutzerliste -->
        <table class="table table-bordered table-striped bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th><?= $_language->module['id'] ?></th>
                    <th><?= $_language->module['username'] ?></th>
                    <th><?= $_language->module['email'] ?></th>
                    <th><?= $_language->module['registered_on'] ?></th>
                    <th width="350"><?= $_language->module['actions'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['userID']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?> - <?= $user['banned'] ? $_language->module['banned'] : $_language->module['not_banned'] ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= date('d.m.Y H:i:s', $user['registerdate']) ?></td>
                        <td>
                            <?php if ($user['banned']) : ?>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="userID" value="<?= $user['userID'] ?>">
                                    <button type="submit" name="unban_user" class="btn btn-success btn-sm">
                                        <?= $_language->module['unban_user'] ?>
                                    </button>
                                </form>
                            <?php else : ?>
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="userID" value="<?= $user['userID'] ?>">
                                    <button type="submit" name="ban_user" class="btn btn-danger btn-sm">
                                        <?= $_language->module['ban_user'] ?>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <a href="admincenter.php?site=user_roles&action=edit_user&userID=<?= $user['userID'] ?>" class="btn btn-sm btn-warning">
                                <?= $_language->module['edit'] ?>
                            </a>

                            <a href="admincenter.php?site=user_roles&action=delete_user&userID=<?= $user['userID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= $_language->module['confirm_delete'] ?>')">
                                <?= $_language->module['delete'] ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


    <!-- Paginierung -->
    <nav aria-label="Seiten-Navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="admincenter.php?site=user_roles&page=1">
                <?= $_language->module['first'] ?>
            </a>
        </li>
        <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="admincenter.php?site=user_roles&page=<?= ($page - 1) ?>">
                <?= $_language->module['previous'] ?>
            </a>
        </li>

        <!-- Dynamische Seitenzahlen -->
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="admincenter.php?site=user_roles&page=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= ($page == $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="admincenter.php?site=user_roles&page=<?= ($page + 1) ?>">
                <?= $_language->module['next'] ?>
            </a>
        </li>
        <li class="page-item <?= ($page == $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="admincenter.php?site=user_roles&page=<?= $total_pages ?>">
                <?= $_language->module['last'] ?>
            </a>
        </li>
    </ul>
</nav>
</div>

</div></div>
<?php
}

