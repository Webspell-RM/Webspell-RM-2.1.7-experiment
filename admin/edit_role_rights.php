<?php
// Sprachmodul laden
$_language->readModule('access_rights', false, true);

require_once("../system/sql.php");
require_once("../system/functions.php");

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_edit_role_rights');

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
