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

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_user_roles');

// Überprüfe, ob die Sitzung bereits gestartet wurde, bevor session_start() aufgerufen wird
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../system/sql.php");
require_once("../system/functions.php");

// CSRF-Token generieren und in der Session speichern
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Die Dropdown-Auswahl für Rollen aus der Datenbank erstellen
$roles_and_rights = [
    'Super-Admin' => [
        'rights' => 'Vollzugriff auf alle Admin-Bereiche und -Funktionen. Kann Rollen und Benutzer verwalten. Kann Systemeinstellungen und andere sicherheitsrelevante Bereiche ändern. Kann alle Datenbanken und Daten im System bearbeiten.'
    ],
    'Admin' => [
        'rights' => 'Zugriff auf alle normalen Admin-Bereiche, aber ohne die Möglichkeit, Rollen oder Benutzerdaten zu bearbeiten. Kann Benutzer und Inhalte verwalten (z.B. Artikel, Kommentare, etc.). Kann Systemeinstellungen und Konfigurationen einsehen, jedoch nicht ändern.'
    ],
    'Moderator' => [
        'rights' => 'Kann Benutzerinhalte wie Forenbeiträge, Kommentare und andere interaktive Inhalte moderieren. Kann bestimmte Daten bearbeiten oder löschen (abhängig von den Rechten). Hat keinen Zugriff auf Benutzerverwaltung oder Systemkonfiguration.'
    ],
    'Editor' => [
        'rights' => 'Kann Inhalte erstellen, bearbeiten und veröffentlichen (z.B. Artikel, Seiten). Hat keinen Zugriff auf Benutzermanagement oder die Verwaltung von Systemdaten.'
    ],
    'Support' => [
        'rights' => 'Kann Support-Tickets bearbeiten und beantworten. Kann Benutzerdaten einsehen, aber nicht ändern. Hat keinen Zugriff auf Verwaltungsfunktionen oder Inhalte.'
    ],
    'Leserechte' => [
        'rights' => 'Kann nur Informationen einsehen, aber keine Änderungen vornehmen. Diese Rolle ist nützlich für Nutzer, die Einsicht in Admin-Bereiche benötigen, aber keine Änderungen vornehmen dürfen.'
    ]
];

// Dropdown-Liste für Rollen generieren
$roles_dropdown = '';
foreach ($roles_and_rights as $role_name => $data) {
    $roles_dropdown .= "<option value='$role_name'>$role_name</option>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF-Überprüfung
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['csrf_error'] = "Ungültiges CSRF-Token. Anfrage abgelehnt.";
        header("Location: admincenter.php?site=user_roles"); // Weiterleitung zur vorherigen Seite
        exit();
    }

    // Rolle erstellen
    if (isset($_POST['create_role']) && !empty($_POST['role_name'])) {
        $roleName = escape($_POST['role_name']);

        // Überprüfen, ob die Rolle bereits existiert
        $existing_role = safe_query("SELECT * FROM " . PREFIX . "user_roles WHERE name = '$roleName'");
        if (mysqli_num_rows($existing_role) > 0) {
            $_SESSION['csrf_error'] = "Die Rolle '$roleName' existiert bereits.";
            header("Location: admincenter.php?site=user_roles");
            exit();
        }

        $roleRights = isset($roles_and_rights[$roleName]['rights']) ? $roles_and_rights[$roleName]['rights'] : ''; // Sicherstellen, dass Rechte gesetzt sind
        safe_query("INSERT INTO " . PREFIX . "user_roles (name, rights) VALUES ('$roleName', '$roleRights')");
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

// Fehler nach CSRF-Überprüfung anzeigen
if (isset($_SESSION['csrf_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['csrf_error']) ?>
    </div>
    <?php unset($_SESSION['csrf_error']); ?> <!-- Fehlernachricht nach einmaligem Anzeigen entfernen -->
<?php endif; ?>

<div class="container py-5">
    <h2 class="mb-4">Admin-Rollen verwalten</h2>

    <!-- Neue Rolle hinzufügen -->
    <form method="post" class="row g-3 mb-5">
        <div class="col-auto">
            <label for="role_name" class="form-label">Rolle erstellen</label>
            <select name="role_name" class="form-select" required>
                <?php echo $roles_dropdown; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" name="create_role" class="btn btn-primary">Hinzufügen</button>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    </form>

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
        $roles = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY name");
        while ($role = mysqli_fetch_assoc($roles)) : ?>
            <tr>
                <td><?= htmlspecialchars($role['name']) ?></td>
                <td><?= htmlspecialchars($role['rights'] ?? 'Keine Rechte definiert') ?></td>
                <td>
                    <a href="admincenter.php?site=edit_role_rights&roleID=<?= (int)$role['roleID'] ?>" class="btn btn-sm btn-warning">
                        Rechte bearbeiten
                    </a>
                    <a href="admincenter.php?site=user_roles&delete=<?= (int)$role['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen? Alle Zuweisungen dieser Rolle werden ebenfalls entfernt!')">
                        Löschen
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

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
                $roles_for_assign = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY name");
                while ($role = mysqli_fetch_assoc($roles_for_assign)) :
                ?>
                    <option value="<?= $role['roleID'] ?>"><?= htmlspecialchars($role['name']) ?></option>
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
        $assignments = safe_query("SELECT ur.adminID, ur.roleID, u.nickname, r.name AS role_name
                                   FROM " . PREFIX . "user_role_assignments ur
                                   JOIN " . PREFIX . "user u ON ur.adminID = u.userID
                                   JOIN " . PREFIX . "user_roles r ON ur.roleID = r.roleID");
        while ($assignment = mysqli_fetch_assoc($assignments)) : ?>
            <tr>
                <td><?= htmlspecialchars($assignment['nickname']) ?></td>
                <td><?= htmlspecialchars($assignment['role_name']) ?></td>
                <td>
                    <a href="admincenter.php?site=user_role_details&userID=<?= $assignment['roleID'] ?>" class="btn btn-sm btn-warning">
                        Zugewiesene Rechte einsehen
                    </a>
                    <a href="admincenter.php?site=user_roles&delete_assignment=<?= $assignment['adminID'] ?>&roleID=<?= $assignment['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Rolle entfernen?')">
                        Entfernen
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
// Rolle entfernen
if (isset($_GET['delete_assignment'])) {
    $adminID = (int)$_GET['delete_assignment'];
    $roleID = (int)$_GET['roleID'];

    safe_query("DELETE FROM " . PREFIX . "user_role_assignments WHERE adminID = '$adminID' AND roleID = '$roleID'");
    header("Location: admincenter.php?site=user_roles");
    exit();
}

// Rolle löschen
if (isset($_GET['delete'])) {
    $roleID = (int)$_GET['delete'];
    safe_query("DELETE FROM " . PREFIX . "user_roles WHERE roleID = '$roleID'");
    safe_query("DELETE FROM " . PREFIX . "user_role_assignments WHERE roleID = '$roleID'"); // Zuweisungen löschen
    header("Location: admincenter.php?site=user_roles");
    exit();
}
?>
