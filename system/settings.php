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

// -- SYSTEM ERROR DISPLAY -- //
include('error.php');
ini_set('display_errors', 1);

// -- PHP FUNCTION CHECK -- //

if (!function_exists('mb_substr')) {
    system_error('PHP Multibyte String Support is not enabled.', 0);
}

// -- ERROR REPORTING -- //
define('DEBUG', "ON"); // ON = development-mode | OFF = public mode
if (DEBUG === 'ON') {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

// -- SET ENCODING FOR MB-FUNCTIONS -- //

mb_internal_encoding("UTF-8");

// -- SET INCLUDE-PATH FOR vendors --//

 $path = __DIR__.DIRECTORY_SEPARATOR.'components';
 set_include_path(get_include_path() . PATH_SEPARATOR .$path);

// -- SET HTTP ENCODING -- //

header('content-type: text/html; charset=utf-8');

// -- INSTALL CHECK -- //

if (DEBUG == "OFF" && file_exists('install/index.php')) {
    system_error(
        'The install-folder exists. Did you run the <a href="install/">Installer</a>?<br>
        If yes, please remove the install-folder.',
        0
    );
}

// -- CONNECTION TO MYSQL -- //
if (!isset($GLOBALS[ '_database' ])) {
    $_database = @new mysqli($host, $user, $pwd, $db);

    if ($_database->connect_error) {
        system_error('Cannot connect to MySQL-Server');
    }

    $_database->query("SET NAMES 'utf8mb4'");
    $_database->query("SET sql_mode = ''");
}


// -- GENERAL PROTECTIONS -- //

if (function_exists("globalskiller") === false) {
    function globalskiller()
    {
        // Kills all non-system variables
        $global = array(
            'GLOBALS',
            '_POST',
            '_GET',
            '_COOKIE',
            '_FILES',
            '_SERVER',
            '_ENV',
            '_REQUEST',
            '_SESSION',
            '_database'
        );

        // Durchlaufe alle globalen Variablen
        foreach ($GLOBALS as $key => $val) {
            // Überprüfe, ob der Schlüssel nicht zu den systemrelevanten Variablen gehört
            if (!in_array($key, $global)) {
                // Lösche die Variable, falls sie kein Array ist
                if (is_array($val)) {
                    unset($GLOBALS[$key]); // Lösche Arrays
                } else {
                    unset($GLOBALS[$key]); // Lösche nicht-Array Variablen
                }
            }
        }
    }
}

if (function_exists("unset_array") === false) {
    function unset_array($array)
    {
        foreach ($array as $key) {
            if (is_array($key)) {
                unset_array($key);
            } else {
                unset($key);
            }
        }
    }
}

globalskiller();

if (isset($_GET[ 'site' ])) {
    $site = $_GET[ 'site' ];
} else {
    $site = null;
}
if ($site != "search") {
    $request = strtolower(urldecode($_SERVER[ 'QUERY_STRING' ]));
    $protarray = array(
        "union",
        "select",
        "into",
        "where",
        "update ",
        "from",
        "/*",
        "set ",
        PREFIX . "user ",
        PREFIX . "user(",
        PREFIX . "user`",
        PREFIX . "user_groups",
        "phpinfo",
        "escapeshellarg",
        "exec",
        "fopen",
        "fwrite",
        "escapeshellcmd",
        "passthru",
        "proc_close",
        "proc_get_status",
        "proc_nice",
        "proc_open",
        "proc_terminate",
        "shell_exec",
        "system",
        "telnet",
        "ssh",
        "cmd",
        "mv",
        "chmod",
        "chdir",
        "locate",
        "killall",
        "passwd",
        "kill",
        "script",
        "bash",
        "perl",
        "mysql",
        "~root",
        ".history",
        "~nobody",
        "getenv"
    );
    $check = str_replace($protarray, '*', $request);
    if ($request != $check) {
        system_error("Invalid request detected.");
    }
}

function security_slashes(&$array)
{

    global $_database;

    foreach ($array as $key => $value) {
        if (is_array($array[ $key ])) {
            security_slashes($array[ $key ]);
        } else {
            $tmp = $value;
            if (function_exists("mysqli_real_escape_string")) {
                $array[ $key ] = $_database->escape_string($tmp);
            } else {
                $array[ $key ] = addslashes($tmp);
            }
            unset($tmp);
        }
    }
}

security_slashes($_POST);
security_slashes($_COOKIE);
security_slashes($_GET);
security_slashes($_REQUEST);

// -- ESCAPE QUERY FUNCTION FOR TABLE -- //
function escapestring($mquery) {
    global $_database;
    
    if (function_exists("mysqli_real_escape_string")) {
        $mquery = $_database->escape_string($mquery);
    } else {
        $mquery = addslashes($mquery);
    }
    return $mquery;
}

function mysqli_fetch_assocss($mquery) {
if(isset($mquery)){
$putquery = '0';
} else {
$putquery = mysqli_fetch_assoc($mquery);
}

return $putquery;
print_r($putquery);

}

// -- MYSQL QUERY FUNCTION -- //
$_mysql_querys = array();
function safe_query($query = "")
{

    global $_database;
    global $_mysql_querys;
    $_database->query("SET sql_mode = ''");

    if (stristr(str_replace(' ', '', $query), "unionselect") === false and
        stristr(str_replace(' ', '', $query), "union(select") === false
    ) {
        $_mysql_querys[ ] = $query;
        if (empty($query)) {
            return false;
        }
        if (DEBUG == "OFF") {
            $result = $_database->query($query) or system_error('Query failed!');
        } else {
            $result = $_database->query($query) or
            system_error(
                '<strong>Query failed</strong> ' . '<ul>' .
                '<li>MySQL error no.: <mark>' . $_database->errno . '</mark></li>' .
                '<li>MySQL error: <mark>' . $_database->error . '</mark></li>' .
                '<li>SQL: <mark>' . $query . '</mark></li>'.
                '</ul>',
                1,
                1
            );
        }
        return $result;
echo $result;
    } else {
        die();
    }
}

// -- SYSTEM FILE INCLUDE -- //

function systeminc($file) {
    if (!include('system/' . $file . '.php')) {
        if (DEBUG == "OFF") {
            system_error('Could not get system file for <mark>' . $file . '</mark>');
        } else {
            system_error('Could not get system file for <mark>' . $file . '</mark>', 1, 1);
        }
    }
}

// -- GLOBAL SETTINGS -- //
$headlines = '';

// Führen Sie die Abfrage aus
$result = safe_query("SELECT * FROM " . PREFIX . "settings_expansion WHERE active = '1'");

// Fehlerbehandlung für das Abfrageergebnis
if ($result && mysqli_num_rows($result) > 0) {
    // Hole die erste Zeile des Ergebnisses als assoziatives Array
    $dx = mysqli_fetch_assoc($result);

    // Überprüfe, ob die Felder existieren und setze Standardwerte, falls nicht
    $font_family = isset($dx['body1']) ? $dx['body1'] : 'default-font'; // Fallback für Schriftart
    $headlines = isset($dx['headlines']) ? $dx['headlines'] : 'default-headline'; // Fallback für Headlines
} else {
    // Fehlerbehandlung, wenn keine Daten gefunden wurden
    // Hier kannst du Fehler melden oder Standardwerte verwenden
    $font_family = 'default-font';
    $headlines = 'default-headline';
}

// CSS- und JS-Dateien
$components = array(
    'css' => array(
        './components/bootstrap/css/bootstrap.min.css',
        './components/bootstrap/css/bootstrap-icons.min.css',
        './components/scrolltotop/css/scrolltotop.css',
        './components/datatables/css/jquery.dataTables.min.css',
        './components/ckeditor/plugins/codesnippet/lib/highlight/styles/school_book_output.css',
        './components/css/styles.css.php',
        './components/css/animate.css',
        './components/css/page.css',
        './components/css/passtrength.css',
        './components/css/' . htmlspecialchars($headlines) . '', // Sicherheitsmaßnahme: htmlspecialchars
        './components/fonts/fonts_' . htmlspecialchars($font_family) . '.css' // Sicherheitsmaßnahme: htmlspecialchars
    ),
    'js' => array(
        './components/jquery/jquery.min.js',
        './components/bootstrap/js/bootstrap.bundle.min.js',
        './components/scrolltotop/js/scrolltotop.js',
        './components/datatables/js/jquery.dataTables.js',
        './components/js/bbcode.js',
        './components/js/index.js',
        './components/js/jquery.easing.min.js',
        './components/js/passtrength.js',
        './components/js/slick.min.js',
        './components/js/enchanter.js'
    )
);

// Funktion zum Prüfen, ob die Dateien existieren (CSS und JS)
function check_file_exists($file)
{
    return file_exists($file) ? $file : '';
}

// Dateien nur hinzufügen, wenn sie existieren
$valid_css = array_filter($components['css'], 'check_file_exists');
$valid_js = array_filter($components['js'], 'check_file_exists');

// Hier kannst du dann das $valid_css und $valid_js weiterverwenden

$ds = mysqli_fetch_array(
    safe_query("SELECT * FROM `" . PREFIX . "settings`")
);

$maxlatesttopics = $ds[ 'latesttopics' ];
if (empty($maxlatesttopics)) {
    $maxlatesttopics = 10;
}
$maxlatesttopicchars = $ds[ 'latesttopicchars' ];
if (empty($maxlatesttopicchars)) {
    $maxlatesttopicchars = 18;
}
$maxtopics = $ds[ 'topics' ];
if (empty($maxtopics)) {
    $maxtopics = 20;
}
$maxposts = $ds[ 'posts' ];
if (empty($maxposts)) {
    $maxposts = 10;
}
$maxsball = $ds[ 'sball' ];
if (empty($maxsball)) {
    $maxsball = 5;
}
$maxmessages = $ds[ 'messages' ];
if (empty($maxmessages)) {
    $maxmessages = 5;
}
$hp_url = $ds[ 'hpurl' ];
$hp_title = stripslashes($ds[ 'title' ]);
$register_per_ip = $ds[ 'register_per_ip' ];
$admin_name = $ds[ 'adminname' ];
$admin_email = $ds[ 'adminemail' ];
$myclantag = $ds[ 'clantag' ];
$myclanname = $ds[ 'clanname' ];

$keywords = $ds[ 'keywords' ];
$description = $ds[ 'description'];


$sessionduration = $ds[ 'sessionduration' ];
if (empty($sessionduration)) {
    $sessionduration = 24;
}
$closed = (int)$ds[ 'closed' ];
$imprint_type = $ds[ 'imprint' ];

$default_language = $ds[ 'default_language' ];
if (empty($default_language)) {
    $default_language = 'en';
}
$rss_default_language = $ds[ 'default_language' ];
if (empty($rss_default_language)) {
    $rss_default_language = 'en';
}
$max_wrong_pw = $ds[ 'max_wrong_pw' ];
if (empty($max_wrong_pw)) {
    $max_wrong_pw = 3;
}
$lastBanCheck = $ds[ 'bancheck' ];
$insertlinks = $ds[ 'insertlinks' ];
$autoDetectLanguage = (int)$ds[ 'detect_language' ];
$spamCheckMaxPosts = $ds[ 'spammaxposts' ];
if (empty($spamCheckMaxPosts)) {
    $spamCheckMaxPosts = 30;
}
$spamCheckEnabled = (int)$ds[ 'spam_check' ];
$spamCheckRating = 0.95;
$default_format_date = $ds[ 'date_format' ];
if (empty($default_format_date)) {
    $default_format_date = 'd.m.Y';
}
$default_format_time = $ds[ 'time_format' ];
if (empty($default_format_time)) {
    $default_format_time = 'H:i';
}
$search_min_len = $ds[ 'search_min_len' ];
if (empty($search_min_len)) {
    $search_min_len = '4';
}
$modRewrite = (bool)$ds[ 'modRewrite' ];
if (empty($modRewrite)) {
    $modRewrite = false;
}

$new_chmod = 0666;

// -- LOGO -- //

// Logo-Abfrage
$dx = safe_query("SELECT * FROM " . PREFIX . "settings_themes WHERE active = '1'");

// Fehlerbehandlung für die Logo-Abfrage
if ($dx && mysqli_num_rows($dx) > 0) {
    $ds = mysqli_fetch_assoc($dx);
    $logo = isset($ds['logo']) ? $ds['logo'] : 'default_logo.png'; // Fallback-Wert
} else {
    // Fehlerbehandlung, wenn keine Daten für Logo gefunden wurden
    $logo = 'default_logo.png'; // Setze Standardlogo, wenn nichts gefunden wurde
}

// Erweiterte Einstellungen - partners
$row = safe_query("SELECT * FROM " . PREFIX . "settings_expansion WHERE active = '1'");

// Fehlerbehandlung für Erweiterungsabfrage
if ($row && mysqli_num_rows($row) > 0) {
    while ($ds = mysqli_fetch_assoc($row)) {
        // Annahme: Nur das letzte `pfad` wird benötigt
        $theme_name = isset($ds['pfad']) ? $ds['pfad'] : 'default_theme'; // Fallback-Wert
    }
} else {
    // Fehlerbehandlung, wenn keine Daten für Erweiterungen gefunden wurden
    $theme_name = 'default_theme'; // Setze Standardtheme, wenn nichts gefunden wurde
}

// Abfrage für Partneranzahl
$tmp = safe_query("SELECT count(themeID) as cnt FROM " . PREFIX . "settings_expansion");

// Fehlerbehandlung für Partneranzahl
if ($tmp && mysqli_num_rows($tmp) > 0) {
    $tmp_data = mysqli_fetch_assoc($tmp);
    $anzpartners = isset($tmp_data['cnt']) ? $tmp_data['cnt'] : 0; // Fallback auf 0, wenn keine Partner gefunden
} else {
    // Fehlerbehandlung, wenn keine Partneranzahl gefunden wurde
    $anzpartners = 0; // Setze Standardwert auf 0
}
