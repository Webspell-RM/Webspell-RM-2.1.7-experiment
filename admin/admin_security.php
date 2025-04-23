<?php

echo '<div class="card"><div class="card-body">';
echo '<h4>Benutzer</h4>';
$get = $_database->query("SELECT userID, username, email, activated, registerdate FROM users");
echo '<table class="table table-striped">';
echo '<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Aktiviert</th><th>Registriert</th></tr></thead>';
while ($ds = $get->fetch_assoc()) {
    echo '<tr>
        <td>' . $ds['userID'] . '</td>
        <td>' . htmlspecialchars($ds['username']) . '</td>
        <td>' . htmlspecialchars($ds['email']) . '</td>
        <td>' . ($ds['activated'] ? '✔️' : '❌') . '</td>
        <td>' . date("d.m.Y H:i", $ds['registerdate']) . '</td>
    </tr>';
}
echo '</table>';
echo '</div></div>';

// ------------------------------------


#include('../system/config.inc.php'); // oder dein Pfad zum DB-Setup

// Session löschen, wenn Formular gesendet wurde

// Session löschen, falls POST vorhanden
if (isset($_POST['session_id'])) {
    $sessionID = $_POST['session_id'];

    $deleteQuery = $_database->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $deleteQuery->bind_param('s', $sessionID);
    $deleteQuery->execute();

    // Weiterleitung mit Erfolgs-Flag
    header("Location: /admin/admincenter.php?site=admin_security&deleted=true");
    exit;
}

// Optionale Erfolgsmeldung anzeigen
if (isset($_GET['deleted'])) {
    echo '<div class="alert alert-success">Session wurde erfolgreich gelöscht.</div>';
}

// Aktive Sessions anzeigen mit Username aus users-Tabelle
$getSessions = $_database->query("
    SELECT s.session_id, s.userID, u.username, s.user_ip, s.session_data, s.browser, s.last_activity
    FROM user_sessions s
    LEFT JOIN users u ON s.userID = u.userID
");

echo '<div class="card mt-4"><div class="card-body">';
echo '<h4>Aktive Sessions</h4>';
echo '<table class="table table-striped">';
echo '<thead><tr><th>Session ID</th><th>Username</th><th>IP</th><th>Letzte Aktion</th><th>Browser</th><th>Aktion</th></tr></thead>';

while ($ds = $getSessions->fetch_assoc()) {
    $username = isset($ds['username']) && !empty($ds['username']) ? $ds['username'] : 'Unbekannt';
    $lastActivityTimestamp = (int) $ds['last_activity'];

    if ($lastActivityTimestamp == 0) {
        $lastActivityTimestamp = time(); // fallback
    }

    $sessionTime = date("d.m.Y H:i", $lastActivityTimestamp);

    echo '<tr>
         <td>' . htmlspecialchars($ds['session_id']) . '</td>
         <td>' . htmlspecialchars($username) . '</td>
         <td>' . htmlspecialchars($ds['user_ip']) . '</td>
         <td>' . $sessionTime . '</td>
         <td>' . htmlspecialchars(substr($ds['browser'], 0, 40)) . '...</td>
         <td>
             <form method="POST" action="" onsubmit="return confirm(\'Session wirklich löschen?\');">
                 <input type="hidden" name="session_id" value="' . htmlspecialchars($ds['session_id']) . '">
                 <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
             </form>
         </td>
     </tr>';
}

echo '</table>';
echo '</div></div>';





// ------------------------------------

echo '<div class="card mt-4"><div class="card-body">';
echo '<h4>Fehlgeschlagene Login-Versuche (letzte 15 Minuten)</h4>';

// Gruppiere nach IP-Adresse und zähle die Anzahl der Versuche
$get = $_database->query("
    SELECT ip, COUNT(*) AS attempts, MAX(UNIX_TIMESTAMP(attempt_time)) AS last_attempt
    FROM failed_login_attempts
    WHERE attempt_time > NOW() - INTERVAL 15 MINUTE
    GROUP BY ip
    ORDER BY attempts DESC
");

echo '<table class="table table-striped">';
echo '<thead><tr><th>IP-Adresse</th><th>Versuche</th><th>Letzter Versuch</th></tr></thead><tbody>';

while ($ds = $get->fetch_assoc()) {
    echo '<tr>
            <td>' . htmlspecialchars($ds['ip']) . '</td>
            <td>' . (int)$ds['attempts'] . '</td>
            <td>' . date("d.m.Y H:i:s", $ds['last_attempt']) . '</td>
          </tr>';
}

echo '</tbody></table>';
echo '</div></div>';


// ------------------------------------
// GESPERRETE IPs mit Lösch-Button
// ------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ip'])) {
    $ip = $_POST['delete_ip'];
    $_database->query("DELETE FROM banned_ips WHERE ip = '" . $_database->real_escape_string($ip) . "'");
    echo '<div class="alert alert-success">IP <strong>' . htmlspecialchars($ip) . '</strong> wurde entfernt.</div>';
}

echo '<div class="card mt-4"><div class="card-body">';
echo '<h4>Gesperrte IPs</h4>';

$query = "
    SELECT 
        b.ip, 
        b.deltime, 
        b.reason,
        b.email, 
        u.username, 
        r.role_name AS role_name
    FROM banned_ips b
    LEFT JOIN users u ON b.userID = u.userID
    LEFT JOIN user_role_assignments ura ON u.userID = ura.userID
    LEFT JOIN user_roles r ON ura.roleID = r.roleID
";

$get = $_database->query($query);

echo '<table class="table table-striped">';
echo '<thead><tr>
        <th>IP</th>
        <th>Benutzername</th>
        <th>Email</th>
        <th>Rolle</th>
        <th>Entbannzeit</th>
        <th>Grund</th>
        <th>Aktion</th>
    </tr></thead>';

while ($ds = $get->fetch_assoc()) {
    echo '<tr>
        <td>' . htmlspecialchars($ds['ip']) . '</td>
        <td>' . (!empty($ds['username']) ? htmlspecialchars($ds['username']) : '<em>Unbekannt</em>') . '</td>
        <td>' . (!empty($ds['email']) ? htmlspecialchars($ds['email']) : '<em>Unbekannt</em>') . '</td>
        <td>' . (isset($ds['role_name']) ? htmlspecialchars($ds['role_name']) : '<em>Keine</em>') . '</td>
        <td>' . date("d.m.Y H:i", strtotime($ds['deltime'])) . '</td>
        <td>' . htmlspecialchars($ds['reason']) . '</td>
        <td>
            <form method="post" onsubmit="return confirm(\'IP wirklich löschen?\');" style="display:inline;">
                <input type="hidden" name="delete_ip" value="' . htmlspecialchars($ds['ip']) . '">
                <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
            </form>
        </td>
    </tr>';
}
echo '</table>';
echo '</div></div>';


