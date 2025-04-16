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
            FROM `user_admin_access_rights` ar
            JOIN `user_role_assignments` ur ON ar.`roleID` = ur.`roleID`
            JOIN `user_roles` r ON ur.`roleID` = r.`roleID`
            WHERE ur.`adminID` = '" . (int)$userID . "'
            AND ar.`modulname` = '" . escape($modulname) . "'
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
                WHERE `modulname` = '" . escape($modulname) . "'
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
}

class multiLanguage
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
    private function getTextByTag($language, $text)
    {
        $output = '';
        $segments = explode('{[' . $language . ']}', $text);
        foreach ($segments as $segment) {
            $tmp = explode('{[', $segment);
            $output .= $tmp[0];
        }
        return $output;
    }
}

function escape($input) {
    return mysqli_real_escape_string($GLOBALS['_database'], $input);
}
