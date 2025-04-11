<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

function getuserid($nickname)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherheit: Benutzereingabe escapen, um SQL-Injektionen zu verhindern
    $nickname = mysqli_real_escape_string($_database, $nickname);  // $_database verwenden

    // Versuche, die Abfrage auszuführen
    $get = safe_query("SELECT userID FROM " . PREFIX . "user WHERE `nickname` = '$nickname'");

    // Überprüfe, ob ein Ergebnis vorhanden ist
    if ($get && mysqli_num_rows($get) > 0) {
        $ds = mysqli_fetch_assoc($get); // Verwende mysqli_fetch_assoc für ein assoziiertes Array
        return $ds['userID'];
    } else {
        return ''; // Wenn kein Benutzer gefunden wurde, gib einen leeren Wert zurück
    }
}


function getnickname($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherheitsverbesserung: Casten von userID als Integer
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // Erste Abfrage: Versuche, den Nicknamen aus der "user" Tabelle zu bekommen
        $erg = safe_query("SELECT nickname FROM " . PREFIX . "user WHERE `userID` = $userID");

        // Wenn genau ein Ergebnis gefunden wird
        if (mysqli_num_rows($erg) == 1) {
            $ds = mysqli_fetch_assoc($erg); // Verwende mysqli_fetch_assoc
            return $ds['nickname'];
        } else {
            // Falls kein Nickname in der "user"-Tabelle gefunden wird, versuche es in der "user_nickname"-Tabelle
            $ds = mysqli_fetch_assoc(safe_query("SELECT nickname FROM " . PREFIX . "user_nickname WHERE `userID` = $userID"));
            
            // Rückgabe des Nicknames mit durchgestrichener Darstellung, falls vorhanden
            return isset($ds['nickname']) ? '<s>' . $ds['nickname'] . '</s>' : '';
        }
    } catch (Exception $e) {
        // Fehlerbehandlung: Falls etwas schiefgeht, kann eine Fehlermeldung ausgegeben werden
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}

function deleteduser($userID) 
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherheitsverbesserung: sicherstellen, dass die userID eine Ganzzahl ist
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // Verbindungsprüfung
        global $_database; // Stellen Sie sicher, dass die Verbindung zur Datenbank korrekt initialisiert ist

        $query = "SELECT nickname FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);
        
        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        mysqli_stmt_bind_param($stmt, "i", $userID); // "i" steht für Integer
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Prüfen, ob genau ein Ergebnis zurückgegeben wird
        if (mysqli_stmt_num_rows($stmt) == 1) {
            return '0'; // Benutzer existiert
        } else {
            return '1'; // Benutzer existiert nicht
        }
    } catch (Exception $e) {
        // Fehlerbehandlung: Geben Sie eine Fehlermeldung zurück, falls etwas schiefgeht
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}

