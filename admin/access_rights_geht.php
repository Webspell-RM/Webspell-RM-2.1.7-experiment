<?php

// Sprachmodul laden
$_language->readModule('access_rights', false, true);

// Den Admin-Zugriff für das Modul überprüfen
checkAdminAccess('ac_access_rights');  // Modulname für diese Seite

require_once('../system/func/access_control.php');
$accessControl = new \webspell\AccessControl($userID);

// 🔐 Admin-Liste abrufen
$roleID = 1; // Beispiel: die Rolle, die du laden möchtest
$admins = [];

$result = $_database->query("SELECT roleID
                              FROM " . PREFIX . "user_roles
                              ORDER BY roleID ASC");

if (!$result) {
    die("Fehler bei der Abfrage der Admins: " . $_database->error);
}

while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}

// ✅ Modul-Liste abrufen
$modules = [];
$result = $_database->query("SELECT linkID, modulname, name FROM " . PREFIX . "navigation_dashboard_links ORDER BY sort ASC");
while ($row = $result->fetch_assoc()) {
    $modules[] = $row;
}

// ✅ Kategorie-Liste abrufen
$categories = [];
$result = $_database->query("SELECT catID, name, modulname FROM " . PREFIX . "navigation_dashboard_categories ORDER BY sort ASC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo '<form method="post">
    <div class="mb-3">
        <label for="adminID" class="form-label">' . $_language->module['select_admin'] . '</label>
        <select name="adminID" id="adminID" class="form-select" onchange="this.form.submit()">
            <option value="">-- Admin wählen --</option>';

foreach ($admins as $admin) {
    echo '<option value="' . $admin['userID'] . '" ' . (isset($_POST['adminID']) && $_POST['adminID'] == $admin['userID'] ? 'selected' : '') . '>' . htmlspecialchars($admin['nickname']) . '</option>';
}

echo '</select>
    </div>
</form>';


// 💾 Rechte speichern (nur wenn "Speichern"-Button gedrückt!)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adminID']) && isset($_POST['modules']) && isset($_POST['save_rights'])) {
    $adminID = (int)$_POST['adminID'];
    $grantedModules = $_POST['modules'] ?? []; 
    $grantedCategories = $_POST['category'] ?? [];  // `category` anstelle von `access['category']`

    // Bestehende Rechte löschen
    $stmt = $_database->prepare("DELETE FROM " . PREFIX . "user_access_rights WHERE adminID = ?");
    $stmt->bind_param('i', $adminID);
    if (!$stmt->execute()) {
        die("Fehler beim Löschen der bestehenden Rechte: " . $stmt->error);
    }

    // Neue Rechte für Module speichern
if (!empty($grantedModules)) {
    $insertStmt = $_database->prepare("INSERT IGNORE INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) VALUES (?, 'link', ?, ?)");
    foreach ($grantedModules as $modulname) {
        $linkID = null;  // Hier kannst du den accessID-Wert bestimmen, wenn nötig

        // Füge Logik hinzu, um den richtigen accessID-Wert zu bestimmen, falls erforderlich
        foreach ($modules as $module) {
            if ($module['modulname'] === $modulname) {
                $linkID = $module['linkID']; // hol dir die linkID, die zu diesem modulname gehört
                break;
            }
        }

        // Wenn der accessID-Wert korrekt ist, binde ihn
        if ($linkID !== null) {
            $insertStmt->bind_param('iss', $adminID, $modulname, $linkID);
            if (!$insertStmt->execute()) {
                die("Fehler beim Speichern der neuen Rechte für Modul: " . $insertStmt->error);
            }
        }
    }
}

    // Neue Rechte für Kategorien speichern (speichern des modulname statt catID)
if (!empty($grantedCategories)) {
    $insertCat = $_database->prepare("INSERT IGNORE INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) VALUES (?, 'category', ?, ?)");
    foreach ($grantedCategories as $modulname) {
        // Hier übergibst du den modulname und die catID
        $catID = null;
        foreach ($categories as $category) {
            if ($category['modulname'] === $modulname) {
                $catID = $category['catID']; // hol dir die catID, die zu diesem modulname gehört
                break;
            }
        }

        if ($catID !== null) {
            $insertCat->bind_param('isi', $adminID, $modulname, $catID); // Jetzt wird die catID als accessID verwendet
            if (!$insertCat->execute()) {
                die("Fehler beim Speichern der Kategorien: " . $insertCat->error);
            }
        }
    }
}

    // Erfolgsnachricht und Umleitung
    $_SESSION['success_message'] = $_language->module['rights_updated'];
    header("Location: /admin/admincenter.php?site=access_rights");
    exit;
}


// Wenn Admin ausgewählt ist, Rechte anzeigen
if (!empty($_POST['adminID'])):
    $adminID = (int)$_POST['adminID'];

    // Bestehende Rechte laden
    $moduleRights = [];
    $categoryRights = [];

    $stmt = $_database->prepare("SELECT type, modulname, accessID FROM " . PREFIX . "user_access_rights WHERE adminID = ?");
    $stmt->bind_param('i', $adminID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['type'] === 'link') {
            $moduleRights[] = $row['modulname'];
        } elseif ($row['type'] === 'category') {
            $categoryRights[] = $row['modulname']; // Hier wird der modulname gespeichert
        }
    }
?>

<!-- 📝 Rechte bearbeiten -->
<form method="post">
    <input type="hidden" name="adminID" value="<?= $adminID ?>">

    <!-- Kategorien -->
    <h4><?= $_language->module['categories'] ?></h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $_language->module['modul'] ?></th>
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
                    <td>
                        <input type="checkbox" name="category[]" value="<?= $cat['modulname'] ?>"
                               <?= in_array($cat['modulname'], $categoryRights) ? 'checked' : '' ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Module -->
    <h4><?= $_language->module['modules'] ?></h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $_language->module['modul'] ?></th>
                <th><?= $_language->module['access'] ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module): 
                $translate->detectLanguages($module['name']);
                $title = $translate->getTextByLanguage($module['name']);
            ?>
                <tr>
                    <td><?= htmlspecialchars($title) ?></td>
                    <td>
                        <input type="checkbox" name="modules[]" value="<?= $module['modulname'] ?>"
                               <?= in_array($module['modulname'], $moduleRights) ? 'checked' : '' ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" name="save_rights" class="btn btn-primary"><?= $_language->module['save_rights'] ?></button>
</form>

<?php endif; ?>
