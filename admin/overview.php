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
 * @copyright       2018-2025 by webspell-rm.de                                                              *
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

// Sprachmodul laden
$_language->readModule('overview', false, true);

use webspell\AccessControl;
// Den Admin-Zugriff für das Modul überprüfen
AccessControl::checkAdminAccess('ac_overview');

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
#$get_magic_quotes =
#    get_cfg_var('magic_quotes_gpc') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
#        '<font color="#FFA500">' . $_language->module[ 'off' ] . '</font>';
$get_max_execution_time = get_cfg_var('max_execution_time') < 30 ?
    '<font color="#FF0000">' . get_cfg_var('max_execution_time') . '</font> <small>(min. > 30)</small>' :
    '<font color="#008000">' . get_cfg_var('max_execution_time') . '</font>';
#$get_memory_limit =
#    get_cfg_var('memory_limit') > 128 ? '<font color="#FFA500">' . get_cfg_var('memory_limit') . '</font>' :
#        '<font color="#008000">' . get_cfg_var('memory_limit') . '</font>';
$get_open_basedir = get_cfg_var('open_basedir') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
    '<font color="#FFA500">' . $_language->module[ 'off' ] . '</font>';
$get_post_max_size =
    get_cfg_var('post_max_size') > 8 ? '<font color="#FFA500">' . get_cfg_var('post_max_size') . '</font>' :
        '<font color="#008000">' . get_cfg_var('post_max_size') . '</font>';
$get_register_globals =
    get_cfg_var('register_globals') ? '<font color="#FF0000">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#008000">' . $_language->module[ 'off' ] . '</font>';
#$get_safe_mode = get_cfg_var('safe_mode') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
#    '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
$get_short_open_tag =
    get_cfg_var('short_open_tag') ? '<font color="#008000">' . $_language->module[ 'on' ] . '</font>' :
        '<font color="#FFA500">' . $_language->module[ 'off' ] . '</font>';