function getuserdescription($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherheitsverbesserung: sicherstellen, dass die userID eine Ganzzahl ist
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit vorbereiteten Anfragen, um SQL-Injektionen zu vermeiden
        $query = "SELECT userdescription FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden der Parameter
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Holen der Daten
            mysqli_stmt_bind_result($stmt, $userdescription);
            mysqli_stmt_fetch($stmt);

            // Rückgabe der Beschreibung mit getinput
            return getinput($userdescription);
        } else {
            // Wenn kein Benutzer gefunden wurde
            return ''; // Leerer Wert, wenn kein Benutzer gefunden wurde
        }

    } catch (Exception $e) {
        // Fehlerbehandlung: Geben Sie eine Fehlermeldung zurück, falls etwas schiefgeht
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getfirstname($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage
        $query = "SELECT firstname FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($mysqli, $query); // Verwendung der globalen $mysqli-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Parameter binden (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Bind result and fetch the firstname
            mysqli_stmt_bind_result($stmt, $firstname);
            mysqli_stmt_fetch($stmt);

            // Rückgabe der ersten Name mit getinput()
            return getinput($firstname);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getlastname($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT lastname FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen des Nachnamens
            mysqli_stmt_bind_result($stmt, $lastname);
            mysqli_stmt_fetch($stmt);

            // Rückgabe des Nachnamens mit getinput()
            return getinput($lastname);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getbirthday($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT birthday FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen des Geburtstags
            mysqli_stmt_bind_result($stmt, $birthday);
            mysqli_stmt_fetch($stmt);

            // Rückgabe des formatierten Geburtstags mit getformatdate()
            return getformatdate($birthday);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls kein Geburtstag vorhanden
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function gettown($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT town FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen der Stadt
            mysqli_stmt_bind_result($stmt, $town);
            mysqli_stmt_fetch($stmt);

            // Rückgabe der Stadt (nach getinput() Verarbeitung)
            return getinput($town);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls keine Stadt vorhanden
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getemail($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT email FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen der E-Mail
            mysqli_stmt_bind_result($stmt, $email);
            mysqli_stmt_fetch($stmt);

            // Rückgabe der E-Mail (nach getinput() Verarbeitung)
            return getinput($email);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls keine E-Mail vorhanden
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getemailhide($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT email_hide FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen der E-Mail
            mysqli_stmt_bind_result($stmt, $email_hide);
            mysqli_stmt_fetch($stmt);

            // Rückgabe des "email_hide"-Werts (nach getinput() Verarbeitung)
            return getinput($email_hide);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls kein "email_hide" gefunden
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function gethomepage($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT homepage FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen der Homepage
            mysqli_stmt_bind_result($stmt, $homepage);
            mysqli_stmt_fetch($stmt);

            // Rückgabe der Homepage ohne 'https://', falls vorhanden
            return str_replace('https://', '', getinput($homepage));
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls keine Homepage gefunden
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}


function getdiscord($userID)
{
    global $_database;  // Zugriff auf die globale Datenbankverbindung

    // Sicherstellen, dass userID als Ganzzahl behandelt wird
    $userID = (int)$userID;

    // Fehlerbehandlung und Abfrageausführung
    try {
        // SQL-Abfrage mit sicherer Parameterbindung
        $query = "SELECT discord FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query); // Verwenden der globalen $_database-Verbindung

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters (userID)
        mysqli_stmt_bind_param($stmt, "i", $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob ein Ergebnis vorhanden ist
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Binden des Ergebnisses und Abrufen des Discord-Benutzernamens
            mysqli_stmt_bind_result($stmt, $discord);
            mysqli_stmt_fetch($stmt);

            // Rückgabe des Discord-Benutzernamens
            return getinput($discord);
        } else {
            // Kein Ergebnis gefunden
            return ''; // Leerer Wert, falls kein Discord-Name gefunden wurde
        }

    } catch (Exception $e) {
        // Fehlerbehandlung
        return '<span class="label label-danger">Fehler: ' . $e->getMessage() . '</span>';
    }
}

/*
function getcountries($selected = null)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    // Erstellen einer leeren Variablen für die Ausgabe
    $countries = '';

    try {
        // Kombinierte Abfrage für bevorzugte und nicht bevorzugte Länder
        $query = "SELECT * FROM " . PREFIX . "settings_countries ORDER BY `fav` DESC, `country`";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $id, $country, $short, $fav);

        // Schleifen durch die Abfrageergebnisse
        while (mysqli_stmt_fetch($stmt)) {
            if ($short == $selected) {
                $countries .= '<option value="' . htmlspecialchars($short) . '" selected="selected">' . htmlspecialchars($country) . '</option>';
            } else {
                $countries .= '<option value="' . htmlspecialchars($short) . '">' . htmlspecialchars($country) . '</option>';
            }
        }

        // Überprüfen, ob Länder gefunden wurden
        if (mysqli_stmt_num_rows($stmt) > 0) {
            return $countries;
        } else {
            return '<option value="">Keine Länder gefunden</option>';
        }

    } catch (Exception $e) {
        // Fehlerbehandlung und Rückgabe der Fehlernachricht
        return '<option value="">Fehler: ' . $e->getMessage() . '</option>';
    }
}


function getcountry($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage, um SQL-Injektionen zu verhindern
        $query = "SELECT country FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return ''; // Benutzer nicht gefunden
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $country);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Rückgabe des Landes, sicherer Umgang mit der Ausgabe
        return htmlspecialchars($country);

    } catch (Exception $e) {
        // Fehlerbehandlung
        return 'Fehler: ' . $e->getMessage();
    }
}
*/

function getuserlanguage($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage, um SQL-Injektionen zu verhindern
        $query = "SELECT language FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return ''; // Benutzer nicht gefunden, Rückgabe eines leeren Werts
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $language);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Rückgabe des Werts, sicherer Umgang mit der Ausgabe
        return htmlspecialchars($language);

    } catch (Exception $e) {
        // Fehlerbehandlung
        return 'Fehler: ' . $e->getMessage();
    }
}


function getuserpic($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage, um SQL-Injektionen zu verhindern
        $query = "SELECT userpic, nickname FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return 'default-avatar.png'; // Wenn der Benutzer nicht gefunden wird, ein Standard-Bild zurückgeben
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $userpic, $nickname);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Überprüfen, ob ein Benutzerbild vorhanden ist
        if (empty($userpic)) {
            // Rückgabe eines standardisierten Bildes, wenn kein Bild vorhanden ist
            return "svg-avatar.php?name=" . urlencode($nickname) . "G";
        }

        return $userpic;

    } catch (Exception $e) {
        // Fehlerbehandlung
        return 'default-avatar.png'; // Fehlerfälle oder Probleme mit der Abfrage führen zu einem Standardbild
    }
}


function getavatar($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage, um SQL-Injektionen zu verhindern
        $query = "SELECT avatar, nickname FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return 'default-avatar.png'; // Wenn der Benutzer nicht gefunden wird, ein Standard-Bild zurückgeben
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $avatar, $nickname);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Überprüfen, ob ein Avatar vorhanden ist
        if (empty($avatar)) {
            // Rückgabe eines standardisierten Bildes, wenn kein Avatar vorhanden ist
            return "svg-avatar.php?name=" . urlencode($nickname) . "G";
        }

        return $avatar;

    } catch (Exception $e) {
        // Fehlerbehandlung
        return 'default-avatar.png'; // Fehlerfälle oder Probleme mit der Abfrage führen zu einem Standardbild
    }
}


function getsignatur($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage, um SQL-Injektionen zu verhindern
        $query = "SELECT usertext FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return ''; // Wenn der Benutzer nicht gefunden wird, eine leere Signatur zurückgeben
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $usertext);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Bereinigen der Signatur (optional)
        return strip_tags($usertext);

    } catch (Exception $e) {
        // Fehlerbehandlung
        return ''; // Fehlerfälle oder Probleme mit der Abfrage führen zu einer leeren Signatur
    }
}


