<?php
// Session starten, falls noch nicht gestartet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $_database;

// Sprache aus Session oder URL bestimmen (Beispiel)
if (isset($_SESSION['language'])) {
    $lang = $_SESSION['language'];
} else {
    // Sprache aus DB auslesen
    $result = $_database->query("SELECT default_language FROM settings LIMIT 1");
    if ($result && $row = $result->fetch_assoc() && !empty($row['default_language'])) {
        $lang = $row['default_language'];
    } else {
        $lang = 'en'; // Fallback
    }
    $_SESSION['language'] = $lang;
}

// Sprache via URL-Parameter ändern (Überschreiben + Session speichern)
if (isset($_GET['lang'])) {
    // Nur Kleinbuchstaben erlauben
    $lang = preg_replace('/[^a-z]/', '', strtolower($_GET['lang']));
    $_SESSION['language'] = $lang;
}

// Aktive Sprachen aus DB laden
$query = "SELECT iso_639_1, name_native, name_en, flag FROM settings_languages WHERE active = 1 ORDER BY name_en ASC";
$result = $_database->query($query);

if (!$result) {
    die("Fehler bei der Abfrage: " . $_database->error);
}

$lang_ok = '';
$language_links = '';
$flag_ok = '';

while ($row = $result->fetch_assoc()) {
    $short = $row['iso_639_1'];
    $flag = $row['flag'];

    // Falls kein Flag-Pfad hinterlegt, Default auf /admin/images/flags/ setzen
    if (empty($flag)) {
        $flag = "/admin/images/flags/{$short}.png";
    }

    // Sprachname bevorzugt Native, sonst Englisch, sonst ISO-Code
    $name = $row['name_native'] ?: ($row['name_en'] ?: ucfirst($short));

    // Aktuelle Query-Parameter holen und modifizieren
    $params = $_GET;
    $params['new_lang'] = $short;
    $queryString = http_build_query($params);

    $url = 'index.php?' . $queryString;

    if ($short === $lang) {
        $lang_ok = '<a class="dropdown-item active" href="' . htmlspecialchars($url) . '" title="' . htmlspecialchars($name) . '">'
            . '<span class="flag" style="background-image: url(\'' . htmlspecialchars($flag) . '\');"></span> '
            . htmlspecialchars($name) . ' <i class="bi bi-check2 text-success" style="font-size: 1rem;"></i></a>';
        $flag_ok = '<span class="flag" style="background-image: url(\'' . htmlspecialchars($flag) . '\');"></span>';
    } else {
        $language_links .= '<a class="dropdown-item" href="' . htmlspecialchars($url) . '" title="' . htmlspecialchars($name) . '">'
            . '<span class="flag" style="background-image: url(\'' . htmlspecialchars($flag) . '\');"></span> '
            . htmlspecialchars($name) . '</a>';
    }
}

// Daten für Template
$data_array = [
    'flag_ok' => $flag_ok,
    'languages_ok' => $lang_ok,
    'languages' => $language_links,
];

// Template laden (Semikolon nicht vergessen!)
echo $tpl->loadTemplate("navigation", "languages", $data_array, 'theme');
