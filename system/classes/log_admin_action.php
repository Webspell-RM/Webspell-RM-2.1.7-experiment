<?php
/*function log_admin_action($adminID, $action, $table = null, $recordID = null, $oldValue = null, $newValue = null, $module = null) {
    global $_database;
    
    $stmt = $_database->prepare("
        INSERT INTO admin_audit_log
        (adminID, action, affected_table, affected_id, old_value, new_value, timestamp, ip_address, module)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)
    ");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt->bind_param('sssissss', $adminID, $action, $table, $recordID, $oldValue, $newValue, $ip, $module);
    $stmt->execute();
}

// Ermitteln des action-codes basierend auf der Aktion
function write_admin_log($adminID, $action_text, $module, $affected_id, $old_value, $new_value, $ip_address, $timestamp = null, $affected_table = null, $old_data = null, $new_data = null) {
    // Wenn kein Timestamp übergeben wird, setze ihn auf die aktuelle Zeit
    if ($timestamp === null) {
        $timestamp = date('Y-m-d H:i:s');
    }

    // Ermitteln des action-codes basierend auf der Aktion
    $action_codes = [
        'Erstellen' => 1,
        'Bearbeiten' => 2,
        'Löschen' => 3,
        'Login' => 4,
        'Logout' => 5,
        // Weitere Aktionen hinzufügen...
    ];

    global $_database, $userID;

    $adminID = (int) $userID;  // Verwende den aktuellen Benutzer ID aus der Session
    $action = $action_codes[$action_text] ?? 0; // Aktion als Code ermitteln
    $module = $_database->escape_string($module);
    $affected_table = $_database->escape_string($affected_table);
    $old_value = $_database->escape_string($old_value ?? ''); // Falls keine alten Werte, setze leere Zeichenkette
    $new_value = $_database->escape_string($new_value ?? ''); // Falls keine neuen Werte, setze leere Zeichenkette
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // JSON-kodierte Daten, falls erforderlich
    $old_data = $_database->escape_string(json_encode($old_data ?? []));
    $new_data = $_database->escape_string(json_encode($new_data ?? []));

    // SQL-Query, jetzt mit allen Feldern
    $_database->query("INSERT INTO admin_audit_log 
    (adminID, action, module, affected_table, affected_id, old_value, new_value, ip_address, timestamp) 
    VALUES 
    ('$adminID', '$action', '$module', '$affected_table', '$affected_id', '$old_value', '$new_value', '$ip_address', NOW())");
}









function escape($string) {
    global $_database; // oder deine DB-Variable
    return mysqli_real_escape_string($_database, $string);
}

*/

namespace webspell\logging;

class AdminLogger
{
    public static function log(
        int $adminID,
        string $action_text,
        string $module,
        int $affected_id = 0,
        ?string $old_value = null,
        ?string $new_value = null,
        ?string $ip_address = null,
        ?int $timestamp = null,
        string $affected_table = ''
    ): void
    {
        global $_database;

        $action_codes = [
            'Erstellen' => 1,
            'Bearbeiten' => 2,
            'Löschen' => 3,
            'Login' => 4,
            'Logout' => 5,
        ];

        $action = $action_codes[$action_text] ?? 0;
        $ip = $ip_address ?? $_SERVER['REMOTE_ADDR'];
        $ts = $timestamp ?? time();

        $stmt = $_database->prepare("
            INSERT INTO admin_audit_log (
                adminID, action, module, affected_table,
                affected_id, old_value, new_value, ip_address, timestamp
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, FROM_UNIXTIME(?))
        ");

        if (!$stmt) {
            // Optional: Fehlerbehandlung
            error_log("Prepare failed: " . $_database->error);
            return;
        }

        $stmt->bind_param(
            "iississsi", // Typen: i=int, s=string
            $adminID,
            $action,
            $module,
            $affected_table,
            $affected_id,
            $old_value,
            $new_value,
            $ip,
            $ts
        );

        $stmt->execute();
        $stmt->close();
    }



public static function updateWithLog(
        string $table,
        string $id_column,
        int $affected_id,
        array $new_data,
        string $action_text,
        string $module,
        int $adminID
    ): void
    {
        global $_database;

        // Alte Daten holen
        $stmt = $_database->prepare("SELECT * FROM `$table` WHERE `$id_column` = ?");
        $stmt->bind_param("i", $affected_id);
        $stmt->execute();
        $old_result = $stmt->get_result();
        $old_row = $old_result->fetch_assoc() ?? [];

        // JSON kodieren
        $old_value = json_encode($old_row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $new_value = json_encode($new_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Log speichern
        $action_codes = [
            'Erstellen' => 1,
            'Bearbeiten' => 2,
            'Löschen' => 3,
            'Login' => 4,
            'Logout' => 5,
        ];
        $action = $action_codes[$action_text] ?? 0;
        $ip = $_SERVER['REMOTE_ADDR'];
        $timestamp = time();

        $stmt = $_database->prepare("
            INSERT INTO admin_audit_log (
                adminID, action, module, affected_table,
                affected_id, old_value, new_value, ip_address, timestamp
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, FROM_UNIXTIME(?))
        ");
        $stmt->bind_param(
            "ississssi",
            $adminID,
            $action,
            $module,
            $table,
            $affected_id,
            $old_value,
            $new_value,
            $ip,
            $timestamp
        );
        $stmt->execute();
    }




public static function fetchOldData(string $table, string $primaryKey, int $id, array $fields): array
{
    global $_database;

    $columns = implode(", ", $fields);
    $stmt = $_database->prepare("SELECT $columns FROM $table WHERE $primaryKey = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc() ?: [];
    $stmt->close();

    return $data;
}



}