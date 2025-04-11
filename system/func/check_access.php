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

// Stelle sicher, dass die Datenbankverbindung verfügbar ist
global $_database; 

function checkAccessRights($adminID, $catID = null, $linkID = null)
{
    global $_database;

    // Wenn eine Kategorie-ID übergeben wird, prüfe den Zugriff auf die Kategorie
    if ($catID) {
        $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "user_access_rights WHERE adminID = ? AND type = 'category' AND accessID = ?");
        $stmt->bind_param('ii', $adminID, $catID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Gibt true zurück, wenn Zugriffsrechte vorhanden sind
        return $result->num_rows > 0;
    }

    // Wenn eine Link-ID übergeben wird, prüfe den Zugriff auf den Link
    if ($linkID) {
        $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "user_access_rights WHERE adminID = ? AND type = 'link' AND accessID = ?");
        $stmt->bind_param('ii', $adminID, $linkID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Gibt true zurück, wenn Zugriffsrechte vorhanden sind
        return $result->num_rows > 0;
    }

    return false;
}



// Hilfsfunktion, um zu prüfen, ob der Benutzer Zugang basierend auf dem accesslevel hat
/*function hasAccess($userID, $accessLevel)
{
    global $_database;
    // Hier kannst du die Logik implementieren, um zu prüfen, ob der Benutzer den passenden Zugang hat
    // Beispiel: Ein Admin hat Zugriff auf alles, ein regulärer Benutzer nur auf bestimmte Bereiche
    $stmt = $_database->prepare("SELECT access FROM " . PREFIX . "user_access_rights WHERE userID = ? AND access = ?");
    $stmt->bind_param('is', $userID, $accessLevel);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}*/