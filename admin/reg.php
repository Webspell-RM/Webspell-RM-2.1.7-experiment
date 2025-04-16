<?php
// Session starten
session_start();

// Verbindung zur Datenbank einbinden
include_once '../system/sql.php'; // Achte darauf, dass der Pfad korrekt ist

chdir('../');
include('system/sql.php');
include('system/settings.php');
include('system/functions.php');
include('system/plugin.php');
include('system/widget.php');
include('system/version.php');
chdir('admin');

// Fehlerberichte aktivieren
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Überprüfen, ob die Datenbankverbindung erfolgreich hergestellt wurde
if (!isset($_database) || $_database === null) {
    die("Datenbankverbindung konnte nicht hergestellt werden.");
} else {
    echo "Datenbankverbindung erfolgreich.<br>";
}

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Benutzereingaben
    $email = $_POST['email'];
    $password = trim($_POST['password']); // Entfernen von Leerzeichen

    // Überprüfen, ob die E-Mail bereits existiert
    $query = $_database->prepare("SELECT * FROM " . PREFIX . "user1 WHERE email = ? LIMIT 1");

    if (!$query) {
        die('Fehler bei der SQL-Preparaion: ' . $_database->error); // SQL Fehler ausgeben
    }

    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo "Benutzer mit dieser E-Mail existiert bereits!<br>";
    } else {
        // Passwort hashen
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Benutzer in die Datenbank einfügen
        $query = $_database->prepare("INSERT INTO " . PREFIX . "user1 (email, password_hash, role) VALUES (?, ?, ?)");

        if (!$query) {
            die('Fehler bei der SQL-Preparaion für Insert: ' . $_database->error); // SQL Fehler ausgeben
        }

        $role = 'user'; // Standardrolle für neue Benutzer

        $query->bind_param("sss", $email, $hashed_password, $role);

        // Ausführen der SQL-Abfrage
        if ($query->execute()) {
            // Benutzer erfolgreich registriert
            echo "Benutzer erfolgreich registriert! Weiterleitung zu index.php...<br>";

            // Session-Daten setzen
            $_SESSION['email'] = $email;  // E-Mail in der Session speichern
            $_SESSION['role'] = $role;    // Rolle in der Session speichern
            $_SESSION['registered'] = true;  // Flag setzen

            // Weiterleitung
            header('Location: login.php');
            exit();  // Beenden des Skripts
        } else {
            // Fehler beim Einfügen, gibt den Fehler aus
            echo "Fehler beim Eintragen des Benutzers in die Datenbank: " . $_database->error . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5">Benutzerregistrierung</h2>

                <form method="POST" action="reg.php">
                    <div class="form-group">
                        <label for="email">E-Mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Passwort:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrieren</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
