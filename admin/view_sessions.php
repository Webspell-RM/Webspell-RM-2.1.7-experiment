<?php
// Session starten, falls noch nicht gestartet
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['userID'])) {
    header("Location: /admin/login.php");
    exit;
}

// Verbindung zur Datenbank herstellen (Falls noch nicht global verknüpft)
require_once('../system/config.inc.php'); // Anpassen je nach Bedarf
require_once('../system/settings.php');    // Anpassen je nach Bedarf
require_once('../system/functions.php');   // Anpassen je nach Bedarf

// Abrufen der gespeicherten Sessions
$query = $_database->query("SELECT * FROM user_sessions");
$sessions = [];
while ($row = $query->fetch_assoc()) {
    $sessions[] = $row;
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gespeicherte Sessions</title>
    <!-- Hier kannst du dein Stylesheet einbinden -->
</head>
<body>

<h1>Gespeicherte Sessions</h1>

<!-- Tabelle mit den Sessions -->
<table border="1">
    <thead>
        <tr>
            <th>UserID</th>
            <th>Session ID</th>
            <th>Session Data</th>
            <th>Letzte Aktivität</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($sessions)) { ?>
            <tr>
                <td colspan="4">Keine gespeicherten Sessions gefunden.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($sessions as $session) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($session['userID']); ?></td>
                    <td><?php echo htmlspecialchars($session['session_id']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($session['session_data'])); ?></td>
                    <td><?php echo htmlspecialchars($session['last_activity']); ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>