<?php

// Überprüfen, ob die Session bereits gestartet wurde
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sprachmodul laden
$_language->readModule('static', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_static');

if (isset($_POST['save'])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST['captcha_hash'])) {

        // Eingabedaten sicherstellen
        $title = mysqli_real_escape_string($_database, $_POST['title']);
        $content = mysqli_real_escape_string($_database, $_POST['message']);
        $tags = $_POST['tags'];
        $editor = isset($_POST['editor']) ? '1' : '0';
        $date = time();
        $categoryID = (int)$_POST['categoryID'];
        $staticID = isset($_POST['staticID']) ? (int)$_POST['staticID'] : null;

        // Rollen verarbeiten (Checkboxen)
        $access_roles = isset($_POST['access_roles']) ? $_POST['access_roles'] : [];
        $access_roles_json = mysqli_real_escape_string($_database, json_encode($access_roles, JSON_UNESCAPED_UNICODE));

        // Überprüfen, ob eine statische Seite mit der angegebenen staticID existiert
        if (!empty($staticID)) {
            // Update der statischen Seite in settings_static
            safe_query("
                UPDATE settings_static
                SET title = '$title',
                    content = '$content',
                    access_roles = '$access_roles_json',
                    date = '$date',
                    editor = '$editor',
                    categoryID = '$categoryID'
                WHERE staticID = '$staticID'
            ");

            // Navigationsmenü aktualisieren
            safe_query("
                DELETE FROM navigation_website_sub
                WHERE url = 'index.php?site=static&amp;staticID=$staticID'
            ");

            safe_query("
                INSERT INTO navigation_website_sub (
                    mnavID, name, modulname, url, sort, indropdown, themes_modulname
                ) VALUES (
                    '$categoryID',
                    '$title',
                    'static',
                    'index.php?site=static&amp;staticID=$staticID',
                    1,
                    1,
                    ''
                )
            ");
        } else {
            // Neue statische Seite erstellen
            safe_query("
                INSERT INTO settings_static (title, content, access_roles, date, editor, categoryID)
                VALUES ('$title', '$content', '$access_roles_json', '$date', '$editor', '$categoryID')
            ");
            $staticID = mysqli_insert_id($_database);

            // Navigationsmenü-Eintrag erstellen
            safe_query("
                INSERT INTO navigation_website_sub (
                    mnavID, name, modulname, url, sort, indropdown, themes_modulname
                ) VALUES (
                    '$categoryID',
                    '$title',
                    'static',
                    'index.php?site=static&amp;staticID=$staticID',
                    1,
                    1,
                    ''
                )
            ");
        }

        // Tags setzen
        \webspell\Tags::setTags('static', $staticID, $tags);

        echo '<div class="alert alert-success" role="alert">' . $_language->module['changes_successful'] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_static";
                }, 3000); // 3 Sekunden warten
            </script>';

    } else {
        echo '<div class="alert alert-danger" role="alert">' . $_language->module['transaction_invalid'] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_static";
                }, 3000); // 3 Sekunden warten
            </script>';
    }
}

 elseif (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        \webspell\Tags::removeTags('static', $_GET[ 'staticID' ]);
        safe_query("DELETE FROM `settings_static` WHERE staticID='" . $_GET[ 'staticID' ] . "'");
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $_language->module[ 'transaction_invalid' ] . '</div>';
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "admincenter.php?site=settings_static";
                }, 3000); // 3 Sekunden warten
            </script>';
    }
}

