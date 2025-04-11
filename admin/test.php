<?php
require_once("../system/sql.php");
require_once("../system/functions.php");

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

// Nur Admins mit Rolle 'Admin' aus der Rollen-Tabelle laden
$admins_result = safe_query("
    SELECT u.userID, u.nickname, r.name AS role_name
    FROM " . PREFIX . "user_role_assignments AS ura
    LEFT JOIN " . PREFIX . "user AS u ON ura.adminID = u.userID
    LEFT JOIN " . PREFIX . "user_roles AS r ON ura.roleID = r.roleID
    WHERE r.name = 'Admin'
    ORDER BY u.nickname
");

$admins = [];
while ($row = mysqli_fetch_assoc($admins_result)) {
    $admins[] = $row;
}

// Formular zur Auswahl eines Admins
echo '<form method="post">
    <div class="mb-3">
        <label for="adminID" class="form-label">Admin wählen</label>
        <select name="adminID" id="adminID" class="form-select" onchange="this.form.submit()">
            <option value="">-- Admin wählen --</option>';

foreach ($admins as $admin) {
    $selected = (isset($_POST['adminID']) && $_POST['adminID'] == $admin['userID']) ? 'selected' : '';
    echo '<option value="' . $admin['userID'] . '" ' . $selected . '>' . htmlspecialchars($admin['nickname']) . '</option>';
}

echo '</select>
    </div>
</form>';

// Wenn Admin gewählt → Rechte anzeigen
if (!empty($_POST['adminID'])) {
    $selectedID = (int)$_POST['adminID'];
    $selectedAdmin = null;

    foreach ($admins as $admin) {
        if ($admin['userID'] == $selectedID) {
            $selectedAdmin = $admin;
            break;
        }
    }

    if ($selectedAdmin && isset($roles_and_rights[$selectedAdmin['role_name']])) {
        echo '<h5>Rolle: ' . htmlspecialchars($selectedAdmin['role_name']) . '</h5>';
        echo '<p><strong>Rechte:</strong><br>' . nl2br(htmlspecialchars($roles_and_rights[$selectedAdmin['role_name']]['rights'])) . '</p>';
    } else {
        echo '<p class="text-danger">Diese Rolle ist nicht definiert.</p>';
    }
}
?>
