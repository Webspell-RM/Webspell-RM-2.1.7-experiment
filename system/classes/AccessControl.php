<?php

namespace webspell;

class AccessControl
{
    public static function checkAdminAccess($modulname)
    {
        global $userID;

        // Überprüfen, ob der Benutzer angemeldet ist
        if (!$userID) {
            header('Location: login.php'); // Umleitung zur Login-Seite
            exit;
        }

        // Prüfen, ob der Benutzer Zugriff auf das Modul hat und den Rollennamen holen
        $query = "
            SELECT r.`role_name`, ar.`modulname`, COUNT(*) AS access_count
            FROM `user_role_admin_navi_rights` ar
            JOIN `user_role_assignments` ur ON ar.`roleID` = ur.`roleID`
            JOIN `user_roles` r ON ur.`roleID` = r.`roleID`
            WHERE ur.`userID` = '" . (int)$userID . "'
            AND ar.`modulname` = '" . $modulname . "'
            GROUP BY r.`role_name`, ar.`modulname`
        ";

        $result = safe_query($query);
        $row = mysqli_fetch_assoc($result);

        // Wenn keine Zeilen zurückgegeben werden oder keine Berechtigung für das Modul besteht
        if ($row === null || $row['access_count'] == 0) {
            $modulnameDisplay = $row ? htmlspecialchars($row['modulname']) : 'Unbekanntes Modul';
            $errorMessage = "<b>Zugriff verweigert:</b> Keine Berechtigung für das Modul '<i>$modulnameDisplay</i>'.<br>";

            // Protokolliere zur Fehlerdiagnose
            error_log("AccessControl Fehler: Modul '$modulnameDisplay' nicht erlaubt für userID $userID");

            // Versuche, den Linknamen aus der Navigations-Tabelle zu laden
            $linkQuery = "
                SELECT `name`
                FROM `navigation_dashboard_links`
                WHERE `modulname` = '" . $modulname . "'
            ";
            $linkResult = safe_query($linkQuery);
            $linkRow = mysqli_fetch_assoc($linkResult);
            $linkName = $linkRow ? htmlspecialchars($linkRow['name']) : 'Unbekannter Link';

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($linkName);
            $translatedName = $translate->getTextByLanguage($linkName);

            $errorMessage .= "<b>Linkname:</b> $translatedName<br>";

            if (!empty($row['role_name'])) {
                $errorMessage .= "<b>Ihre Rolle:</b> " . htmlspecialchars($row['role_name']);
            }

            echo "<div class='alert alert-danger' role='alert'>$errorMessage</div>";
            exit;
        }
    }


    /**
     * Erzwingt eine oder mehrere erlaubte Rollen.
     *# @param array|string $allowedRoles z.B. ['member', 'admin']
     */
 /*   public static function enforce($allowedRoles = ['member'])
    {
        if (!isset($_SESSION['userID'])) {
            die('<div style="color: red; font-weight: bold; font-family: sans-serif;"><br>⚠️ Zugriff verweigert – bitte zuerst einloggen.</div>');
        }

        $userID = (int)$_SESSION['userID'];
        global $_database;

        $stmt = $_database->prepare("
            SELECT r.role_name
            FROM user_role_assignments a
            JOIN user_roles r ON a.roleID = r.roleID
            WHERE a.userID = ?
            LIMIT 1
        ");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $res = $stmt->get_result();
        $userRole = $res->fetch_assoc()['role_name'] ?? '';

        $allowed = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];

        if (!in_array($userRole, $allowed)) {
            die("Zugriff verweigert – dieser Bereich ist nur für: " . implode(", ", $allowed));
        }
    }

    public static function hasRole(string $roleName): bool
    {
        if (!isset($_SESSION['userID'])) return false;

        $userID = (int)$_SESSION['userID'];
        global $_database;

        $stmt = $_database->prepare("
            SELECT r.role_name
            FROM user_role_assignments a
            JOIN user_roles r ON a.roleID = r.roleID
            WHERE a.userID = ? AND r.role_name = ?
            LIMIT 1
        ");
        $stmt->bind_param('is', $userID, $roleName);
        $stmt->execute();
        $res = $stmt->get_result();

        return $res->num_rows > 0;
    }

    public static function hasAnyRole(array $roleNames): bool
    {
        foreach ($roleNames as $role) {
            if (self::hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    public static function isClanMember(): bool
    {
        return self::hasRole('Clanmitglied');
    }
*/

    public static function hasAnyRole(array $roleNames): bool
{
    if (!isset($_SESSION['userID']) || empty($roleNames)) return false;

    $userID = (int)$_SESSION['userID'];
    global $_database;

    // Erzeuge Platzhalter für Prepared Statement (?, ?, ...)
    $placeholders = implode(',', array_fill(0, count($roleNames), '?'));

    // Datentypen: i für userID, danach s für jeden Rollennamen
    $types = 'i' . str_repeat('s', count($roleNames));

    // Alle Parameter zusammenfügen
    $params = array_merge([$types, $userID], $roleNames);

    // Dynamisch vorbereiten
    $stmt = $_database->prepare("
        SELECT 1
        FROM user_role_assignments a
        JOIN user_roles r ON a.roleID = r.roleID
        WHERE a.userID = ? AND r.role_name IN ($placeholders)
        LIMIT 1
    ");

    if ($stmt === false) {
        error_log("Fehler beim Prepare in hasAnyRole(): " . $_database->error);
        return false;
    }

    // Dynamisches Binden
    $stmt->bind_param(...self::refValues($params));
    $stmt->execute();
    $res = $stmt->get_result();

    return $res->num_rows > 0;
}

private static function refValues(array $arr)
{
    // Ab PHP 8 sind keine Referenzen mehr nötig
    if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
        return $arr;
    }

    // Für PHP < 8: Referenzen für bind_param
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}



    
}

/*class multiLanguage
{
    public $language;
    public $availableLanguages = [];

    public function __construct($lang)
    {
        $this->language = $lang;
    }

    // Extrahiert alle verfügbaren Sprachen aus dem Text
    public function detectLanguages($text)
    {
        $parts = explode('{[', $text);
        foreach ($parts as $part) {
            $langPart = explode(']}', $part);
            if (isset($langPart[0]) && !in_array($langPart[0], $this->availableLanguages) && !empty($langPart[0])) {
                $this->availableLanguages[] = $langPart[0];
            }
        }
    }

    // Gibt den passenden Text zur eingestellten Sprache zurück
    public function getTextByLanguage($text)
    {
        if (in_array($this->language, $this->availableLanguages)) {
            return $this->getTextByTag($this->language, $text);
        } elseif (!empty($this->availableLanguages)) {
            return $this->getTextByTag($this->availableLanguages[0], $text);
        } else {
            return $text;
        }
    }

    // Extrahiert den Inhalt eines bestimmten Sprach-Tags
   /* private function getTextByTag($language, $text)
    {
        $output = '';
        $segments = explode('{[' . $language . ']}', $text);
        foreach ($segments as $segment) {
            $tmp = explode('{[', $segment);
            $output .= $tmp[0];
        }
        return $output;
    }*/
/*}*/