function getregistered($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage zur Vermeidung von SQL-Injektionen
        $query = "SELECT registerdate FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return ''; // Benutzer nicht gefunden, leere Antwort zurückgeben
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $registerdate);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Formatieren des Datums
        return getformatdate($registerdate);

    } catch (Exception $e) {
        // Fehlerbehandlung
        return ''; // Fehler führen zu einer leeren Rückgabe
    }
}


function getlastlogin($userID)
{
    global $_database; // Sicherstellen, dass die globale DB-Variable verwendet wird.

    try {
        // Vorbereitete Abfrage zur Vermeidung von SQL-Injektionen
        $query = "SELECT lastlogin FROM " . PREFIX . "user WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer gefunden wurde
        if (mysqli_stmt_num_rows($stmt) == 0) {
            return ''; // Benutzer nicht gefunden, leere Antwort zurückgeben
        }

        // Binden der Ergebnisse
        mysqli_stmt_bind_result($stmt, $lastlogin);

        // Abrufen des Werts
        mysqli_stmt_fetch($stmt);

        // Überprüfen, ob der Benutzer sich noch nie angemeldet hat (Unix-Zeitstempel 0)
        if ($lastlogin == '0000-00-00 00:00:00' || strtotime($lastlogin) == 0) {
            return "User war noch nicht angemeldet";
        }

        // Formatieren des Datums
        return getformatdate($lastlogin);

    } catch (Exception $e) {
        // Fehlerbehandlung
        return ''; // Fehler führen zu einer leeren Rückgabe
    }
}



function usergroupexists($userID)
{
    global $_database; // Sicherstellen, dass $_database verwendet wird.

    try {
        // Vorbereitete Abfrage zur Vermeidung von SQL-Injektionen
        $query = "SELECT userID FROM " . PREFIX . "user_groups WHERE `userID` = ?";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Anfrage.');
        }

        // Binden des Parameters
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Ausführen der Abfrage
        mysqli_stmt_execute($stmt);

        // Ergebnis speichern
        mysqli_stmt_store_result($stmt);

        // Überprüfen, ob der Benutzer einer Gruppe zugeordnet ist
        return mysqli_stmt_num_rows($stmt) > 0;

    } catch (Exception $e) {
        // Fehlerbehandlung
        return false; // Rückgabe von false, wenn ein Fehler auftritt
    }
}


