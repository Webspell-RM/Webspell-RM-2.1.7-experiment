<?php

use webspell\AccessControl;

function checkAdminAccess($currentModule) {
    // Überprüfe, ob der Benutzer eingeloggt ist
    AccessControl::enforceLogin();

    // Überprüfe, ob der Benutzer ein Admin ist
    global $_database, $_SESSION, $_language;
    $stmt = $_database->prepare("SELECT is_admin FROM " . PREFIX . "user WHERE userID = ?");
    $stmt->bind_param("i", $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data || intval($data['is_admin']) !== 1) {
        echo $_language->module['access_denied'];
        header("Refresh: 3; url=/admin/admincenter.php");
        exit;
    }

    // Überprüfe, ob der Admin Zugriff auf das angeforderte Modul hat
    $stmt = $_database->prepare("SELECT modulname FROM " . PREFIX . "user_access_rights WHERE adminID = ?");
    $stmt->bind_param('i', $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $allowedModules = [];

    while ($row = $result->fetch_assoc()) {
        $allowedModules[] = $row['modulname'];
    }

    if (!in_array($currentModule, $allowedModules)) {
        echo $_language->module['access_denied'];
        header("Refresh: 3; url=/admin/admincenter.php");
        exit;
    }
}

// Funktion zum Prüfen, ob der Benutzer eine Rolle zugewiesen bekommen hat
// Überprüfen, ob der Benutzer eine Rolle zugewiesen bekommen hat
function checkUserRoleAssignment($userID) {
    global $_database; // Datenbankverbindung

    // Angenommene Spaltenbezeichnung für die Benutzer-ID: 'user_id'
    $stmt = $_database->prepare("SELECT roleID FROM " . PREFIX . "user_role_assignments WHERE AdminID = ?");
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Wenn der Benutzer eine Rolle hat, zurückgeben
    return $result->num_rows > 0;
}

// Funktion zur Überprüfung von Zugriffsrechten
function checkAccessRights($userID, $catID = null, $linkID = null) {
    // Wenn keine gültige Kategorie oder Link-ID übergeben wurde, geben wir false zurück
    if (!$catID && !$linkID) {
        return false;
    }

    // Query zur Überprüfung der Zugriffsrechte des Benutzers auf Kategorien und Links
    $query = "
        SELECT ar.type, ar.accessID
        FROM " . PREFIX . "user_admin_access_rights ar
        JOIN " . PREFIX . "user_role_assignments ur ON ar.roleID = ur.roleID
        WHERE ur.adminID = '$userID' 
        AND ((ar.type = 'category' AND ar.accessID = '$catID') OR (ar.type = 'link' AND ar.accessID = '$linkID'))
    ";

    // Abfrage ausführen
    $result = safe_query($query);
    
    // Prüfen, ob eine Übereinstimmung gefunden wurde (d.h., der Benutzer hat Zugriff)
    return mysqli_num_rows($result) > 0;
}

function assignRoleToUser($userID, $roleID) {
    // Füge die Rolle zur Tabelle user_roles hinzu
    $query = "INSERT INTO " . PREFIX . "user_roles (userID, roleID) VALUES ('$userID', '$roleID')";
    safe_query($query);

    // Hole die Berechtigungen für diese Rolle
    $rolePermissions = safe_query("SELECT * FROM " . PREFIX . "user_role_permissions WHERE roleID = '$roleID'");

    while ($permission = mysqli_fetch_array($rolePermissions)) {
        // Füge die Berechtigung in die user_admin_access_rights-Tabelle ein
        $adminID = $userID;  // Verwende die userID als adminID
        $type = $permission['type'];  // 'link' oder 'category'
        $accessID = $permission['accessID'];  // Die ID des Zugriffs (Link oder Kategorie)

        // Berechtigung hinzufügen
        $query = "INSERT INTO " . PREFIX . "user_admin_access_rights (adminID, roleID, type, accessID) 
                  VALUES ('$adminID', '$roleID', '$type', '$accessID')";
        safe_query($query);
    }
}

/*function has_role($userID, array $roleIDs) {
    $ids = implode(',', array_map('intval', $roleIDs));
    $result = safe_query("
        SELECT * FROM " . PREFIX . "user_role_assignments
        WHERE adminID = " . (int)$userID . " AND roleID IN ($ids)
    ");
    return mysqli_num_rows($result) > 0;
}*/

function hasRole($userID, $roleID) {
    global $_database;
    $stmt = $_database->prepare("SELECT 1 FROM " . PREFIX . "user_role_assignments WHERE adminID = ? AND roleID = ?");
    $stmt->bind_param("ii", $userID, $roleID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getAvailableRoles() {
    global $_database;
    $query = "SELECT roleID FROM " . PREFIX . "user_roles"; // Annahme: rm_216_user_roles ist die Tabelle mit den Rollen
    $result = $_database->query($query);
    $roles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $roles[] = $row['roleID'];
    }
    return $roles;
}

function hasAnyRole($userID, array $allowedRoles): bool {
    // Holt alle verfügbaren Rollen
    $availableRoles = getAvailableRoles();

    // Prüft, ob eine der erlaubten Rollen im System existiert
    $userRoles = getUserRoles($userID);
    foreach ($userRoles as $roleID) {
        if (in_array($roleID, $allowedRoles) && in_array($roleID, $availableRoles)) {
            return true;
        }
    }
    return false;
}

function getUserRoles($userID) {
    global $_database;
    $query = "SELECT roleID FROM " . PREFIX . "user_role_assignments WHERE adminID = ?";
    $stmt = $_database->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();  // Hier wird das Resultat abgerufen
    $roles = [];
    while ($row = $result->fetch_assoc()) {  // Dann fetch_assoc() verwenden
        $roles[] = $row['roleID'];
    }
    return $roles;
}


// Diese Funktion sorgt dafür, dass ein Benutzer gebannt wird
/*function ban_user($userID) {
    // SQL-Abfrage zum Bann eines Benutzers
    $userID = intval($userID);  // Sicherheitsmaßnahme, um sicherzustellen, dass es eine gültige Zahl ist
    $query = "UPDATE " . PREFIX . "user SET banned = 1 WHERE userID = $userID";
    return safe_query($query);
}*/

function ban_user($userID, $reason = '', $duration = null) {
    // Absicherung der Eingabe
    $userID = intval($userID);  // Um sicherzustellen, dass es sich um eine gültige Zahl handelt
    
    // Berechnung des Enddatums für den Bann, falls eine Dauer angegeben wurde
    $ban_until = null;
    if ($duration) {
        $ban_until = date('Y-m-d H:i:s', strtotime("+$duration"));
    }

    // SQL-Abfrage zum Bannen des Benutzers
    $query = "UPDATE `" . PREFIX . "user` SET `banned` = 1, `ban_reason` = '$reason', `ban_until` = '$ban_until' WHERE `userID` = $userID";
    
    // Stelle sicher, dass die Abfrage ausgeführt wird
    if (safe_query($query)) {
        return true;
    } else {
        return false;
    }
}