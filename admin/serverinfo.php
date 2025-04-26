<?php
// Sprachmodul laden
$_language->readModule('overview', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_overview');

// Serverinformationen sammeln
$phpversion = phpversion() < '4.3' ? '<font color="#FF0000">' . phpversion() . '</font>' :
    '<font color="#008000">' . phpversion() . '</font>';
$zendversion = zend_version() < '1.3' ? '<font color="#FF0000">' . zend_version() . '</font>' :
    '<font color="#008000">' . zend_version() . '</font>';
$mysqlversion = mysqli_get_server_version($_database) < '40000' ?
    '<font color="#FF0000">' . mysqli_get_server_info($_database) . '</font>' :
    '<font color="#008000">' . mysqli_get_server_info($_database) . '</font>';
$get_phpini_path = get_cfg_var('cfg_file_path');
$get_allow_url_fopen =
    get_cfg_var('allow_url_fopen') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
$get_allow_url_include =
    get_cfg_var('allow_url_include') ? '<font color="#FF0000">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#008000">' . $_language->module[ 'off' ] . '</font>';
$get_display_errors =
    get_cfg_var('display_errors') ? '<font color="#FFA500">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#008000">' . $_language->module[ 'off' ] . '</font>';
$get_file_uploads = get_cfg_var('file_uploads') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
    '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
$get_log_errors = get_cfg_var('log_errors') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
    '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';

$get_max_execution_time = get_cfg_var('max_execution_time') < 30 ?
    '<font color="#FF0000">' . get_cfg_var('max_execution_time') . '</font> <small>(min. > 30)</small>' :
    '<font color="#008000">' . get_cfg_var('max_execution_time') . '</font>';
$get_open_basedir = get_cfg_var('open_basedir') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
    '<font color="#FFA500">' . $_language->module[ 'off' ] . '</font>';
$get_post_max_size =
    get_cfg_var('post_max_size') > 8 ? '<font color="#FFA500">' . get_cfg_var('post_max_size') . '</font>' :
        '<font color="#008000">' . get_cfg_var('post_max_size') . '</font>';
$get_register_globals =
    get_cfg_var('register_globals') ? '<font color="#FF0000">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#008000">' . $_language->module[ 'off' ] . '</font>';

if (function_exists('curl_version')) {
    $curl_check = '<font color="#008000">' . $_language->module[ 'on' ] . '</font>';
} else {
    $curl_check = '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
    $fatal_error = true;
}

$get_upload_max_filesize = get_cfg_var('upload_max_filesize') > 16 ?
    '<font color="#FFA500">' . get_cfg_var('upload_max_filesize') . '</font>' :
    '<font color="#008000">' . get_cfg_var('upload_max_filesize') . '</font>';

$info_na = '<font color="#8F8F8F">' . $_language->module[ 'na' ] . '</font>';

if (function_exists("gd_info")) {
    $gdinfo = gd_info();
    $get_gd_info = '<font color="#008000">' . $_language->module[ 'enable' ] . '</font>';
    $get_gdtypes = array();
    if (isset($gdinfo[ 'FreeType Support' ]) && $gdinfo[ 'FreeType Support' ] === true) {
        $get_gdtypes[ ] = "FreeType";
    }
    if (isset($gdinfo[ 'T1Lib Support' ]) && $gdinfo[ 'T1Lib Support' ] === true) {
        $get_gdtypes[ ] = "T1Lib";
    }
    if (isset($gdinfo[ 'GIF Read Support' ]) && $gdinfo[ 'GIF Read Support' ] === true) {
        $get_gdtypes[ ] = "*.gif " . $_language->module[ 'read' ];
    }
    if (isset($gdinfo[ 'GIF Create Support' ]) && $gdinfo[ 'GIF Create Support' ] === true) {
        $get_gdtypes[ ] = "*.gif " . $_language->module[ 'create' ];
    }
    if (isset($gdinfo[ 'JPG Support' ]) && $gdinfo[ 'JPG Support' ] === true) {
        $get_gdtypes[ ] = "*.jpg";
    } elseif (isset($gdinfo[ 'JPEG Support' ]) && $gdinfo[ 'JPEG Support' ] === true) {
        $get_gdtypes[ ] = "*.jpg";
    }
    if (isset($gdinfo[ 'PNG Support' ]) && $gdinfo[ 'PNG Support' ] === true) {
        $get_gdtypes[ ] = "*.png";
    }
    if (isset($gdinfo[ 'WBMP Support' ]) && $gdinfo[ 'WBMP Support' ] === true) {
        $get_gdtypes[ ] = "*.wbmp";
    }
    if (isset($gdinfo[ 'XBM Support' ]) && $gdinfo[ 'XBM Support' ] === true) {
        $get_gdtypes[ ] = "*.xbm";
    }
    if (isset($gdinfo[ 'XPM Support' ]) && $gdinfo[ 'XPM Support' ] === true) {
        $get_gdtypes[ ] = "*.xpm";
    }
    $get_gdtypes = implode(", ", $get_gdtypes);
} else {
    $get_gd_info = '<font color="#FF0000">' . $_language->module[ 'disable' ] . '</font>';
    $gdinfo[ 'GD Version' ] = '---';
    $get_gdtypes = '---';
}

$get = safe_query("SELECT DATABASE()");
$ret = mysqli_fetch_array($get);
$db = $ret[ 0 ];

// HTML-Ausgabe mit Bootstrap
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systemübersicht</title>
    <!-- Bootstrap 5.3 CSS einbinden -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Systemübersicht</h2>
    <div class="row">
        <!-- Erste Spalte -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>PHP und Serverinformationen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">PHP Version: <span class="text-success"><?php echo phpversion(); ?></span></li>
                        <li class="list-group-item">Zend Version: <span class="text-success"><?php echo zend_version(); ?></span></li>
                        <li class="list-group-item">MySQL Version: <span class="text-success"><?php echo mysqli_get_server_version($_database); ?></span></li>
                        <li class="list-group-item">max_execution_time: <span class="text-danger"><?php echo get_cfg_var('max_execution_time'); ?>s</span></li>
                        <li class="list-group-item">file_uploads: <span class="text-<?php echo get_cfg_var('file_uploads') ? 'success' : 'danger'; ?>"><?php echo get_cfg_var('file_uploads') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                        <li class="list-group-item">register_globals: <span class="text-<?php echo get_cfg_var('register_globals') ? 'danger' : 'success'; ?>"><?php echo get_cfg_var('register_globals') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                        <li class="list-group-item">upload_max_filesize: <span class="text-<?php echo get_cfg_var('upload_max_filesize') > 16 ? 'warning' : 'success'; ?>"><?php echo get_cfg_var('upload_max_filesize'); ?> MB</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Zweite Spalte -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>GD und cURL Informationen</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">GD Version: <span class="text-<?php echo function_exists('gd_info') ? 'success' : 'danger'; ?>"><?php echo function_exists('gd_info') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                        <li class="list-group-item">cURL: <span class="text-<?php echo function_exists('curl_version') ? 'success' : 'danger'; ?>"><?php echo function_exists('curl_version') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                        <li class="list-group-item">GD unterstützte Formate: <span class="text-success"><?php echo implode(', ', $get_gdtypes); ?></span></li>
                        <li class="list-group-item">open_basedir: <span class="text-<?php echo get_cfg_var('open_basedir') ? 'success' : 'warning'; ?>"><?php echo get_cfg_var('open_basedir') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                        <li class="list-group-item">short_open_tag: <span class="text-<?php echo get_cfg_var('short_open_tag') ? 'success' : 'warning'; ?>"><?php echo get_cfg_var('short_open_tag') ? 'Aktiviert' : 'Deaktiviert'; ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Weitere Datenbankinformationen -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Datenbankverbindung</h5>
                </div>
                <div class="card-body">
                    <p><strong>Datenbank:</strong> <?php echo $db; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3 JS einbinden -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