function wantmail($userID)
{
    global $_database; // Sicherstellen, dass die globale Datenbankverbindung verwendet wird

    try {
        // Vorbereitete Abfrage zur Vermeidung von SQL-Injektionen
        $query = "SELECT EXISTS(SELECT 1 FROM " . PREFIX . "user WHERE `userID` = ? AND `mailonpm` = 1)";
        $stmt = mysqli_prepare($_database, $query);

        if ($stmt === false) {
            throw new Exception('Fehler bei der Vorbereitung der Datenbankabfrage.');
        }

        // Parameter binden (i = integer)
        mysqli_stmt_bind_param($stmt, 'i', $userID);

        // Abfrage ausführen
        mysqli_stmt_execute($stmt);

        // Ergebnis binden und abrufen
        mysqli_stmt_bind_result($stmt, $exists);
        mysqli_stmt_fetch($stmt);

        // Rückgabe des Ergebnisses als Boolean
        return (bool)$exists;
    } catch (Exception $e) {
        return false; // Fehlerfall absichern
    }
}

/*
function getuserguestbookstatus($userID)
{
    $ds = mysqli_fetch_array(
        safe_query(
            "SELECT user_guestbook FROM " . PREFIX . "user WHERE `userID` = " . (int)$userID
        )
    );
    return getinput($ds['user_guestbook']);
}

function getusercomments($userID, $type)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT
                commentID
            FROM
                `" . PREFIX . "comments`
            WHERE
                `userID` = " . (int)$userID . " AND
                `type` = '" . $type . "'"
        )
    );
}

function getallusercomments($userID)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT commentID FROM `" . PREFIX . "comments` WHERE `userID` = " . (int)$userID
        )
    );
}

function isbuddy($userID, $buddy)
{
    return (
        mysqli_num_rows(
            safe_query(
                "SELECT
                    *
                FROM
                    " . PREFIX . "buddys
                WHERE
                    `banned` = 0 AND
                    `buddy` = " . (int)$buddy . " AND
                    `userID` = " . (int)$userID
            )
        ) > 0
    );
}
*/
/*function RandPass($length, $type = 0)
{

    /* Randpass: Generates an random password
    Parameter:
    length - length of the password string
    type - there are 4 types: 0 - all chars, 1 - numeric only, 2 - upper chars only, 3 - lower chars only
    Example:
    echo RandPass(7, 1); => 0917432
    */
/*    $pass = '';
    for ($i = 0; $i < $length; $i++) {
        if ($type == 0) {
            $rand = rand(1, 3);
        } else {
            $rand = $type;
        }
        switch ($rand) {
            case 1:
                $pass .= chr(rand(48, 57));
                break;
            case 2:
                $pass .= chr(rand(65, 90));
                break;
            case 3:
                $pass .= chr(rand(97, 122));
                break;
        }
    }
    return $pass;
}*/

function RandPass($length, $type = 0)
{
    if ($length <= 0) {
        throw new InvalidArgumentException('Passwortlänge muss größer als 0 sein.');
    }

    // Zeichensätze definieren
    $numbers    = '0123456789';
    $uppercase  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase  = 'abcdefghijklmnopqrstuvwxyz';
    
    // Welche Zeichen dürfen verwendet werden?
    switch ($type) {
        case 1: $chars = $numbers; break;
        case 2: $chars = $uppercase; break;
        case 3: $chars = $lowercase; break;
        default: $chars = $numbers . $uppercase . $lowercase; break;
    }

    $pass = '';
    $maxIndex = strlen($chars) - 1;

    // Passwort generieren
    for ($i = 0; $i < $length; $i++) {
        $pass .= $chars[random_int(0, $maxIndex)];
    }

    return $pass;
}

