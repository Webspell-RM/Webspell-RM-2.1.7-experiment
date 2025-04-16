<?php
<?php
// Datenbankverbindungsdetails
$host = "localhost";  // Hostname der Datenbank (in der Regel 'localhost')
$user = "";           // Benutzername für die Datenbankverbindung
$pwd  = "";           // Passwort für die Datenbankverbindung
$db   = "";           // Name der Datenbank, mit der verbunden werden soll

// Verbindungsaufbau zur MySQL-Datenbank
// Die Verbindung wird über den MySQLi-Treiber hergestellt.
$conn = new mysqli($host, $user, $pwd, $db);

// Überprüfen, ob die Verbindung erfolgreich war
if ($conn->connect_error) {
    // Falls die Verbindung fehlschlägt, eine Fehlermeldung ausgeben und die Verbindung abbrechen
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Setze den Zeichensatz auf UTF-8, um Zeichencodierungsprobleme zu vermeiden
$conn->set_charset("utf8");

// Weitere Datenbankoperationen können hier durchgeführt werden

?>

?>