if (isset($_GET['action']) && $_GET['action'] == "add") {
    // CAPTCHA-Hash generieren
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Editor aktivieren, wenn Checkbox aktiviert war
    $editor = '';
    if (isset($editor) && $editor == 1) {
        $editor = 'ckeditor';
    }   

    // Rollen aus DB laden
    $role_result = safe_query("SELECT role_name FROM user_roles ORDER BY role_name ASC");
    $allRoles = [];
    while ($role = mysqli_fetch_array($role_result)) {
        $allRoles[] = $role['role_name'];
    }

    // Vorhandene Rollen auslesen
    $selectedRoles = [];
    if (!empty($ds['access_roles'])) {
        $selectedRoles = json_decode($ds['access_roles'], true);
    }

    // Checkboxen generieren
    $leftColumn = '';
    $rightColumn = '';
    $half = ceil(count($allRoles) / 2);
    $i = 0;

    foreach ($allRoles as $role) {
        $checked = in_array($role, $selectedRoles) ? 'checked="checked"' : '';
        $checkbox = '<div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="access_roles[]" value="' . htmlspecialchars($role) . '" ' . $checked . '>
            <label class="form-check-label">' . htmlspecialchars($role) . '</label>
        </div>';

        if ($i < $half) {
            $leftColumn .= $checkbox;
        } else {
            $rightColumn .= $checkbox;
        }
        $i++;
    }

    // Kategorien für Select laden
    $category_select = '<select name="categoryID" class="form-select">';
    $category_select .= '<option value="0">-- keine Kategorie --</option>';
    $category_query = safe_query("SELECT mnavID, name FROM navigation_website_main ORDER BY sort ASC");

    while ($row = mysqli_fetch_array($category_query)) {
        // Erstelle ein neues multiLanguage-Objekt für die aktuelle Sprache
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($row['name']);
        
        #$selected = ($row['mnavID'] == $ds['categoryID']) ? ' selected' : '';
        $category_select .= '<option value="' . (int)$row['mnavID'] . '">' . htmlspecialchars($translate->getTextByLanguage($row['name'])) . '</option>';
    }
    $category_select .= '</select>';

    $roleCheckboxes = '
    <div class="row">
        <div class="col-md-6">' . $leftColumn . '</div>
        <div class="col-md-6">' . $rightColumn . '</div>
    </div>';       

        // HTML-Formular für die Eingabe von Daten
        echo '<div class="card">
                <div class="card-header">
                    ' . $_language->module['static_pages'] . '
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admincenter.php?site=settings_static">' . $_language->module['static_pages'] . '</a></li>
                        <li class="breadcrumb-item active" aria-current="page">' . $_language->module['add'] . '</li>
                    </ol>
                </nav>

                <div class="card-body">

                <form class="form-horizontal" method="post" id="post" name="post" action="">
                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['category'] . ':</label>
                            <div class="col-sm-8">
                                ' . $category_select . '
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['title'] . ':</label>
                            <div class="col-sm-8"><span class="text-muted small"><em>
                                <input class="form-control" type="text" name="title" size="60" value="new" /></em></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['tags'] . ':</label>
                            <div class="col-sm-8"><span class="text-muted small"><em>
                                <input class="form-control" type="text" name="tags" size="60" value="" /></em></span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['editor_is_displayed'] . ':</label>
                            <div class="col-sm-8 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggle-editor" name="editor" value="1" checked>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                         <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['accesslevel'] . ':</label>
                            <div class="col-sm-8">
                                ' . $roleCheckboxes . '
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12"><span class="text-muted small"><em>
                        <textarea class="ckeditor" id="ckeditor" name="message" rows="20" cols="" style="width: 100%;"></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <button class="btn btn-success btn-sm" type="submit" name="save"  />' . $_language->module['add'] . '</button>
                    </div>
                </div>
            </form>
        </div>
    </div>';
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-editor');
    const textarea = document.getElementById('ckeditor');

    // Funktion zum Editor aktivieren/deaktivieren
    function toggleEditor() {
        if (toggle.checked) {
            if (!CKEDITOR.instances['ckeditor']) {
                CKEDITOR.replace('ckeditor');
            }
        } else {
            if (CKEDITOR.instances['ckeditor']) {
                CKEDITOR.instances['ckeditor'].destroy(true);
            }
        }
    }

    // Initialer Zustand (z. B. bei Seiten-Reload)
    toggleEditor();

    // Reaktion auf Umschalten
    toggle.addEventListener('change', toggleEditor);
});
</script>
<?php
  
} elseif (isset($_GET['action']) && $_GET['action'] == "edit") {

    $staticID = (int)$_GET['staticID'];
    $ergebnis = safe_query("SELECT * FROM `settings_static` WHERE staticID='" . $staticID . "'");
    $ds = mysqli_fetch_array($ergebnis);
    $content = $ds['content'];
    $title = $ds['title'];
    $tags = \webspell\Tags::getTags('static', $staticID);

    // Editor aktivieren, wenn Checkbox aktiviert war
    $editor = '';
    if (isset($ds['editor']) && $ds['editor'] == 1) {
        $editor = 'ckeditor';
    }

    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    // Rollen aus DB laden
    $role_result = safe_query("SELECT role_name FROM user_roles ORDER BY role_name ASC");
    $allRoles = [];
    while ($role = mysqli_fetch_array($role_result)) {
        $allRoles[] = $role['role_name'];
    }

    // Vorhandene Rollen auslesen
    $selectedRoles = [];
    if (!empty($ds['access_roles'])) {
        $selectedRoles = json_decode($ds['access_roles'], true);
    }

    // Checkboxen generieren
    $leftColumn = '';
    $rightColumn = '';
    $half = ceil(count($allRoles) / 2);
    $i = 0;

    foreach ($allRoles as $role) {
        $checked = in_array($role, $selectedRoles) ? 'checked="checked"' : '';
        $checkbox = '<div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="access_roles[]" value="' . htmlspecialchars($role) . '" ' . $checked . '>
            <label class="form-check-label">' . htmlspecialchars($role) . '</label>
        </div>';

        if ($i < $half) {
            $leftColumn .= $checkbox;
        } else {
            $rightColumn .= $checkbox;
        }
        $i++;
    }

    // Kategorien für Select laden
    $category_select = '<select name="categoryID" class="form-select">';
    $category_select .= '<option value="0">-- keine Kategorie --</option>';
    $category_query = safe_query("SELECT mnavID, name FROM navigation_website_main ORDER BY sort ASC");

    while ($row = mysqli_fetch_array($category_query)) {
        // Erstelle ein neues multiLanguage-Objekt für die aktuelle Sprache
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($row['name']);
        
        $selected = ($row['mnavID'] == $ds['categoryID']) ? ' selected' : '';
        $category_select .= '<option value="' . (int)$row['mnavID'] . '"' . $selected . '>' . htmlspecialchars($translate->getTextByLanguage($row['name'])) . '</option>';
    }
    $category_select .= '</select>';

    $roleCheckboxes = '
    <div class="row">
        <div class="col-md-6">' . $leftColumn . '</div>
        <div class="col-md-6">' . $rightColumn . '</div>
    </div>';

    // Formularausgabe
    echo '
    <div class="card">
        <div class="card-header">' . $_language->module['static_pages'] . '</div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admincenter.php?site=settings_static">' . $_language->module['static_pages'] . '</a></li>
                <li class="breadcrumb-item active" aria-current="page">' . $_language->module['edit'] . '</li>
            </ol>
        </nav>

        <div class="card-body">
            <div class="container py-5">
            <!-- Benutzerrolle zuweisen -->
            <h3 class="mb-4">' . $_language->module[ 'static_pages' ] . '</h3>

            <form class="form-horizontal" method="post" action="">

                <div class="row">
                    <div class="col-md-6">

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['category'] . ':</label>
                            <div class="col-sm-8">
                                ' . $category_select . '
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['title'] . ':</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="title" value="' . htmlspecialchars($title) . '" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['tags'] . ':</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="tags" value="' . htmlspecialchars($tags) . '" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['editor_is_displayed'] . ':</label>
                            <div class="col-sm-8 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="toggle-editor" name="editor" value="1"' . ($ds['editor'] == 1 ? ' checked' : '') . '>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label class="col-sm-3 control-label">' . $_language->module['accesslevel'] . ':</label>
                            <div class="col-sm-8">
                                ' . $roleCheckboxes . '
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <textarea class="' . $editor . '" id="ckeditor" name="message" rows="20" style="width: 100%;">' . htmlspecialchars($content) . '</textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <input type="hidden" name="captcha_hash" value="' . $hash . '" />
                        <input type="hidden" name="staticID" value="' . $staticID . '" />
                        <button class="btn btn-warning btn-sm" type="submit" name="save">' . $_language->module['edit'] . '</button>
                    </div>
                </div>

            </form>
            </div>
        </div>
    </div>';

?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-editor');
    const textarea = document.getElementById('ckeditor');

    // Funktion zum Editor aktivieren/deaktivieren
    function toggleEditor() {
        if (toggle.checked) {
            if (!CKEDITOR.instances['ckeditor']) {
                CKEDITOR.replace('ckeditor');
            }
        } else {
            if (CKEDITOR.instances['ckeditor']) {
                CKEDITOR.instances['ckeditor'].destroy(true);
            }
        }
    }

    // Initialer Zustand (z. B. bei Seiten-Reload)
    toggleEditor();

    // Reaktion auf Umschalten
    toggle.addEventListener('change', toggleEditor);
});
</script>
<?php
} else {

    echo '<div class="card">
            <div class="card-header">
                ' . $_language->module['static_pages'] . '
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb t-5 p-2 bg-light">
                    <li class="breadcrumb-item">
                        <a href="admincenter.php?site=settings_static">' . $_language->module['static_pages'] . '</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">News / Edit</li>
                </ol>
            </nav>

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-1 control-label">' . $_language->module['options'] . ':</label>
                    <div class="col-md-8">
                        <a href="admincenter.php?site=settings_static&amp;action=add" class="btn btn-primary btn-sm" type="button">
                            ' . $_language->module['new_static_page'] . '
                        </a>
                    </div>
                </div>

                <div class="container py-5">
                    <h3 class="mb-4">' . $_language->module['static_pages'] . '</h3>';

    $ergebnis = safe_query("SELECT * FROM settings_static ORDER BY staticID");

    echo '<table class="table table-bordered table-striped bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th><b>' . $_language->module['id'] . '</b></th>
                        <th><b>' . $_language->module['title'] . '</b></th>
                        <th><b>' . $_language->module['accesslevel'] . '</b></th>
                        <th><b>' . $_language->module['actions'] . '</b></th>
                    </tr>
                </thead>';

    $i = 1;
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    while ($ds = mysqli_fetch_array($ergebnis)) {
        $td = ($i % 2) ? 'td1' : 'td2';
        $roles = [];

        if (!empty($ds['access_roles'])) {
            // Als JSON speichern: ["Clanmitglied", "Moderator"]
            $roles = json_decode($ds['access_roles'], true);
        }

        $accesslevel = empty($roles) ? $_language->module['public'] : implode(', ', array_map(function($role) {
            return $_language->module[strtolower($role)] ?? htmlspecialchars($role);
        }, $roles));

        $title = $ds['title'];

        // Mehrsprachigkeit für Titel
        $translate = new multiLanguage(detectCurrentLanguage());
        $translate->detectLanguages($title);
        $title = $translate->getTextByLanguage($title);

        echo '<tr>
                <td>' . $ds['staticID'] . '</td>
                <td><a href="../index.php?site=static&amp;staticID=' . $ds['staticID'] . '" target="_blank">' . $title . '</a></td>
                <td>' . $accesslevel . '</td>
                <td>
                    <a href="admincenter.php?site=settings_static&amp;action=edit&amp;staticID=' . $ds['staticID'] . '" class="hidden-xs hidden-sm btn btn-warning btn-sm" type="button">' . $_language->module['edit'] . '</a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="admincenter.php?site=settings_static&amp;delete=true&amp;staticID=' . $ds['staticID'] . '&amp;captcha_hash=' . $hash . '">
                        ' . $_language->module['delete'] . '
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">' . $_language->module['static_pages'] . '</h5>
                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="' . $_language->module['close'] . '"></button>
                                </div>
                                <div class="modal-body"><p>' . $_language->module['really_delete'] . '</p></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">' . $_language->module['close'] . '</button>
                                    <a class="btn btn-danger btn-ok btn-sm">' . $_language->module['delete'] . '</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal END -->
                </td>
            </tr>';
        
        $i++;
    }

    echo '</table>';
    echo '</div></div></div>';
}

?>