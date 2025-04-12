<?php

namespace webspell;

class AccessControl {

    

    public static function checkAdminAccess($modulname) {
        global $userID;

        // Überprüfen, ob der Benutzer angemeldet ist
        if (!$userID) {
            // Benutzer ist nicht angemeldet, Zugriff verweigern
            header('Location: login.php'); // Umleitung zur Login-Seite
            exit;
        }

        // Prüfen, ob der Benutzer Zugriff auf das Modul hat und den Namen der Rolle holen
        $query = "
            SELECT r.role_name AS role_name, ar.modulname, COUNT(*) AS access_count
            FROM " . PREFIX . "user_admin_access_rights ar
            JOIN " . PREFIX . "user_role_assignments ur ON ar.roleID = ur.roleID
            JOIN " . PREFIX . "user_roles r ON ur.roleID = r.roleID
            WHERE ur.adminID = '$userID'
            AND ar.modulname = '$modulname'
            GROUP BY r.role_name, ar.modulname
        ";

        $result = safe_query($query);
        $row = mysqli_fetch_assoc($result);

        // Wenn keine Zeilen zurückgegeben werden oder keine Berechtigung für das Modul, Zugriff verweigern
        if ($row === null || $row['access_count'] == 0) {
            // Modulname ausgeben, falls vorhanden
            $modulnameDisplay = $row ? htmlspecialchars($row['modulname']) : 'Unbekanntes Modul';
            $errorMessage = "<b>Zugriff verweigert:</b> Keine Berechtigung für das Modul '$modulname'.<br>";

            // Protokolliere den Wert von $modulname, um den Fehler zu diagnostizieren
            error_log("Fehler in AccessControl: Modul '$modulnameDisplay' nicht gefunden für BenutzerID $userID");

            // Holen des Linknamens aus der Tabelle `navigation_dashboard_links`, falls linkID vorhanden
            $linkQuery = "
                SELECT name
                FROM " . PREFIX . "navigation_dashboard_links
                WHERE modulname = '$modulname'
            ";
            $linkResult = safe_query($linkQuery);
            $linkRow = mysqli_fetch_assoc($linkResult);
            $linkName = $linkRow ? htmlspecialchars($linkRow['name']) : 'Unbekannter Link';

            $translate = new multiLanguage(detectCurrentLanguage());
            $translate->detectLanguages($linkName);
            $name = $translate->getTextByLanguage($linkName);

            // Ausgabe des Linknamens
            $errorMessage .= "<b>Linkname:</b> $name<br>";

            // Optional: Rolle ausgeben, wenn vorhanden
            if (isset($row['role_name'])) {
                $errorMessage .= "<b>Ihre Rolle:</b> " . htmlspecialchars($row['role_name']);
            }

            // Fehlermeldung in Bootstrap Alert-Box ausgeben
            echo "<div class='alert alert-danger' role='alert'>$errorMessage</div>";

            exit;
        }
    }
}



class multiLanguage {

    public $language;
    public $availableLanguages = array();

    // Konstruktor
    public function __construct($lang) {
        $this->language = $lang;
    }

    // Ermittelt alle verfügbaren Sprachen im Text
    public function detectLanguages($text) {
        // Trennen des Textes nach den Sprach-Tags
        $sox = explode('{[', $text);
        
        // Iteriere durch alle Teile und prüfe, ob es ein neues Sprach-Tag gibt
        foreach ($sox as $part) {
            $eox = explode(']}', $part);
            if (isset($eox[0]) && !in_array($eox[0], $this->availableLanguages) && !empty($eox[0])) {
                $this->availableLanguages[] = $eox[0];
            }
        }
    }

    // Gibt den Text für die ausgewählte Sprache zurück
    public function getTextByLanguage($text) {
        // Prüfen, ob die angeforderte Sprache verfügbar ist
        if (in_array($this->language, $this->availableLanguages)) {
            return $this->getTextByTag($this->language, $text);
        } elseif (!empty($this->availableLanguages)) {
            // Falls die ausgewählte Sprache nicht vorhanden ist, nutze eine andere verfügbare Sprache
            return $this->getTextByTag($this->availableLanguages[0], $text);
        } else {
            // Gibt den Originaltext zurück, wenn keine Sprachen gefunden wurden
            return $text;
        }
    }

    // Hilfsmethode, um den Text für ein bestimmtes Sprach-Tag zu extrahieren
    private function getTextByTag($language, $text) {
        // Extrahiere den Text basierend auf der angegebenen Sprache
        $output = '';
        $fix = explode('{[' . $language . ']}', $text);

        foreach ($fix as $part) {
            $tmp = explode('{[', $part);
            $output .= $tmp[0];  // Füge den Text ohne Sprach-Tag hinzu
        }

        return $output;
    }
}

