<?php

echo '<div class="card"><div class="card-body">';
echo '<h4>Benutzer</h4>';
$get = $_database->query("SELECT userID, email, activated, registerdate FROM users");
echo '<table class="table table-striped">';
echo '<thead><tr><th>ID</th><th>Email</th><th>Aktiviert</th><th>Registriert</th></tr></thead>';
while ($ds = $get->fetch_assoc()) {
    echo '<tr>
        <td>' . $ds['userID'] . '</td>
        <td>' . htmlspecialchars($ds['email']) . '</td>
        <td>' . ($ds['activated'] ? '✔️' : '❌') . '</td>
        <td>' . date("d.m.Y H:i", $ds['registerdate']) . '</td>
    </tr>';
}
echo '</table>';
echo '</div></div>';

// ------------------------------------

echo '<div class="card mt-4"><div class="card-body">';
echo '<h4>Aktive Sessions</h4>';
$get = $_database->query("SELECT * FROM sessions");
echo '<table class="table table-striped">';
echo '<thead><tr><th>Session</th><th>UserID</th><th>IP</th><th>Letzte Aktion</th><th>Browser</th></tr></thead>';
while ($ds = $get->fetch_assoc()) {
    echo '<tr>
        <td>' . htmlspecialchars($ds['sessionID']) . '</td>
        <td>' . $ds['userID'] . '</td>
        <td>' . $ds['ip'] . '</td>
        <td>' . date("d.m.Y H:i", $ds['lastaction']) . '</td>
        <td>' . htmlspecialchars(substr($ds['browser'], 0, 40)) . '...</td>
    </tr>';
}
echo '</table>';
echo '</div></div>';

// ------------------------------------

echo '<div class="card mt-4"><div class="card-body">';
echo '<h4>Fehlgeschlagene Login-Versuche</h4>';
$get = $_database->query("SELECT * FROM failed_login_attempts");
echo '<table class="table table-striped">';
echo '<thead><tr><th>IP</th><th>Versuche</th></tr></thead>';
while ($ds = $get->fetch_assoc()) {
    echo '<tr><td>' . $ds['ip'] . '</td><td>' . $ds['wrong'] . '</td></tr>';
}
echo '</table>';
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
$get = $_database->query("SELECT * FROM banned_ips");
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
        <td>' . (isset($ds['username']) ? htmlspecialchars($ds['username']) : '<em>Unbekannt</em>') . '</td>
        <td>' . (isset($ds['email']) ? htmlspecialchars($ds['email']) : '<em>Unbekannt</em>') . '</td>
        <td>' . (isset($ds['role_name']) ? htmlspecialchars($ds['role_name']) : '<em>Keine</em>') . '</td>
        <td>' . date("d.m.Y H:i", $ds['deltime']) . '</td>
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