if (function_exists('curl_version')) {
    $curl_check = '<font color="#008000">' . $_language->module[ 'on' ] . '</font>';
} else {
    $curl_check = '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
    $fatal_error = true;
}
if (function_exists('curl_exec')) {
    $curlexec_check = '<font color="#008000">' . $_language->module[ 'on' ] . '</font>';
} else {
    $curlexec_check = '<font color="#FF0000">' . $_language->module[ 'off' ] . '</font>';
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

if (function_exists("apache_get_modules")) {
    $apache_modules = implode(", ", apache_get_modules());
} else {
    $apache_modules = $_language->module[ 'na' ];
}

$get = safe_query("SELECT DATABASE()");
$ret = mysqli_fetch_array($get);
$db = $ret[ 0 ];
 ?>


<div class="card">
<div class="card-header">
        <?php echo $_language->module['system_information']; ?>
    </div>
    <div class="card-body"><div class="container py-5">

<!-- Serverinfo und GD Graphics -->
<div class="row">
    <div class="col-md-6">
        <h4 class="mb-3"><?php echo $_language->module['serverinfo']; ?></h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th><?php echo $_language->module['property']; ?></th>
                    <th><?php echo $_language->module['value']; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td><?php echo $_language->module['webspell_version']; ?></td><td><em class="text-success"><?php echo $version; ?></em></td></tr>
                <tr><td><?php echo $_language->module['php_version']; ?></td><td><em><?php echo $phpversion; ?></em></td></tr>
                <tr><td><?php echo $_language->module['zend_version']; ?></td><td><em><?php echo $zendversion; ?></em></td></tr>
                <tr><td><?php echo $_language->module['mysql_version']; ?></td><td><em><?php echo $mysqlversion; ?></em></td></tr>
                <tr><td><?php echo $_language->module['databasename']; ?></td><td><em><?php echo $db; ?></em></td></tr>
                <tr><td><?php echo $_language->module['server_os']; ?></td><td><em><?php echo (($php_s = @php_uname('s')) ? $php_s : $info_na); ?></em></td></tr>
                <tr><td><?php echo $_language->module['server_host']; ?></td><td><em><?php echo (($php_n = @php_uname('n')) ? $php_n : $info_na); ?></em></td></tr>
                <tr><td><?php echo $_language->module['server_release']; ?></td><td><em><?php echo (($php_r = @php_uname('r')) ? $php_r : $info_na); ?></em></td></tr>
                <tr><td><?php echo $_language->module['server_version']; ?></td><td><em><?php echo (($php_v = @php_uname('v')) ? $php_v : $info_na); ?></em></td></tr>
                <tr><td><?php echo $_language->module['server_machine']; ?></td><td><em><?php echo (($php_m = @php_uname('m')) ? $php_m : $info_na); ?></em></td></tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <h4 class="mb-3">GD Graphics Library</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th><?php echo $_language->module['property']; ?></th>
                    <th><?php echo $_language->module['value']; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td>GD Graphics Library</td><td><em><?php echo $get_gd_info; ?></em></td></tr>
                <tr><td><?php echo $_language->module['supported_types']; ?></td><td><em><?php echo $get_gdtypes; ?></em></td></tr>
                <tr><td>GD Lib <?php echo $_language->module['version']; ?></td><td><em><?php echo $gdinfo['GD Version']; ?></em></td></tr>
            </tbody>
        </table>

        <h4 class="mb-3"><?php echo $_language->module['interface']; ?></h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th><?php echo $_language->module['property']; ?></th>
                    <th><?php echo $_language->module['value']; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td><?php echo $_language->module['server_api']; ?></td><td><em><?php echo php_sapi_name(); ?></em></td></tr>
                <tr><td><?php echo $_language->module['apache']; ?></td><td><em><?php if(function_exists("apache_get_version")) echo apache_get_version(); else echo $_language->module['na']; ?></em></td></tr>
                <tr><td><?php echo $_language->module['apache_modules']; ?></td><td><em><?php if(function_exists("apache_get_modules")){if(count(apache_get_modules()) > 1) $get_apache_modules = implode(", ", apache_get_modules()); echo $get_apache_modules;} else{ echo $_language->module['na'];} ?></em></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- PHP Settings -->
<div class="row">
    <div class="col-md-12">
        <h4 class="mb-3"></i> <?php echo $_language->module['php_settings']; ?></h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th><?php echo $_language->module['property']; ?></th>
                    <th><?php echo $_language->module['value']; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <?php echo $_language->module['legend']; ?>:
                        <span class="text-success"><?php echo $_language->module['green']; ?>:</span> <?php echo $_language->module['setting_ok']; ?> -
                        <span class="text-warning"><?php echo $_language->module['orange']; ?>:</span> <?php echo $_language->module['setting_notice']; ?> -
                        <span class="text-danger"><?php echo $_language->module['red']; ?>:</span> <?php echo $_language->module['setting_error']; ?>
                    </td>
                </tr>
                <tr><td>php.ini <?php echo $_language->module['path']; ?></td><td><em><?php echo $get_phpini_path; ?></em></td></tr>
                <tr><td>Allow URL fopen</td><td><em><?php echo $get_allow_url_fopen; ?></em></td></tr>
                <tr><td>Allow URL Include</td><td><em><?php echo $get_allow_url_include; ?></em></td></tr>
                <tr><td>Display Errors</td><td><em><?php echo $get_display_errors; ?></em></td></tr>
                <tr><td>Error Log</td><td><em><?php echo $get_log_errors; ?></em></td></tr>
                <tr><td>File Uploads</td><td><em><?php echo $get_file_uploads; ?></em></td></tr>
                <tr><td>max. Execution Time</td><td><em><?php echo $get_max_execution_time; ?></em></td></tr>
                <tr><td>Open Basedir</td><td><em><?php echo $get_open_basedir; ?></em></td></tr>
                <tr><td>max. Upload (Filesize)</td><td><em><?php echo $get_upload_max_filesize; ?></em></td></tr>
                <tr><td>Post max Size</td><td><em><?php echo $get_post_max_size; ?></em></td></tr>
                <tr><td>Register Globals</td><td><em><?php echo $get_register_globals; ?></em></td></tr>
                <tr><td>Short Open Tag</td><td><em><?php echo $get_short_open_tag; ?></em></td></tr>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
</div>
