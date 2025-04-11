<?php

// Sprachmodul laden
$_language->readModule('access_rights', false, true);

require_once("../system/sql.php");
require_once("../system/functions.php");

// Den Admin-Zugriff für das Modul überprüfen
checkAdminAccess('edit_role_rights');  // Modulname für diese Seite

require_once('../system/func/access_control.php');
#$accessControl = new \webspell\AccessControl($userID);

// Initialisierung der Rechte-Arrays, um Fehler zu vermeiden
$categoryRights = [];
$moduleRights = [];

if (isset($_GET['roleID'])) {
    $roleID = (int)$_GET['roleID'];

    // 🔐 Admin-Rollen abrufen (aus der Tabelle rm_216_user_roles)
   
$adminID=$roleID;


echo "<pre>";
var_dump($roleID);  // Ausgabe der Admins mit `adminID` und `roleID`
echo "</pre>";



    // ✅ Modul-Liste abrufen (aus der Tabelle navigation_dashboard_links)
    $modules = [];
    $result = $_database->query("SELECT linkID, modulname, name FROM " . PREFIX . "navigation_dashboard_links ORDER BY sort ASC");
    if (!$result) {
        die("Fehler bei der Abfrage der Module: " . $_database->error);
    }
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }

    echo "<pre>";
    #var_dump($modules);  // Prüfen, ob Modul-Daten geladen wurden
    echo "</pre>";

    // ✅ Kategorie-Liste abrufen (aus der Tabelle navigation_dashboard_categories)
    $categories = [];
    $result = $_database->query("SELECT catID, name, modulname FROM " . PREFIX . "navigation_dashboard_categories ORDER BY sort ASC");
    if (!$result) {
        die("Fehler bei der Abfrage der Kategorien: " . $_database->error);
    }
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo "<pre>";
    #var_dump($categories);  // Prüfen, ob Kategoriedaten geladen wurden
    echo "</pre>";

    // 💾 Rechte speichern (nur wenn "Speichern"-Button gedrückt!)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adminID']) && isset($_POST['modules']) && isset($_POST['save_rights'])) {
        $adminID = (int)$_POST['adminID'];
        $grantedModules = $_POST['modules'] ?? []; 
        $grantedCategories = $_POST['category'] ?? [];  // `category` anstelle von `access['category']`

        // Bestehende Rechte löschen (aus der Tabelle rm_216_user_access_rights)
        $stmt = $_database->prepare("DELETE FROM " . PREFIX . "user_access_rights WHERE adminID = ?");
        $stmt->bind_param('i', $adminID);
        if (!$stmt->execute()) {
            die("Fehler beim Löschen der bestehenden Rechte: " . $stmt->error);
        }

        // Rechte für Module speichern
        if (!empty($grantedModules)) {
            // Verwenden von "INSERT INTO ... ON DUPLICATE KEY UPDATE" statt "IGNORE", um Duplikate zu vermeiden
            $insertStmt = $_database->prepare("INSERT INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) 
                                               VALUES (?, 'link', ?, ?)
                                               ON DUPLICATE KEY UPDATE accessID = VALUES(accessID)"); // Update nur, wenn ein Duplikat gefunden wird
            foreach ($grantedModules as $modulname) {
                $linkID = null; // Hole die linkID des Moduls
                foreach ($modules as $module) {
                    if ($module['modulname'] === $modulname) {
                        $linkID = $module['linkID'];
                        break;
                    }
                }

                if ($linkID !== null) {
                    $insertStmt->bind_param('iss', $adminID, $modulname, $linkID);
                    if (!$insertStmt->execute()) {
                        die("Fehler beim Speichern der neuen Rechte für Modul: " . $insertStmt->error);
                    }
                }
            }
        }

        // Rechte für Kategorien speichern
        if (!empty($grantedCategories)) {
            $insertCat = $_database->prepare("INSERT INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) 
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
                    $insertCat->bind_param('isi', $adminID, $modulname, $catID);
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

    // Wenn Admin ausgewählt ist, Rechte anzeigen
if (!empty($_POST['adminID']) && !empty($_POST['roleID'])):
    $adminID = (int)$_POST['adminID'];
    $roleID = (int)$_POST['roleID'];  // roleID aus dem POST-Array

    // Bestehende Rechte laden
    $stmt = $_database->prepare("SELECT type, modulname, accessID 
                                 FROM " . PREFIX . "user_access_rights 
                                 WHERE adminID = ? AND roleID = ?");
    $stmt->bind_param('ii', $adminID, $roleID);  // adminID und roleID binden
    $stmt->execute();
    $result = $stmt->get_result();  // Ergebnis holen
    while ($row = $result->fetch_assoc()) {
        if ($row['type'] === 'link') {
            $moduleRights[] = $row['modulname'];
        } elseif ($row['type'] === 'category') {
            $categoryRights[] = $row['modulname']; // Hier wird der modulname gespeichert
        }
    }
endif;



if (isset($_GET['roleID'])) {
    echo$roleID = (int)$_GET['roleID']; 
    echo' Hier kannst du sicherstellen, dass die roleID gesetzt ist';
} else {
    die("Keine RoleID übergeben.");
}
?>



<form method="post">
        <input type="hidden" name="adminID" value="<?= $adminID ?>">
        <input type="hidden" name="roleID" value="<?= $roleID ?>">

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
}
?>