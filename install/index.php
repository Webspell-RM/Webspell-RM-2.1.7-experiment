<?php
include('head.php');

if ($step == '1') {
    $accepted1 = checksession('agree') ? 'selected="selected"' : '';
    $accepted2 = !$accepted1 ? 'selected="selected"' : '';

    $data_array = array();
    $data_array['$accepted1'] = $accepted1;
    $data_array['$accepted2'] = $accepted2;
    $data_array['$licence'] = $_language->module['licence'];
    $data_array['$version'] = $_language->module['version'] . ' ' . $version;
    $data_array['$update'] = $_language->module['update'] . ' ' . $update;
    $data_array['$info'] = $_language->module['gpl_info'] . '<br />' . $_language->module['more_info'];
    $data_array['$please_select'] = $_language->module['please_select'];
    $data_array['$agree_not'] = $_language->module['agree_not'];
    $data_array['$agree'] = $_language->module['agree'];
    $data_array['$continue'] = $_language->module['continue'];

    $step01 = $_template->loadTemplate('step01', 'content', $data_array);
    echo $step01;

}elseif ($step == '2') {
    if (isset($_POST['agree'])) {
        $_SESSION['agree'] = $_POST['agree'];
    }

    $installtype = checksession('installtype');
    $accepted1 = ($installtype == 'org') ? 'checked="checked"' : '';
    $accepted2 = ($installtype == 'nor') ? 'checked="checked"' : '';
    $accepted3 = ($installtype == 'rm200') ? 'checked="checked"' : '';
    $accepted4 = ($installtype == 'rm201') ? 'checked="checked"' : '';
    $accepted5 = (!$installtype) ? 'checked="checked"' : '';

    if (checksession('agree') == '1') {
        $php_version = phpversion();

        // Prüfen, ob die PHP-Version kleiner als 8.0 ist
        $versionerror = version_compare($php_version, '8.0.0', '<');

        $data_array = [];
        if ($versionerror) {
            $data_array = array();
            $data_array['$php_version'] = $_language->module['php_version'];
            $data_array['$php_info'] = $_language->module['php_info'];
            $step02_content = $_template->loadTemplate('step02', 'versionerror', $data_array);
        } else {
            $data_array = array();
            $data_array['$enter_url'] = $_language->module['enter_url'];
            $data_array['$hp_url'] = CurrentUrl();
            $step02_content = $_template->loadTemplate('step02', 'enterhomepage', $data_array);
        }

        $data_array = array();
        $data_array['$title'] = ($versionerror) ? $_language->module['error'] : $_language->module['your_site_url'];
        $data_array['$step02_content'] = $step02_content;
        $data_array['$back'] = $_language->module['back'];

        $data_array['$new_install'] = $_language->module['new_install'];
        $data_array['$what_to_do'] = $_language->module['what_to_do'];
        $data_array['$select_install'] = $_language->module['select_install'];
        $data_array['$accepted1'] = $accepted1;
        $data_array['$accepted2'] = $accepted2;
        $data_array['$accepted3'] = $accepted3;
        $data_array['$accepted4'] = $accepted4;
        $data_array['$accepted5'] = $accepted5;
        $fatal2_error = ''; 

        $step02 = $_template->loadTemplate('step02', 'content', $data_array);
        echo $step02;

        // Datei-Überprüfung
        $filename = '../includes/expansion/default/css/stylesheet.css';
        $stylesheet = file_exists($filename) ?
            '<div class="alert alert-success text-center" role="alert">' . $_language->module['the_file'] . ' "<i>' . $filename . '</i>" ' . $_language->module['exists'] . ' <i class="bi bi-check-lg"></i></div>' :
            '<div class="alert alert-danger text-center" role="alert">' . $_language->module['the_file'] . ' "<i>' . $filename . '</i>" ' . $_language->module['does not exist'] . ' <i class="bi bi-x-lg"></i></div>';

        // PHP-Version überprüfen
        $php_version_check = version_compare(PHP_VERSION, '8.0.0', '<') ?
            '<div class="alert alert-danger text-center" role="alert">' . $_language->module['your_php_version'] . ': ' . phpversion() . ' ' . $_language->module['is_not_compatible'] . ' <i class="bi bi-x-lg"></i></div>' :
            '<div class="alert alert-success text-center" role="alert">' . $_language->module['your_php_version'] . ': ' . phpversion() . ' ' . $_language->module['is_compatible'] . ' <i class="bi bi-check-lg"></i></div>';











            

        // Prüfen, ob die PHP-Version kleiner als 8.0 ist
$versionerror = version_compare($php_version, '8.0.0', '<');

// Verzeichnisse und Dateien auf Schreibrechte prüfen
$chmodfiles = [
    '/includes/expansion/default/css/stylesheet.css',
    '/images/avatars',
    '/images/userpics',
    '/includes/plugins',
    '/includes/themes',
    '/system/sql.php',
    '/tmp/'
];

$error = [];
foreach ($chmodfiles as $file) {
    if (!is_writable('..' . $file) && !@chmod('..' . $file, 0777)) {
        $error[] = $file;
    }
}

// Wenn ein Fehler bei der PHP-Version oder den Dateiberechtigungen auftritt, den Button "Weiter" nicht anzeigen
$weiter = '';
if (!$versionerror && count($error) == 0) {
    // Wenn keine Fehler bei der PHP-Version und den Dateiberechtigungen aufgetreten sind, den Button anzeigen
    $weiter = '<a class="btn btn-primary text-end" href="javascript:document.ws_install.submit()">
                ' . $_language->module['continue'] . '
              </a>';
}

// Fehlermeldung für PHP-Version oder Dateiberechtigungen
$php_version_check = $versionerror ?
    '<div class="alert alert-danger text-center" role="alert">' . $_language->module['your_php_version'] . ': ' . phpversion() . ' ' . $_language->module['is_not_compatible'] . ' <i class="bi bi-x-lg"></i></div>' :
    '<div class="alert alert-success text-center" role="alert">' . $_language->module['your_php_version'] . ': ' . phpversion() . ' ' . $_language->module['is_compatible'] . ' <i class="bi bi-check-lg"></i></div>';

$chmod_errors = count($error) ? 
    '<div class="alert alert-danger text-center" role="alert">' . $_language->module['chmod_error'] . ' <i class="bi bi-x-lg"></i></div>' :
    '<div class="alert alert-success text-center" role="alert">' . $_language->module['successful'] . ' <i class="bi bi-check-lg"></i></div>';

$values = count($error) ? implode('', array_map(fn($file) =>
    '<div class="alert alert-danger text-center" role="alert">' . $_language->module['unwriteable1'] . ' "<i>' . $file . '</i>" ' . $_language->module['unwriteable2'] . ' <i class="bi bi-x-lg"></i></div>', $error)) : '';

// Übergabe der Daten an das Template
/*$data_array = array();
$data_array['$php_version_check'] = $php_version_check;
$data_array['$chmod_errors'] = $chmod_errors;
$data_array['$values'] = $values;
$data_array['$weiter'] = $weiter;
$data_array['$successful'] = $_language->module['successful'];
$data_array['$setting_chmod'] = $_language->module['setting_chmod'];*/

        // System-Funktionen prüfen
        $data_array = array();
        $data_array['$mysqli_check'] = checkfunc('mysqli_connect');
        $data_array['$mb_check'] = checkfunc('mb_substr');
        $data_array['$curl_check'] = checkfunc('curl_version');
        $data_array['$curlexec_check'] = checkfunc('curl_exec');
        $data_array['$allow_url_fopen_check'] = checkfunc('allow_url_fopen');
        $data_array['$php_version_check'] = $php_version_check;
        $data_array['$stylesheet'] = $stylesheet;
        $data_array['$values'] = $values;
        $data_array['$weiter'] = $weiter;
        $data_array['$successful'] = $_language->module['successful'];
        $data_array['$setting_chmod'] = $_language->module['setting_chmod'];
        $data_array['$chmod_errors'] = $chmod_errors;
        $data_array['$version_from'] = $_language->module['version_from'];
        $data_array['$or_higher'] = $_language->module['or_higher'];

        $data_array['$the_file'] = $_language->module['the_file'];
        $data_array['$exists'] = $_language->module['exists'];
        $data_array['$does not exist'] = $_language->module['does not exist'];

        $step02_chmod = $_template->loadTemplate('step02', 'chmod', $data_array);
        echo $step02_chmod;

        // Finales Template mit Auswahl der Installation
        $fatal2_error = count($error) ? 'true' : '';

        $data_array['$back'] = $_language->module['back'];
        $data_array['$continue'] = $_language->module['continue'];
        $data_array['$new_install'] = $_language->module['new_install'];
        $data_array['$what_to_do'] = $_language->module['what_to_do'];
        $data_array['$select_install'] = $_language->module['select_install'];

        $data_array['$accepted1'] = $accepted1;
        $data_array['$accepted2'] = $accepted2;
        $data_array['$accepted3'] = $accepted3;
        $data_array['$accepted4'] = $accepted4;
        $data_array['$accepted5'] = $accepted5;

        $fatal2_error = '';     

        echo $_template->loadTemplate('step02', 'select', $data_array);
    } else {
        $data_array = array();
        $data_array['$you_have_to_agree'] = $_language->module['you_have_to_agree'];
        $data_array['$back'] = $_language->module['back'];
        $step02 = $_template->loadTemplate('step02', 'failed', $data_array);
        echo $step02;
    }
}elseif ($step == '3') {

    $adminname = '';
    $adminpwd = '';
    $adminmail = '';
    $getuser = '';
    $getpwd = '';
    $getdb = '';
    $getprefix = '';

    // Sicherstellen, dass 'installtype' und 'hp_url' gesetzt sind und sicher in Session speichern
    if (isset($_POST['installtype'])) {
        $_SESSION['installtype'] = $_POST['installtype'];
    }
    if (isset($_POST['hp_url'])) {
        $_SESSION['hp_url'] = $_POST['hp_url'];
    }

    // Sessionwerte überprüfen und setzen
    if (checksession('adminname')) {
        $adminname = checksession('adminname');    
    }
    if (checksession('adminpwd')) {
        $adminpwd = checksession('adminpwd');    
    }
    if (checksession('adminmail')) {
        $adminmail = checksession('adminmail');    
    }
    if (checksession('user')) {
        $getuser = checksession('user');    
    }
    if (checksession('pwd')) {
        $getpwd = checksession('pwd');    
    }
    if (checksession('db')) {
        $getdb = checksession('db');    
    }
    if (checksession('prefix')) {
        $getprefix = checksession('prefix');    
    } else {
        // Zufälliges Prefix generieren, wenn es nicht gesetzt ist
        $getprefix = 'rm_'.RandPass(3).'_';
    }

    // Wenn Installations-Typ "full" und "hp_url" gesetzt sind, MySQL-Konfiguration anzeigen
    if (checksession('installtype') == 'full' && checksession('hp_url')) {
        $data_array['$continue'] = $_language->module['continue'];
        $data_array['$back'] = $_language->module['back'];
        $data_array['$data_config'] = $_language->module['data_config'];
        $data_array['$min_requirements'] = $_language->module['min_requirements'];
        $data_array['$pass_info'] = $_language->module['pass_info'];
        $data_array['$php_info'] = $_language->module['php_info'];
        $data_array['$php_ver'] = $_language->module['php_ver'];
        $data_array['$host_name'] = $_language->module['host_name'];
        $data_array['$mysql_username'] = $_language->module['mysql_username'];
        $data_array['$mysql_password'] = $_language->module['mysql_password'];
        $data_array['$mysql_database'] = $_language->module['mysql_database'];
        $data_array['$mysql_prefix'] = $_language->module['mysql_prefix'];
        $data_array['$RandPass'] = $getprefix;
        $data_array['$tooltip_1'] = $_language->module['tooltip_1'];
        $data_array['$tooltip_2'] = $_language->module['tooltip_2'];
        $data_array['$tooltip_3'] = $_language->module['tooltip_3'];
        $data_array['$tooltip_4'] = $_language->module['tooltip_4'];
        $data_array['$tooltip_5'] = $_language->module['tooltip_5'];
        $data_array['$webspell_config'] = $_language->module['webspell_config'];
        $data_array['$pass_ver'] = $_language->module['pass_ver'];
        $data_array['$pass_text'] = $_language->module['pass_text'];
        $data_array['$admin_username'] = $_language->module['admin_username'];
        $data_array['$admin_password'] = $_language->module['admin_password'];
        $data_array['$admin_email'] = $_language->module['admin_email'];
        $data_array['$tooltip_6'] = $_language->module['tooltip_6'];
        $data_array['$tooltip_7'] = $_language->module['tooltip_7'];
        $data_array['$tooltip_8'] = $_language->module['tooltip_8'];
        $data_array['$postinstalltype'] = checksession('installtype');
        $data_array['$hp_url'] = checksession('hp_url');
        $data_array['$adminname'] = $adminname;
        $data_array['$adminpwd'] = $adminpwd;
        $data_array['$adminmail'] = $adminmail;
        $data_array['$getuser'] = $getuser;
        $data_array['$getpwd'] = $getpwd;
        $data_array['$getdb'] = $getdb;
        $data_array['$getprefix'] = $getprefix;
        $step03 = $_template->loadTemplate('step03', 'mysqlconfig', $data_array);
        echo $step03;
    } else {
        // Wenn es sich um ein Update handelt
        $step03 = $_template->loadTemplate('step03', 'update', $data_array);
        echo $step03;
        $data_array['$continue'] = $_language->module['continue'];
        $data_array['$back'] = $_language->module['back'];
        $data_array['$finish_install'] = $_language->module['finish_install'];
        $data_array['$finish_next'] = $_language->module['finish_next'];
        $data_array['$postinstalltype'] = checksession('installtype');
        $step03 = $_template->loadTemplate('step03', 'update', $data_array);
        echo $step03;
    }
}elseif ($step == '4') {

    // Session-Daten setzen
    if (checksession('hp_url')) {
        $_SESSION['hp_url'] = checksession('hp_url');
    } 
    if (checksession('installtype')) {
        $_SESSION['installtype'] = checksession('installtype');
    }

    // POST-Daten in Session speichern
    $fields = ['adminname', 'adminpwd', 'adminmail', 'user', 'pwd', 'db', 'prefix'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION[$field] = $_POST[$field];
        }
    }

    // Template-Daten vorbereiten
    $data_array = array();
    $data_array['$finish_install'] = $_language->module['finish_install'];

    // Template laden und anzeigen
    $step04 = $_template->loadTemplate('step04', 'head', $data_array);
    echo $step04;

    include('functions.php');
    $errors = array();

    // Installationstyp prüfen (update oder full)
    if ($_SESSION['installtype'] != "full") {
        include('../system/sql.php');
        @$_database = new mysqli($host, $user, $pwd, $db);

        if (mysqli_connect_error()) {
            $errors[] = $_language->module['error_mysql'];
        }

        $type = '<div class="list-group-item list-group-item-success"><b>' . $_language->module['update_complete'] . '</b></div>';
        $in_progress = $_language->module['update_running'];
    }

   // Full-Installation logic
