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
        die('<div class="alert alert-danger" role="alert">
    Zugriff verweigert: Sie haben keine Rolle zugewiesen bekommen.</div>');
    }

    // Initialisierung der Rechte-Arrays
    $categoryRights = [];
    $moduleRights = [];

    if (isset($_GET['roleID'])) {
        $roleID = (int)$_GET['roleID'];

        // ✅ Modul-Liste abrufen (aus der Tabelle navigation_dashboard_links)
        $modules = [];
        $result = $_database->query("SELECT linkID, modulname, name FROM " . PREFIX . "navigation_dashboard_links ORDER BY sort ASC");
        if (!$result) {
            die("Fehler bei der Abfrage der Module: " . $_database->error);
        }
        while ($row = $result->fetch_assoc()) {
            $modules[] = $row;
        }

        // ✅ Kategorie-Liste abrufen (aus der Tabelle navigation_dashboard_categories)
        $categories = [];
        $result = $_database->query("SELECT catID, name, modulname FROM " . PREFIX . "navigation_dashboard_categories ORDER BY sort ASC");
        if (!$result) {
            die("Fehler bei der Abfrage der Kategorien: " . $_database->error);
        }
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        // ✅ Bestehende Rechte für die Rolle laden
        $stmt = $_database->prepare("SELECT type, modulname, accessID 
                                     FROM " . PREFIX . "user_admin_access_rights 
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

        // 💾 Rechte speichern (nur wenn "Speichern"-Button gedrückt!)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roleID']) && isset($_POST['save_rights'])) {
            // CSRF-Token überprüfen
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('<div class="alert alert-danger" role="alert">Ungültiges CSRF-Token. Anfrage abgelehnt.</div>');
            }

            $roleID = (int)$_POST['roleID'];

            // Rechte für Module speichern
            $grantedModules = $_POST['modules'] ?? [];
            if (!empty($grantedModules)) {
                $insertStmt = $_database->prepare("INSERT INTO " . PREFIX . "user_admin_access_rights (roleID, type, modulname, accessID) 
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
                            die("Fehler beim Speichern der neuen Rechte für Modul: " . $insertStmt->error);
                        }
                    }
                }
            }

            // Rechte für Kategorien speichern
            $grantedCategories = $_POST['category'] ?? [];
            if (!empty($grantedCategories)) {
                $insertCat = $_database->prepare("INSERT INTO " . PREFIX . "user_admin_access_rights (roleID, type, modulname, accessID) 
                                                  VALUES (?, 'category', ?, ?)
                                                  ON DUPLICATE KEY UPDATE accessID = VALUES(accessID)"); // Update für Duplikate
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
                            die("Fehler beim Speichern der Kategorien: " . $insertCat->error);
                        }
                    }
                }
            }

            // Erfolgsnachricht und Umleitung
            $_SESSION['success_message'] = $_language->module['rights_updated'];
            header("Location: /admin/admincenter.php?site=user_roles");
            exit;
        }
    }
    ?>

    <form method="post">
        <input type="hidden" name="roleID" value="<?= $roleID ?>">

        <!-- CSRF-Token im Formular einfügen -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <h4><?= $_language->module['categories'] ?></h4>
        <table class="table table-striped">
            <thead><tr><th>Modul</th><th>Zugriff</th></tr></thead>
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
        <table class="table table-striped">
            <thead><tr><th>Modul</th><th>Zugriff</th></tr></thead>
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

        <button type="submit" name="save_rights" class="btn btn-primary"><?= $_language->module['save_rights'] ?></button>
    </form>

    <?php

} elseif ($action == "user_role_details") {


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

        <a href="admincenter.php?site=user_roles&action=admins" class="btn btn-primary mt-3">Zurück zu den Rollen</a>
    </div>
    <?php




      
} elseif ($action == "admins") {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF-Überprüfung
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['csrf_error'] = "Ungültiges CSRF-Token. Anfrage abgelehnt.";
            header("Location: admincenter.php?site=user_roles&action=admins"); // Weiterleitung zur vorherigen Seite
            exit();
        }

        // Rolle zuweisen
        if (isset($_POST['assign_role'])) {
            $userID = (int)$_POST['user_id'];  // Benutzer-ID
            $roleID = (int)$_POST['role_id'];  // Rollen-ID

            // Überprüfen, ob die Rolle bereits zugewiesen wurde
            $existing_assignment = safe_query("SELECT * FROM " . PREFIX . "user_role_assignments WHERE adminID = '$userID' AND roleID = '$roleID'");
            if (mysqli_num_rows($existing_assignment) > 0) {
                $_SESSION['csrf_error'] = "Der Benutzer hat bereits diese Rolle.";
                header("Location: admincenter.php?site=user_roles&action=admins");
                exit();
            }

            // Zuweisung in der Tabelle speichern
            safe_query("INSERT INTO " . PREFIX . "user_role_assignments (adminID, roleID) VALUES ('$userID', '$roleID')");
        }
    }
    ?>
        <!-- Benutzerrolle zuweisen -->
        <h3 class="mb-4">Rolle einem Benutzer zuweisen</h3>
        <form method="post" class="row g-3 mb-5">
            <div class="col-auto">
                <label for="user_id" class="form-label">Benutzer auswählen</label>
                <select name="user_id" class="form-select" required>
                    <?php
                    $admins = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY userID");
                    while ($admin = mysqli_fetch_assoc($admins)) : ?>
                        <option value="<?= $admin['userID'] ?>"><?= htmlspecialchars($admin['nickname']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-auto">
                <label for="role_id" class="form-label">Rolle auswählen</label>
                <select name="role_id" class="form-select" required>
                    <?php
                    // Hole alle Rollen
                    $roles_for_assign = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY role_name");
                    while ($role = mysqli_fetch_assoc($roles_for_assign)) :
                    ?>
                        <option value="<?= $role['roleID'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-auto">
                <button type="submit" name="assign_role" class="btn btn-primary">Rolle zuweisen</button>
            </div>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        </form>

        <!-- Zuweisungen anzeigen -->
        <h3 class="mb-4">Zugewiesene Rollen</h3>
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Benutzername</th>
                    <th>Rolle</th>
                    <th style="width: 330px">Aktionen</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $assignments = safe_query("SELECT ur.adminID, ur.roleID, u.nickname, r.role_name AS role_name
                                       FROM " . PREFIX . "user_role_assignments ur
                                       JOIN " . PREFIX . "user u ON ur.adminID = u.userID
                                       JOIN " . PREFIX . "user_roles r ON ur.roleID = r.roleID");
            while ($assignment = mysqli_fetch_assoc($assignments)) : ?>
                <tr>
                    <td><?= htmlspecialchars($assignment['nickname']) ?></td>
                    <td><?= htmlspecialchars($assignment['role_name']) ?></td>
                    <td>
                        <a href="admincenter.php?site=user_roles&action=user_role_details&userID=<?= $assignment['adminID'] ?>" class="btn btn-sm btn-warning">
                            Zugewiesene Rechte einsehen
                        </a>
                        <a href="admincenter.php?site=user_roles&action=admins&delete_assignment=<?= $assignment['adminID'] ?>&roleID=<?= $assignment['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Rolle entfernen?')">
                            Entfernen
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Überprüfen, ob die Parameter 'delete_assignment' und 'roleID' in der URL gesetzt sind
if (isset($_GET['delete_assignment']) && isset($_GET['roleID'])) {
    // Sichere die Parameter und konvertiere sie in Ganzzahlen
    $adminID = (int)$_GET['delete_assignment'];
    $roleID = (int)$_GET['roleID'];

    // SQL-Abfrage ausführen, um die Zuweisung zu entfernen
    $result = safe_query("DELETE FROM " . PREFIX . "user_role_assignments WHERE adminID = '$adminID' AND roleID = '$roleID'");

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




   
    // Überprüfe, ob die Sitzung bereits gestartet wurde
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once("../system/sql.php");
    require_once("../system/functions.php");

    // CSRF-Token generieren und in der Session speichern
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF-Überprüfung
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['csrf_error'] = "Ungültiges CSRF-Token. Anfrage abgelehnt.";
            header("Location: admincenter.php?site=user_roles"); // Weiterleitung zur vorherigen Seite
            exit();
        }

        // Rolle zuweisen
        if (isset($_POST['assign_role'])) {
            $userID = (int)$_POST['user_id'];  // Benutzer-ID
            $roleID = (int)$_POST['role_id'];  // Rollen-ID

            // Überprüfen, ob die Rolle bereits zugewiesen wurde
            $existing_assignment = safe_query("SELECT * FROM " . PREFIX . "user_role_assignments WHERE adminID = '$userID' AND roleID = '$roleID'");
            if (mysqli_num_rows($existing_assignment) > 0) {
                $_SESSION['csrf_error'] = "Der Benutzer hat bereits diese Rolle.";
                header("Location: admincenter.php?site=user_roles");
                exit();
            }

            // Zuweisung in der Tabelle speichern
            safe_query("INSERT INTO " . PREFIX . "user_role_assignments (adminID, roleID) VALUES ('$userID', '$roleID')");
        }
    }
    #}

    // Fehler nach CSRF-Überprüfung anzeigen
    if (isset($_SESSION['csrf_error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($_SESSION['csrf_error']) ?>
        </div>
        <?php unset($_SESSION['csrf_error']); ?> <!-- Fehlernachricht nach einmaligem Anzeigen entfernen -->
    <?php endif; ?>

    <div class="container py-5">
        <h2 class="mb-4">Admin-Rollen verwalten</h2>

        <!-- Rollenliste -->
        <h3 class="mb-4">Verfügbare Rollen</h3>
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Rollenname</th>
                    <th>Rechte</th>
                    <th style="width: 250px">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $roles = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY role_name");
                while ($role = mysqli_fetch_assoc($roles)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($role['role_name']) ?></td>
                        <td><?= htmlspecialchars($role['description'] ?? 'Keine Rechte definiert') ?></td>
                        <td>
                            <a href="admincenter.php?site=user_roles&action=edit_role_rights&roleID=<?= (int)$role['roleID'] ?>" class="btn btn-sm btn-warning">
                                Rechte bearbeiten
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        
    </div>
<?php


} elseif ($action == "edit_user") {
// Verbindungsaufbau zur Datenbank und Sicherheitsprüfung
#require_once 'config.php';

if (isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Abfrage des Benutzers anhand der userID
    $user_query = safe_query("SELECT * FROM " . PREFIX . "user WHERE userID = $userID");
    $user = mysqli_fetch_assoc($user_query);

    // Wenn der Benutzer nicht existiert
    if (!$user) {
        echo "Benutzer nicht gefunden.";
        exit();
    }
}

// Wenn das Formular abgeschickt wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = mysqli_real_escape_string($connection, $_POST['nickname']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'] ? mysqli_real_escape_string($connection, $_POST['password']) : $user['password']; // Optionales Passwort

    // Update-Query, um die Benutzerdaten zu aktualisieren
    safe_query("UPDATE " . PREFIX . "user SET nickname = '$nickname', email = '$email', password = '$password' WHERE userID = $userID");

    // Bestätigungsmeldung
    $_SESSION['success_message'] = "Benutzerdaten wurden erfolgreich aktualisiert!";
    header("Location: admincenter.php?site=user_roles"); // Zurück zur Benutzerübersicht
    exit();
}

?>

<!-- HTML für das Formular -->
<div class="container py-5">
    <h2 class="mb-4">Benutzer bearbeiten</h2>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label for="nickname" class="form-label">Benutzername</label>
            <input type="text" id="nickname" name="nickname" class="form-control" value="<?= htmlspecialchars($user['nickname']) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label">Neues Passwort (optional)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-warning">Speichern</button>
        </div>
    </form>
</div>
<?php


} elseif ($action == "user_create") {






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname = mysqli_real_escape_string($_database, $_POST['nickname']);
    $email = mysqli_real_escape_string($_database, $_POST['email']);
    $password = mysqli_real_escape_string($_database, $_POST['password']);

    // Benutzer einfügen, um userID zu erhalten
    safe_query("INSERT INTO " . PREFIX . "user (nickname, email, registerdate) 
                VALUES ('$nickname', '$email', UNIX_TIMESTAMP())");

    $userID = mysqli_insert_id($_database);

    // Individuellen Pepper erzeugen
    $pepper_plain = Gen_PasswordPepper();        // zufälliger Klartext-Pepper
    $pepper_hash = Gen_Hash($pepper_plain, "");  // verschlüsselter Pepper für DB

    // Passwort hashen mit Klartext-Pepper
    $password_hash = password_hash($password . $pepper_plain, PASSWORD_BCRYPT);

    // Passwort und verschlüsselten Pepper speichern
    $query = "UPDATE " . PREFIX . "user 
              SET password_hash = '$password_hash', 
                  password_pepper = '$pepper_hash' 
              WHERE userID = '" . intval($userID) . "'";
    
    if (safe_query($query)) {
        $_SESSION['success_message'] = "Benutzer wurde erfolgreich angelegt.";
        header("Location: admincenter.php?site=user_roles");
        exit();
    } else {
        $_SESSION['error_message'] = "Fehler beim Speichern der Passwortdaten.";
    }
}
?>

<div class="container py-5">
    <h2 class="mb-4">Neuen Benutzer anlegen</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nickname" class="form-label">Benutzername</label>
            <input type="text" class="form-control" id="nickname" name="nickname" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Benutzer anlegen</button>
    </form>
</div>







<?php
} else { 


// Anzahl der Einträge pro Seite
$users_per_page = 5;

// Aktuelle Seite ermitteln
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $users_per_page;

// Anzahl der Benutzer ermitteln (für die Paginierung)
$total_users_query = safe_query("SELECT COUNT(*) as total FROM " . PREFIX . "user");
$total_users = mysqli_fetch_assoc($total_users_query)['total'];
$total_pages = ceil($total_users / $users_per_page);

if (isset($_GET['action']) && $_GET['action'] == 'delete_user' && isset($_GET['userID'])) {
    $userID = (int)$_GET['userID'];

    // Überprüfe, ob der Benutzer existiert
    $user_check = safe_query("SELECT * FROM " . PREFIX . "user WHERE userID = '$userID'");
    if (mysqli_num_rows($user_check) > 0) {
        // Zuerst die zugehörigen Einträge aus der rm_216_user_role_assignments Tabelle löschen
        safe_query("DELETE FROM " . PREFIX . "user_role_assignments WHERE adminID = '$userID'");

        // Jetzt den Benutzer aus der user Tabelle löschen
        safe_query("DELETE FROM " . PREFIX . "user WHERE userID = '$userID'");

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
    $query = "UPDATE " . PREFIX . "user SET banned = 1 WHERE userID = $userID";
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
    $query = "UPDATE " . PREFIX . "user SET banned = 0 WHERE userID = $userID";
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
$users = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY userID LIMIT $offset, $users_per_page");
?>

<div class="container py-5">
    <h2 class="mb-4">Reguläre Benutzer</h2>

    <!-- Button zum Hinzufügen eines neuen Benutzers -->
    <div class="mb-3">
        <a href="admincenter.php?site=user_roles&action=user_create" class="btn btn-sm btn-success">
            Neuen Benutzer anlegen
        </a>
    </div>

    <!-- Benutzerliste -->
    <table class="table table-bordered table-striped bg-white shadow-sm">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Benutzername</th>
                <th>Email</th>
                <th>Registriert am</th>
                <th width="350">Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                <tr>
                    <td><?= htmlspecialchars($user['userID']) ?></td>
                    <td><?= htmlspecialchars($user['nickname'] . " - " . ($user['banned'] ? 'Gebannt' : 'Nicht gebannt') ) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <!-- Datum mit date() formatieren -->
                    <td><?= date('d.m.Y H:i:s', $user['registerdate']) ?></td>
                    <td>
                        <?php    $banned = $user['banned']; // 1 = gebannt, 0 = nicht gebannt

                        // Wenn der Benutzer gebannt ist, zeige den "Unban" Button, andernfalls den "Ban" Button
                        if ($banned) {
                            echo '<form method="POST" action="">
                                    <input type="hidden" name="userID" value="'.$user['userID'].'">
                                    <button type="submit" name="unban_user" class="btn btn-success">Benutzer unbannen</button>
                                  </form>';
                        } else {
                            echo '<form method="POST" action="">
                                    <input type="hidden" name="userID" value="'.$user['userID'].'">
                                    <button type="submit" name="ban_user" class="btn btn-danger">Benutzer bannen</button>
                                  </form>';
                        }

                        ?>
                        <a href="admincenter.php?site=user_roles&action=edit_user&userID=<?= $user['userID'] ?>" class="btn btn-sm btn-warning">
                            Bearbeiten
                        </a>
                        <a href="admincenter.php?site=user_roles&action=delete_user&userID=<?= $user['userID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Möchten Sie diesen Benutzer wirklich löschen?')">
                            Löschen
                        </a>


                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginierung -->
    <nav aria-label="Seiten-Navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="admincenter.php?site=user_roles&page=1">Erste</a>
            </li>
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="admincenter.php?site=user_roles&page=<?= ($page - 1) ?>">Vorherige</a>
            </li>

            <!-- Dynamische Seitenzahlen -->
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="admincenter.php?site=user_roles&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page == $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="admincenter.php?site=user_roles&page=<?= ($page + 1) ?>">Nächste</a>
            </li>
            <li class="page-item <?= ($page == $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="admincenter.php?site=user_roles&page=<?= $total_pages ?>">Letzte</a>
            </li>
        </ul>
    </nav>
</div>


<?php
}

