<?php
require_once("../system/sql.php");
require_once("../system/functions.php");

// Rollen und deren Rechte
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

// Rollen erstellen und Rechte zuweisen
if (isset($_POST['create_role']) && !empty($_POST['role_name'])) {
    $roleName = escape($_POST['role_name']);
    $roleRights = isset($roles_and_rights[$roleName]['rights']) ? $roles_and_rights[$roleName]['rights'] : ''; // Sicherstellen, dass Rechte gesetzt sind
    
    // Rolle in die Datenbank einfügen
    safe_query("INSERT INTO " . PREFIX . "user_roles (name, rights) VALUES ('$roleName', '$roleRights')");
}

// Rolle löschen
if (isset($_GET['delete'])) {
    $roleID = (int)$_GET['delete'];
    safe_query("DELETE FROM " . PREFIX . "user_roles WHERE roleID = '$roleID'");
}

// Rollen abrufen
$roles = safe_query("SELECT * FROM " . PREFIX . "user_roles ORDER BY name");

// Benutzer abrufen
$admins = safe_query("SELECT * FROM " . PREFIX . "user ORDER BY userID");

// Rolle zuweisen
if (isset($_POST['assign_role'])) {
    $userID = (int)$_POST['user_id'];  // Benutzer-ID
    $roleID = (int)$_POST['role_id'];  // Rollen-ID
    
    // Zuweisung in der Tabelle speichern
    safe_query("INSERT INTO " . PREFIX . "user_role_assignments (adminID, roleID) VALUES ('$userID', '$roleID')");
}

// Zuweisungen abrufen
$assignments = safe_query("SELECT ur.adminID, ur.roleID, u.nickname, r.name AS role_name
                           FROM " . PREFIX . "user_role_assignments ur
                           JOIN " . PREFIX . "user u ON ur.adminID = u.userID
                           JOIN " . PREFIX . "user_roles r ON ur.roleID = r.roleID");

?>

<div class="container py-5">
    <h2 class="mb-4">Admin-Rollen verwalten</h2>

    <!-- Neue Rolle hinzufügen -->
    <form method="post" class="row g-3 mb-5">
        <div class="col-auto">
            <label for="role_name" class="form-label">Rolle erstellen</label>
            <select name="role_name" class="form-select" required>
                <?php foreach ($roles_and_rights as $role => $data) : ?>
                    <option value="<?= $role ?>"><?= htmlspecialchars($role) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" name="create_role" class="btn btn-primary">Hinzufügen</button>
        </div>
    </form>

    <!-- Rollenliste -->
    <h3 class="mb-4">Verfügbare Rollen</h3>
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-light">
        <tr>
            <th>Rollenname</th>
            <th>Rechte</th>
            <th style="width: 150px">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($role = mysqli_fetch_assoc($roles)) : ?>
            <tr>
                <td><?= htmlspecialchars($role['name']) ?></td>
                <td><?= htmlspecialchars($role['rights'] ?? 'Keine Rechte definiert') ?></td> <!-- Falls keine Rechte gesetzt, Standardtext anzeigen -->
                <td>
                    <a href="admincenter.php?site=user_roles&delete=<?= $role['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?')">Löschen</a>
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
                <?php while ($admin = mysqli_fetch_assoc($admins)) : ?>
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
    </form>

    <!-- Zuweisungen anzeigen -->
    <h3 class="mb-4">Zugewiesene Rollen</h3>
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-light">
            <tr>
                <th>Benutzername</th>
                <th>Rolle</th>
                <th style="width: 150px">Aktionen</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($assignment = mysqli_fetch_assoc($assignments)) : ?>
            <tr>
                <td><?= htmlspecialchars($assignment['nickname']) ?></td>
                <td><?= htmlspecialchars($assignment['role_name']) ?></td>
                <td>
                    <a href="admincenter.php?site=user_roles&delete_assignment=<?= $assignment['adminID'] ?>&roleID=<?= $assignment['roleID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Rolle entfernen?')">Entfernen</a>
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

    // Zuweisung löschen
    safe_query("DELETE FROM " . PREFIX . "user_role_assignments WHERE adminID = '$adminID' AND roleID = '$roleID'");
}
?>