if ($_SESSION['installtype'] == 'full') {
    $type = '<div class="text-center"><h5>' . $_language->module['install_complete'] . '</h5></div>';
    $in_progress = $_language->module['install_running'];
    $is_complete = $_language->module['install_finished'];

    // POST-Daten aus der Session holen
    $host = $_POST['host'];
    $user = $_POST['user'];
    $pwd = $_POST['pwd'];
    $db = $_POST['db'];
    $prefix = $_POST['prefix'];
    $adminname = $_POST['adminname'];
    $adminpwd = $_POST['adminpwd'];
    $adminmail = $_POST['adminmail'];

    $hp_url = isset($_POST['hp_url']) ? $_POST['hp_url'] : CurrentUrl();

    // Eingabedaten validieren
    $required_fields = ['host', 'db', 'adminname', 'adminpwd', 'adminmail', 'hp_url'];
    foreach ($required_fields as $field) {
        if (!mb_strlen(trim($$field))) {
            $errors[] = $_language->module['verify_data'];
        }
    }

    // MySQL-Verbindung herstellen
    $_database = new mysqli($host, $user, $pwd, $db);
    if (mysqli_connect_error()) {
        $errors[] = $_language->module['error_mysql'];
    }

    // Konfigurationsdatei schreiben
    $file = '../system/sql.php';
    if ($fp = fopen($file, 'wb')) {
        $string = '<?php
        $host = "' . $host . '";
        $user = "' . $user . '";
        $pwd = "' . $pwd . '";
        $db = "' . $db . '";
        if (!defined("PREFIX")) {
            define("PREFIX", \'' . $prefix . '\');
        }
        ?>';

        fwrite($fp, $string);
        fclose($fp);
    } else {
        $errors[] = $_language->module['write_failed'];
    }

    // Session-Variablen setzen
    $_SESSION['adminpassword'] = $adminpwd;
    $_SESSION['adminname'] = $adminname;
    $_SESSION['adminmail'] = $adminmail;
    $_SESSION['url'] = $hp_url;

    // Update-Funktionen je nach Installationstyp setzen
    $update_functions = [];
    switch ($_SESSION['installtype']) {
        case 'full':
            $update_functions = array_merge($update_functions, ["rm_1", "rm_2", "rm_3", "rm_4", "rm_5", "rm_6", "rm_7", "rm_8", "rm_9", "rm_10", "rm_11", "rm_12", "rm_13", "rm_14", "rm_15", "rm_16", "rm_17", "rm_18", "rm_19", "rm_20", "rm_21", "rm_22", "rm_23", "rm_24", "rm_25", "rm_26", "rm_27", "rm_28", "rm_29", "rm_30", "rm_31", "rm_32", "rm_33"]);
            break;
        case 'org':
            $update_functions = array_merge($update_functions, ["org_rm209_1", "org_rm209_2", "org_rm209_3"]);
            break;
        case 'nor':
            $update_functions = array_merge($update_functions, ["nor_rm209_1", "nor_rm209_2", "nor_rm209_3"]);
            break;
        case 'rm200':
            $update_functions = array_merge($update_functions, ["rm_200_201_1", "rm_200_201_2", "rm_200_201_3"]);
            break;
        case 'rm201':
            $update_functions = array_merge($update_functions, ["rm_201_202_1", "rm_201_202_2"]);
            break;
    }

    // Ordner löschen
    $update_functions[] = "clearfolder";
}

// Fehler anzeigen oder Update-Prozess fortsetzen
if (count($errors)) {
    $fehler = implode('<br>', array_unique($errors));
    $text = '<div class="list-group-item list-group-item-danger"><strong>' . $_language->module['error'] . ':</strong> ' . $fehler . '</div>';
} else {
    // Hier wird die Fortschrittsanzeige oder andere notwendige Templates geladen
    $text = update_progress($update_functions);
}


// Template-Daten für Fortschritt
$data_array = array();
$data_array['$in_progress'] = $_language->module['in_progress']; 
$data_array['$is_complete'] = $_language->module['is_complete'];
$data_array['$text'] = $text;
$data_array['$type'] = $type;
$data_array['$view_site'] = $_language->module['view_site'];

$step04 = $_template->loadTemplate('step04', 'content', $data_array);
echo $step04;


    // Sperrdateien setzen
    file_put_contents("locked.txt", "installation locked");
    file_put_contents("../locked.txt", "installation locked");

    // Abschluss des Templates
    $step04 = $_template->loadTemplate('step04', 'foot', $data_array);
    echo $step04;
} else {
    // Startseite oder Sprachwahl
    $languages = '';
    if ($handle = opendir('./languages/')) {
        while (false !== ($file = readdir($handle))) {
            if (is_dir('./languages/' . $file) && $file != ".." && $file != "." && $file != ".svn") {
                $languages .= '<a class="btn btn-default btn-margin btn-sm" href="index.php?lang=' . $file . '"><img style="width: 25px" src="../images/languages/' . $file . '.png" alt="' . $file . '"></a>';
            }
        }
        closedir($handle);
    }

    // Sperrdatei anzeigen, falls vorhanden
    if (file_exists("locked.txt")) {
        $step00_content = '<div class="alert alert-danger">'.$_language->module['installerlocked'].'</div>';
    } else {
        $data_array = array();
        $data_array['$welcome_text_1'] = $_language->module['welcome_text_1'];
        $data_array['$welcome_text_2'] = $_language->module['welcome_text_2'];
        $data_array['$welcome_text_3'] = $_language->module['welcome_text_3'] . '<br />' . $_language->module['webspell_team'];
        $data_array['$continue'] = $_language->module['continue'];
        $step00_content = $_template->loadTemplate('step00', 'success', $data_array);
    }

    // Sprachwahl und Willkommensnachricht anzeigen
    $data_array = array();
    $data_array['$welcome_to'] = $_language->module['welcome_to'];
    $data_array['$select_a_language'] = $_language->module['select_a_language'];
    $data_array['$languages'] = $languages;
    $data_array['$step00_content'] = $step00_content;
    $step00 = $_template->loadTemplate('step00', 'content', $data_array);
    echo $step00;
}


include('foot.php');
?>
                        