function isonline($userID)
{
    $q = safe_query("SELECT site FROM " . PREFIX . "whoisonline WHERE userID = " . (int)$userID);

    if ($q && mysqli_num_rows($q) > 0) {
        $ds = mysqli_fetch_array($q);
        if (!empty($ds['site'])) {
            return '<strong>online</strong> @ <a href="index.php?site=' . htmlspecialchars($ds['site'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($ds['site'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        return '<strong>online</strong>';
    }

    return 'offline';
}
##################################################################

function getLanguageWeight($language)
{
    // Wenn die Eingabe leer oder null ist, Rückgabewert 1
    if (empty($language)) {
        return 1;
    }

    // Hier könnte man mehr Logik einbauen, falls $language als Zahl oder spezifisches Gewicht interpretiert werden soll
    return $language;
}

function detectUserLanguage()
{
    // Prüfen, ob der Header 'HTTP_ACCEPT_LANGUAGE' existiert
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // Regulärer Ausdruck zur Extraktion der Sprachcodes und Gewichtungen
        preg_match_all(
            "/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i",
            $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            $matches
        );

        if (count($matches)) {
            // Sprachcodes und deren Gewichtungen zu einem assoziativen Array kombinieren
            $languages_found = array_combine($matches[1], array_map("getLanguageWeight", $matches[4]));
            
            // Das Array nach Gewichtungen absteigend sortieren
            arsort($languages_found, SORT_NUMERIC);
            
            $path = $GLOBALS['_language']->getRootPath();
            
            // Überprüfen, ob das Verzeichnis für die bevorzugte Sprache existiert
            foreach ($languages_found as $key => $val) {
                if (is_dir($path . $key)) {
                    return $key;  // Rückgabe des Sprachcodes
                }
            }
        }
    }

    // Rückgabe eines Standardwerts, falls keine gültige Sprache gefunden wurde
    return 'en';  // Hier als Beispiel Englisch als Standard
}
###################### prüfen ###############################
function generatePasswordHash($password)
{
    $md5 = hash("md5", $password);
    return hash("sha512", substr($md5, 0, 14) . $md5);
}

//@info		refreshed by Team NOR
//@autor	Getschonnik
function gen_token() { 
	$tk = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
	return $tk;
}
function verify_token($post, $sess) {
	if($post==$sess) { return 1; } else { return 0; }
}
function destroy_token() {
	$_SESSION['token'] =""; unset($_SESSION['token']);
}

function is_PasswordPepper($userID) {
	$q=safe_query("SELECT `password_pepper` FROM `".PREFIX."user` WHERE `userID` = '".intval($userID)."'");
	$r=mysqli_fetch_array($q);
	if(mysqli_num_rows($q) && !empty($r['password_pepper'])) {
		return true;
	} else {
		return false; 
	}
}
function Gen_PasswordPepper() {
    #$chars = '0123456789abcdefghijklmnopqrstuvwxyz!§%()=?#*+ABCDEFGHIJKLMNOPQRSTUVWXYZ'; #Fehler beim zurücksetzten vom Passwort
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charlen = strlen($chars);
    $pep = '';
    for ($i = 0; $i < 10; $i++) {
        $pep .= $chars[rand(0, $charlen - 1)];
    }
    return $pep;
}
function Set_PasswordPepper($pep, $userID) {
	$pepper_hash = Gen_Hash($pep,"");
	safe_query("UPDATE `".PREFIX."user` SET `password_pepper` = '".$pepper_hash."' WHERE `userID` = '".intval($userID)."'");
}
function Get_PasswordPepper($userID) {
	$q=safe_query("SELECT `password_pepper` FROM `".PREFIX."user` WHERE `userID` = '".intval($userID)."' LIMIT 1");
	$r=mysqli_fetch_array($q);
	if(mysqli_num_rows($q) && !empty($r['password_pepper'])) {
		return $r['password_pepper'];
	} else {
		return false; 
	}
}
function destroy_PasswordPepper($userID) {
		safe_query("UPDATE `".PREFIX."user` SET `password_pepper` = '' WHERE `userID` = '".$userID."';");
}
function Gen_Hash($string, $pepper) {
	return password_hash($string.$pepper,PASSWORD_DEFAULT,array('cost'=>12));
}
function Gen_PasswordHash($password, $userID) {
	if(is_PasswordPepper($userID)) {	
		$pepper = Get_PasswordPepper($userID);
		$hash = password_hash($password.$pepper,PASSWORD_BCRYPT,array('cost'=>12));
	} else {
		$pep = Gen_PasswordPepper();
		Set_PasswordPepper($pep, $userID);
		$pepper = Get_PasswordPepper($userID);
		$hash = password_hash($password.$pepper,PASSWORD_BCRYPT,array('cost'=>12));
	}
	return $hash;
}
function verify_PasswordHash($post, $pepper, $dbpass) {
	return password_verify($post.$pepper,$dbpass);
}