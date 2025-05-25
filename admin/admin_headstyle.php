<?php

// Überprüfen, ob die Session bereits gestartet wurde
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_headstyle');


if (isset($_POST['save_style'])) {
    $style = htmlspecialchars($_POST['selected_style'], ENT_QUOTES);
    safe_query("UPDATE settings_headstyle_config SET selected_style='$style' WHERE id=1");
    echo '<div class="alert alert-success">Stil gespeichert!</div>';
}

$current = mysqli_fetch_array(safe_query("SELECT selected_style FROM settings_headstyle_config WHERE id=1"));
$selected = $current['selected_style'];

echo '<form method="post" class="form-group">';
echo '<label>Überschriften-Stil wählen:</label>';
echo '<select name="selected_style" class="form-select">';
for ($i = 1; $i <= 10; $i++) {
    $style = "head-boxes-$i";
    $sel = ($style == $selected) ? 'selected' : '';
    echo "<option value=\"$style\" $sel>Stil $i</option>";
}
echo '</select>';
echo '<button type="submit" name="save_style" class="btn btn-primary mt-2">Speichern</button>';
echo '</form>';
?>
