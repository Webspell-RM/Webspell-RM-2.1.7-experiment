<?php

$_language->readModule('access_rights', false, true);

require_once("../system/sql.php");
require_once("../system/functions.php");
require_once('../system/func/access_control.php');

checkAdminAccess('ac_access_rights');

$accessControl = new \webspell\AccessControl($userID);
$categoryRights = [];
$moduleRights = [];

$roleID = isset($_GET['roleID']) ? (int)$_GET['roleID'] : 0;
$adminID = isset($_GET['adminID']) ? (int)$_GET['adminID'] : 0;

if ($roleID > 0 && $adminID > 0) {

    $modules = [];
    $mod_res = $_database->query("SELECT linkID, modulname, name FROM " . PREFIX . "navigation_dashboard_links ORDER BY sort ASC");
    while ($row = $mod_res->fetch_assoc()) $modules[] = $row;

    $categories = [];
    $cat_res = $_database->query("SELECT catID, name, modulname FROM " . PREFIX . "navigation_dashboard_categories ORDER BY sort ASC");
    while ($row = $cat_res->fetch_assoc()) $categories[] = $row;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_rights'])) {
        $_database->prepare("DELETE FROM " . PREFIX . "user_access_rights WHERE adminID = ?")->bind_param('i', $adminID)->execute();

        $grantedModules = $_POST['modules'] ?? [];
        $grantedCategories = $_POST['category'] ?? [];

        if (!empty($grantedModules)) {
            $stmt = $_database->prepare("INSERT IGNORE INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) VALUES (?, 'link', ?, ?)");
            foreach ($grantedModules as $modulname) {
                foreach ($modules as $mod) {
                    if ($mod['modulname'] == $modulname) {
                        $stmt->bind_param('isi', $adminID, $modulname, $mod['linkID']);
                        $stmt->execute();
                    }
                }
            }
        }

        if (!empty($grantedCategories)) {
            $stmt = $_database->prepare("INSERT IGNORE INTO " . PREFIX . "user_access_rights (adminID, type, modulname, accessID) VALUES (?, 'category', ?, ?)");
            foreach ($grantedCategories as $modulname) {
                foreach ($categories as $cat) {
                    if ($cat['modulname'] == $modulname) {
                        $stmt->bind_param('isi', $adminID, $modulname, $cat['catID']);
                        $stmt->execute();
                    }
                }
            }
        }

        $_SESSION['success_message'] = $_language->module['rights_updated'];
        header("Location: rolle_waehlen.php?adminID=" . $adminID);
        exit;
    }

    // Vorhandene Rechte laden
    $stmt = $_database->prepare("SELECT type, modulname FROM " . PREFIX . "user_access_rights WHERE adminID = ?");
    $stmt->bind_param('i', $adminID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['type'] == 'link') $moduleRights[] = $row['modulname'];
        if ($row['type'] == 'category') $categoryRights[] = $row['modulname'];
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
