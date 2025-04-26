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

// Funktion zur Erkennung der aktuellen Sprache
function detectCurrentLanguage() {
    // Datenbankabfrage zum Abrufen der Standard-Sprache aus der Einstellungen-Tabelle
    $res = safe_query("SELECT `default_language` FROM `settings` WHERE 1");
    $rox = mysqli_fetch_array($res);

    // Überprüfen, ob die Sprache in der Session gesetzt ist
    if (isset($_SESSION['language'])) {
        $lng = $_SESSION['language'];
    } 
    // Falls die Sprache nicht in der Session gesetzt ist, auf den Standardwert aus den Einstellungen zurückgreifen
    elseif (isset($rox['default_language'])) {
        $lng = $rox['default_language'];
    } 
    // Wenn keine Sprache gesetzt ist, Standard auf Englisch setzen
    else {
        $lng = "en";
    }

    // Rückgabe der ermittelten Sprache
    return $lng;
}

// Funktion zur sicheren Ausgabe von Variablen
function show_var($var) {
    // Überprüfen, ob die Variable ein Skalarwert (z.B. String, Integer) ist
    if (is_scalar($var)) {
        return $var;
    } else {
        // Wenn es kein Skalarwert ist, wird die Variable zurückgegeben
        return $var;
    }
}


// Funktion zum Laden von CSS- und JS-Dateien aus einem Template-Verzeichnis
function headfiles($var, $path) {
    $css = "";
    $js = "\n";
    
    // Überprüfung für den Fall, dass CSS-Dateien geladen werden sollen
    switch ($var) {
        case "css":
            // Überprüfen, ob das Verzeichnis für CSS-Dateien existiert
            if (is_dir($path . "css/")) { 
                $subf = "css/"; 
            } else { 
                $subf = ""; 
            }
            
            // Array für die CSS-Dateien
            $f = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path . $subf) . '*.css');
            $fc = count($f, COUNT_RECURSIVE);  // Zählen der CSS-Dateien

            // Wenn CSS-Dateien gefunden wurden, diese hinzufügen
            if ($fc > 0) {
                for ($b = 0; $b <= $fc - 2; $b++) {
                    $css .= '<link type="text/css" rel="stylesheet" href="./' . $f[$b] . '" />' . chr(0x0D) . chr(0x0A);
                }
            }
            return $css;
            break;

        // Überprüfung für den Fall, dass JS-Dateien geladen werden sollen
        case "js":
            // Überprüfen, ob das Verzeichnis für JS-Dateien existiert
            if (is_dir($path . "js/")) { 
                $subf2 = "js/"; 
            } else { 
                $subf2 = ""; 
            }
            
            // Array für die JS-Dateien
            $g = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path . $subf2) . '*.js');
            $fc = count($g, COUNT_RECURSIVE);  // Zählen der JS-Dateien

            // Wenn JS-Dateien gefunden wurden, diese hinzufügen
            if ($fc > 0) {
                for ($d = 0; $d <= $fc - 2; $d++) {
                    $js .= '<script src="./' . $g[$d] . '"></script>' . chr(0x0D) . chr(0x0A);
                }
            }
            return $js;
            break;

        // Standardfall für ungültige Parameter
        default:
            return "<!-- invalid parameter, use 'css', 'js' or 'components' -->";
    }
}

// -- LOGIN SESSION -- //

// Prüfen, ob die Datei 'session.php' existiert und einbinden
if (file_exists('session.php')) {
    systeminc('session');
} else {
    systeminc('../system/session');
}

// Prüfen, ob die Datei 'ip.php' existiert und einbinden
if (file_exists('ip.php')) {
    systeminc('ip');
} else {
    systeminc('../system/ip');
}


// -- GLOBAL WEBSPELL FUNCTIONS -- //
/*
function makepagelink($link, $page, $pages, $sub = '')
{
    $page_link = '<nav>
  <ul class="pagination pagination-sm">';

    if ($page != 1) {
        $page_link .=
        '<li><a href="' . $link . '&amp;' . $sub . 'page=1">&laquo;</a></li> <li><a href="' . $link . '&amp;' . $sub .
        'page=' . ($page - 1) . '">&lsaquo;</a></li>';
    }
    if ($page >= 6) {
        $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page - 5) . '">...</a></li>';
    }
    if ($page + 4 >= $pages) {
        $pagex = $pages;
    } else {
        $pagex = $page + 4;
    }
    for ($i = $page - 4; $i <= $pagex; $i++) {
        if ($i <= 0) {
            $i = 1;
        }
        if ($i == $page) {
            $page_link .= '<li class="active"><a href="#" aria-label="Previous"><span aria-hidden="true">' . $i . '</span></a></li>';
        } else {
            $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . $i . '">' . $i . '</a></li>';
        }
    }
    if (($pages - $page) >= 5) {
        $page_link .= '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page + 5) . '">...</a></li>';
    }
    if ($page != $pages) {
        $page_link .=
        '<li><a href="' . $link . '&amp;' . $sub . 'page=' . ($page + 1) . '">&rsaquo;</a>&nbsp;<a href="' .
        $link . '&amp;' . $sub . 'page=' . $pages . '">&raquo;</a></li>';
    }
    $page_link .= '</ul></nav>';

    return $page_link;
}

// Text Kürzen
function str_break($str, $maxlen)
{
    $nobr = 0;
    $str_br = '';
    $len = mb_strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($str, $i, 1);
        if (($char != ' ') && ($char != '-') && ($char != "\n")) {
            $nobr++;
        } else {
            $nobr = 0;
            if ($maxlen + $i > $len) {
                $str_br .= mb_substr($str, $i);
                break;
            }
        }
        if ($nobr > $maxlen) {
            $str_br .= '- ' . $char;
            $nobr = 1;
        } else {
            $str_br .= $char;
        }
    }
    return $str_br;
}*/

// Funktion zur Zählung des Vorkommens eines Substrings in einem mehrdimensionalen Array
function substri_count_array($haystack, $needle)
{
    $return = 0;

    // Durchlaufe jedes Element im Array
    foreach ($haystack as $value) {
        // Falls das Element selbst ein Array ist, rekursiv die Funktion aufrufen
        if (is_array($value)) {
            $return += substri_count_array($value, $needle);
        } else {
            // Andernfalls, den Substring zählen, dabei Groß-/Kleinschreibung ignorieren
            $return += substr_count(strtolower($value), strtolower($needle));
        }
    }

    return $return;
}

// Funktion zur Ersetzung von Zeichen in einem String für den sicheren Gebrauch in JavaScript
function js_replace($string)
{
    // Ersetze Rückwärtsschrägstriche
    $output = preg_replace("/(\\\)/si", '\\\\\1', $string);

    // Ersetze bestimmte Zeichen durch ihre escape-codierten Entsprechungen
    $output = str_replace(
        array("\r\n", "\n", "'", "<script>", "</script>", "<noscript>", "</noscript>"),
        array("\\n", "\\n", "\'", "\\x3Cscript\\x3E", "\\x3C/script\\x3E", "\\x3Cnoscript\\x3E", "\\x3C/noscript\\x3E"),
        $output
    );

    return $output;
}

// Funktion zur Berechnung des Prozentsatzes
function percent($sub, $total, $dec = 2)
{
    // Überprüfe, ob $sub und $total numerisch sind und $total nicht null ist
    if (!is_numeric($sub) || !is_numeric($total) || $total == 0) {
        return 0; // Verhindere Divisionen durch null und ungültige Eingabewerte
    }

    // Berechne den Prozentsatz
    $perc = ($sub / $total) * 100;

    // Runde den Prozentsatz auf die angegebene Dezimalstellenanzahl
    return round($perc, $dec);
}



// Funktion, die eine Seite im Wartungsmodus anzeigt
function showlock($reason)
{
    // Holen des Seitentitels aus der Datenbank
    $gettitle = mysqli_fetch_array(safe_query("SELECT `title` FROM `settings`"));
    $pagetitle = $gettitle['title'];
    
    // Erstellen eines Datenarrays, um den Seitentitel und andere Variablen zu speichern
    $data_array = array();
    $data_array['$pagetitle'] = $pagetitle;

    // Prüfen, ob mod_rewrite aktiviert ist und die RewriteBase holen
    if (isset($GLOBALS['_modRewrite']) && $GLOBALS['_modRewrite']->enabled()) {
        $data_array['$rewriteBase'] = $GLOBALS['_modRewrite']->getRewriteBase();
    } else {
        $data_array['$rewriteBase'] = '';
    }

    // Hinzufügen des Grundes für den Wartungsmodus
    $data_array['$reason'] = $reason;

    // Einbinden der Lock-Seite
    include("./includes/modules/lock.php");

    // Das Skript stoppen, damit keine weiteren Ausgaben erfolgen
    die();
}

// Funktion zur Überprüfung von Systemumgebungsvariablen
function checkenv($systemvar, $checkfor)
{
    // Überprüft, ob der Wert der Systemumgebungsvariable $systemvar den String $checkfor enthält
    return stristr(ini_get($systemvar), $checkfor);
}

// Funktion zur Verschlüsselung einer E-Mail-Adresse, um sie vor Spam-Bots zu schützen
function mail_protect($mailaddress)
{
    // Sicherstellen, dass die E-Mail-Adresse nicht leer ist
    if (empty($mailaddress)) {
        return '';
    }

    // Initialisierung der Variablen zur Speicherung der verschlüsselten E-Mail
    $protected_mail = "";

    // Umwandeln der E-Mail-Adresse in ein Array von ASCII-Werten
    $arr = unpack("C*", $mailaddress);

    // Durchlaufen jedes Werts im Array und Umwandlung in hexadezimale Form
    foreach ($arr as $entry) {
        // Hexadezimale Darstellung jedes Zeichens
        $protected_mail .= sprintf("%%%X", $entry);
    }

    // Rückgabe der verschlüsselten E-Mail-Adresse
    return $protected_mail;
}

// zum Prüfen
#echo mail_protect("example@example.com");

// Funktion zur Überprüfung, ob eine URL gültig ist
function validate_url($url)
{
    // Regulärer Ausdruck zur Validierung einer URL
    return preg_match(
        // @codingStandardsIgnoreStart
        "/^(ht|f)tps?:\/\/([^:@]+:[^:@]+@)?(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+(:[0-9]{2,5})?(\/[^#\?]*(\?[^#\?]*)?(#.*)?)?$/sui",
        // @codingStandardsIgnoreEnd
        $url
    );
}

// Funktion zur Überprüfung, ob eine E-Mail-Adresse gültig ist
function validate_email($email)
{
    // Regulärer Ausdruck zur Validierung einer E-Mail-Adresse
    return preg_match(
        // @codingStandardsIgnoreStart
        "/^(?!\.)(\.?[\p{L}0-9!#\$%&'\*\+\/=\?^_`\{\|}~-]+)+@(?!\.)(\.?(?!-)[0-9\p{L}-]+(?<!-))+\.[\p{L}0-9]{2,}$/sui",
        // @codingStandardsIgnoreEnd
        $email
    );
}


// Funktion zur Kombination von zwei Arrays, wenn `array_combine` nicht existiert
if (!function_exists('array_combine')) {
    /**
     * Kombiniert zwei Arrays in ein assoziatives Array, wobei das erste Array die Schlüssel und das zweite die Werte darstellt.
     *
     * @param array $keyarray  Ein Array von Schlüsseln.
     * @param array $valuearray Ein Array von Werten.
     * @return array Ein assoziatives Array, das die Schlüssel aus `$keyarray` und die Werte aus `$valuearray` kombiniert.
     */
    function array_combine($keyarray, $valuearray)
    {
        // Arrays für die Schlüssel und Werte initialisieren
        $keys = array();
        $values = array();
        $result = array();

        // Schlüssel aus dem ersten Array extrahieren
        foreach ($keyarray as $key) {
            $keys[] = $key;
        }

        // Werte aus dem zweiten Array extrahieren
        foreach ($valuearray as $value) {
            $values[] = $value;
        }

        // Kombination der Schlüssel und Werte in ein assoziatives Array
        foreach ($keys as $access => $resultkey) {
            $result[$resultkey] = $values[$access];
        }

        // Das resultierende assoziative Array zurückgeben
        return $result;
    }
}

// Funktion zur sicheren Vergleich von zwei Hash-Werten, wenn `hash_equals` nicht existiert
if (!function_exists("hash_equals")) {
    /**
     * Vergleicht zwei Strings sicher, ohne Timing-Angriffe zu ermöglichen.
     *
     * @param string $known_str Der bekannte String, der mit dem Benutzer-String verglichen wird.
     * @param string $user_str Der vom Benutzer bereitgestellte String.
     * @return bool `true`, wenn die Strings gleich sind, andernfalls `false`.
     */
    function hash_equals($known_str, $user_str)
    {
        $result = 0;

        // Sicherstellen, dass beide Parameter Strings sind
        if (!is_string($known_str)) {
            return false;
        }

        if (!is_string($user_str)) {
            return false;
        }

        // Überprüfen, ob die Länge der beiden Strings übereinstimmt
        if (strlen($known_str) != strlen($user_str)) {
            return false;
        }

        // Bitweise XOR-Operation, um die Unterschiede zwischen den Zeichen zu finden
        for ($j = 0; $j < strlen($known_str); $j++) {
            $result |= ord($known_str[$j]) ^ ord($user_str[$j]);
        }

        // Wenn keine Unterschiede vorliegen, sind die Strings gleich
        return $result === 0;
    }
}


/**
 * Zählt die leeren Variablen in einem Array.
 *
 * Diese Funktion prüft rekursiv jedes Element im übergebenen Array. 
 * Wenn das Element leer ist (nachdem es getrimmt wurde), wird es gezählt.
 *
 * @param array $checkarray Das Array, das auf leere Variablen überprüft werden soll.
 * @return int Die Anzahl der leeren Variablen im Array.
 */
function countempty($checkarray)
{
    $ret = 0;

    // Iteration über jedes Element im Array
    foreach ($checkarray as $value) {
        // Wenn das Element ein Array ist, rekursive Zählung aufrufen
        if (is_array($value)) {
            $ret += countempty($value);
        }
        // Wenn das Element leer ist (nach Trim) erhöhen wir den Zähler
        elseif (trim($value) == "") {
            $ret++;
        }
    }

    return $ret;
}

/**
 * Überprüft, ob bestimmte Request-Variablen leer sind.
 *
 * Diese Funktion prüft, ob alle übergebenen Request-Variablen leer sind.
 * Wenn eine der Variablen leer ist, gibt sie `false` zurück, andernfalls `true`.
 *
 * @param array $valuearray Ein Array von Request-Variablen, die überprüft werden sollen.
 * @return bool `true`, wenn keine leeren Variablen gefunden wurden, andernfalls `false`.
 */
function checkforempty($valuearray)
{
    $check = array();

    // Extrahiert die Werte der angegebenen Request-Variablen
    foreach ($valuearray as $value) {
        // Füge den Wert der jeweiligen Request-Variable in das Array hinzu
        $check[] = $_REQUEST[$value];
    }

    // Überprüft, ob es leere Variablen gibt
    if (countempty($check) > 0) {
        return false;
    }

    return true;
}


// -- DATE-TIME INFORMATION -- //
// Einbinden der Datums- und Zeit-Funktionen, je nach Dateipfad
if (file_exists('func/datetime.php')) {
    systeminc('func/datetime');
} else {
    systeminc('../system/func/datetime');
}

// -- USER INFORMATION -- //
// Einbinden der Benutzerinformations-Funktionen
if (file_exists('func/user.php')) {
    systeminc('func/user');
} else {
    systeminc('../system/func/user');
}

// -- ACCESS INFORMATION -- //
// Einbinden der Zugriffssteuerungs-Funktionen
#if(file_exists('func/useraccess.php')) { systeminc('func/useraccess'); } else { systeminc('../system/func/useraccess'); }
if (file_exists('func/access_control.php')) {
    systeminc('func/access_control');
} else {
    systeminc('../system/func/access_control');
}

if (file_exists('func/check_access.php')) {
    systeminc('func/check_access');
} else {
    systeminc('../system/func/check_access');
}
// # Admin-Check wird derzeit nicht eingebunden
#if(file_exists('func/admincheck.php')) { systeminc('func/admincheck'); } else { systeminc('../system/func/admincheck'); }

// -- MESSENGER INFORMATION -- //
// Einbinden der Messenger-Funktionen
if (file_exists('func/messenger.php')) {
    systeminc('func/messenger');
} else {
    systeminc('../system/func/messenger');
}

// -- GAME INFORMATION -- //
// Einbinden der Spiel-Funktionen
if (file_exists('func/game.php')) {
    systeminc('func/game');
} else {
    systeminc('../system/func/game');
}

// -- Page INFORMATION -- //
// Einbinden der Seiten-Funktionen
if (file_exists('func/page.php')) {
    systeminc('func/page');
} else {
    systeminc('../system/func/page');
}

// -- CAPTCHA -- //
// Einbinden der CAPTCHA-Funktionen
if (file_exists('func/captcha.php')) {
    systeminc('func/captcha');
} else {
    systeminc('../system/func/captcha');
}

// -- LANGUAGE SYSTEM -- //
// Einbinden des Sprachsystems und Setzen der Standardsprache
if (file_exists('func/language.php')) {
    systeminc('func/language');
} else {
    systeminc('../system/func/language');
}

$_language = new \webspell\Language;
$_language->setLanguage($default_language);

// -- TEMPLATE SYSTEM -- //
// Einbinden des Template-Systems
if (file_exists('func/template.php')) {
    systeminc('func/template');
} else {
    systeminc('../system/func/template');
}

// -- PLUGIN SERVICE -- //
// Einbinden des Plugin-Service
if (file_exists('func/plugin_service.php')) {
    systeminc('func/plugin_service');
} else {
    systeminc('../system/func/plugin_service');
}



// Erstellen des Template-Objekts, je nachdem, ob es sich um das Admin-Verzeichnis handelt
if (!stristr($_SERVER['SCRIPT_NAME'], '/admin/')) {
    $_template = new \Webspell\Template();
} else {
    $_template = new \Webspell\Template('../templates/');
}

// -- SPAM -- //
// Einbinden der Spam-Funktionen
if (file_exists('func/spam.php')) {
    systeminc('func/spam');
} else {
    systeminc('../system/func/spam');
}

// -- Tags -- //
// Einbinden der Tags-Funktionen
if (file_exists('func/tags.php')) {
    systeminc('func/tags');
} else {
    systeminc('../system/func/tags');
}

// -- Upload -- //
// Einbinden der Upload-Funktionen
if (file_exists('func/upload.php')) {
    systeminc('func/upload');
} else {
    systeminc('../system/func/upload');
}

if (file_exists('func/httpupload.php')) {
    systeminc('func/httpupload');
} else {
    systeminc('../system/func/httpupload');
}

if (file_exists('func/urlupload.php')) {
    systeminc('func/urlupload');
} else {
    systeminc('../system/func/urlupload');
}

// -- Mod Rewrite -- //
// Einbinden des ModRewrite-Systems
if (file_exists('modrewrite.php')) {
    systeminc('modrewrite');
} else {
    systeminc('../system/modrewrite');
}

// -- INDEX CONTENT -- //
// Einbinden des Inhalts für die Startseite
if (file_exists('content.php')) {
    systeminc('content');
} else {
    systeminc('../system/content');
}

// -- INSTALL BASE -- //
// Einbinden der Installations-Basisklasse
if (file_exists('func/install_base.php')) {
    systeminc('func/install_base');
} else {
    systeminc('../system/func/install_base');
}



if (file_exists('func/login_check.php')) {
    systeminc('func/login_check');
} else {
    systeminc('../system/func/login_check');
}


// Für Login unf Rollen
if (file_exists('classes/login_security.php')) {
    systeminc('classes/login_security');
} else {
    systeminc('../system/classes/login_security');
}

if (file_exists('classes/role_manager.php')) {
    systeminc('classes/role_manager');
} else {
    systeminc('../system/classes/role_manager');
}

if (file_exists('classes/plugin_manager.php')) {
    systeminc('classes/plugin_manager');
} else {
    systeminc('../system/classes/plugin_manager');
}
// ModRewrite-Objekt initialisieren und aktivieren
$GLOBALS['_modRewrite'] = new \webspell\ModRewrite();
if (!stristr($_SERVER['SCRIPT_NAME'], '/admin/') && $modRewrite) {
    $GLOBALS['_modRewrite']->enable();
}

/**
 * Bereinigt den Text, indem HTML-Tags entfernt und Sonderzeichen umgewandelt werden.
 *
 * @param string $text Der zu bereinigende Text.
 * @param bool $bbcode Optional, wenn wahr wird BBCode beibehalten.
 * @param string $calledfrom Der Ursprung des Aufrufs (Standard ist 'root').
 * @return string Der bereinigte Text.
 */
function cleartext($text, $bbcode = true, $calledfrom = 'root')
{
    $text = htmlspecialchars($text);  // Wandelt Sonderzeichen in HTML-Entities um
    $text = strip_tags($text);        // Entfernt HTML-Tags
    $text = nl2br($text);             // Wandelt neue Zeilen in <br> um

    return $text;
}

/**
 * Bereinigt die Eingabe aus einer normalen Anfrage.
 *
 * @param string $text Der zu bereinigende Text.
 * @return string Der bereinigte Text.
 */
function getinput($text)
{
    @$text = htmlspecialchars($text); // Wandelt Sonderzeichen in HTML-Entities um

    return $text;
}

/**
 * Bereinigt die Eingabe aus einem Formular.
 *
 * @param string $text Der zu bereinigende Text.
 * @return string Der bereinigte Text.
 */
function getforminput($text)
{
    $text = str_replace(array('\r', '\n'), array("\r", "\n"), $text); // Zeilenumbrüche richtig konvertieren
    $text = stripslashes($text); // Entfernt Escape-Zeichen
    $text = htmlspecialchars($text); // Wandelt Sonderzeichen in HTML-Entities um

    return $text;
}


// -- LOGIN -- //
// Überprüft, ob die Login-Datei existiert, und bindet sie ein
/*if (file_exists('login.php')) {
    systeminc('login');
} else {
    systeminc('../system/login');
}*/

// Sprachwahl aus Cookie, Session oder automatische Erkennung
if (isset($_COOKIE['language'])) {
    $_language->setLanguage($_COOKIE['language']);
} elseif (isset($_SESSION['language'])) {
    $_language->setLanguage($_SESSION['language']);
} elseif ($autoDetectLanguage) {
    $lang = detectUserLanguage();
    if (!empty($lang)) {
        $_language->setLanguage($lang);
        $_SESSION['language'] = $lang;
    }
}

// -- SITE VARIABLE -- //
// Setzt die Site-Variable aus der URL-Abfrage
if (isset($_GET['site'])) {
    $site = $_GET['site'];
} else {
    $site = '';
}

// Prüft, ob das Forum geschlossen ist und ob der Benutzer ein Admin ist
if ($closed && !isanyadmin($userID)) {
    $dl = mysqli_fetch_array(safe_query("SELECT * FROM lock LIMIT 0,1"));
    $reason = $dl['reason'];
    showlock($reason);
}

// Setzt Standardwerte für HTTP_REFERER und REQUEST_URI
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = "";
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

// -- BANNED USERS -- //
// Überprüft alle Benutzer auf Bannstatus und entfernt abgelaufene Banns
if (date("dh", $lastBanCheck) != date("dh")) {
    $get = safe_query("SELECT userID, banned FROM users WHERE banned IS NOT NULL");
    $removeBan = array();
    while ($ds = mysqli_fetch_assoc($get)) {
        if ($ds['banned'] != "perm") {
            if ($ds['banned'] <= time()) {
                $removeBan[] = 'userID="' . $ds['userID'] . '"';
            }
        }
    }
    if (!empty($removeBan)) {
        $where = implode(" OR ", $removeBan);
        safe_query("UPDATE users SET banned=NULL WHERE " . $where);
    }
    safe_query("UPDATE settings SET bancheck='" . time() . "'");
}

// Prüft, ob der Benutzer oder seine IP gesperrt ist, und löscht die Sitzung bei Bann
/*$banned = safe_query("SELECT userID, banned, ban_reason FROM users WHERE (userID='" . $userID . "' OR ip='" . $GLOBALS['ip'] . "') AND banned IS NOT NULL");
while ($bq = mysqli_fetch_array($banned)) {
    if ($bq['ban_reason']) {
        $reason = '<div class="alert alert-warning" role="alert"><br>Grund / Reason: <br>' . $bq['ban_reason'] . '"</div>';
    } else {
        $reason = '';
    }
    if ($bq['banned']) {
        $_SESSION = array();

        // Entfernt das Session-Cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        session_destroy();

        // Entfernt das Login-Cookie
        webspell\LoginCookie::clear('ws_auth');
        system_error('<div class="alert alert-warning" role="alert"><strong>Du wurdest gebannt!<br>You have been banned!</strong></div>' . $reason, 0);
    }
}*/

// -- BANNED IPs -- //
// Löscht abgelaufene Einträge in der Tabelle für gesperrte IPs
safe_query("DELETE FROM banned_ips WHERE deltime < '" . time() . "'");

// -- HELP MODE -- //
// Überprüft, ob die Hilfeseite existiert und bindet sie ein
if (file_exists('help.php')) {
    systeminc('help');
} else {
    systeminc('../system/help');
}

// -- UPDATE LAST LOGIN -- //
// Wenn die Seite gesetzt ist und der Benutzer angemeldet ist, wird das letzte Login-Datum aktualisiert
/*if ($site) {
    if ($userID) {
        safe_query("UPDATE users SET lastlogin='" . time() . "' WHERE userID='" . $userID . "'");
    }
}*/

// =======================
// WHO IS / WAS ONLINE
// =======================
function whouseronline() {
    global $site, $userID;

    $site = $site ?: 'startpage';

    $timeout = 5; // Minuten
    $deltime = time() - ($timeout * 60);
    $wasdeltime = time() - (60 * 60 * 24); // 24 Stunden

    safe_query("DELETE FROM `whoisonline` WHERE `time` < '$deltime'");
    safe_query("DELETE FROM `whowasonline` WHERE `time` < '$wasdeltime'");

    if ($userID) {
        // Aktiver User
        $check = safe_query("SELECT `userID` FROM `whoisonline` WHERE `userID` = '$userID'");
        if (mysqli_num_rows($check)) {
            safe_query("UPDATE `whoisonline` SET `time` = '" . time() . "', `site` = '$site' WHERE `userID` = '$userID'");
        } else {
            safe_query("INSERT INTO `whoisonline` (`time`, `userID`, `site`) VALUES ('" . time() . "', '$userID', '$site')");
        }

        $check = safe_query("SELECT `userID` FROM `whowasonline` WHERE `userID` = '$userID'");
        if (mysqli_num_rows($check)) {
            safe_query("UPDATE `whowasonline` SET `time` = '" . time() . "', `site` = '$site' WHERE `userID` = '$userID'");
        } else {
            safe_query("INSERT INTO `whowasonline` (`time`, `userID`, `site`) VALUES ('" . time() . "', '$userID', '$site')");
        }
    } else {
        // Gast
        $ip = $GLOBALS['ip'];
        $check = safe_query("SELECT `ip` FROM `whoisonline` WHERE `ip` = '$ip'");
        if (mysqli_num_rows($check)) {
            safe_query("UPDATE `whoisonline` SET `time` = '" . time() . "', `site` = '$site' WHERE `ip` = '$ip'");
        } else {
            safe_query("INSERT INTO `whoisonline` (`time`, `ip`, `site`) VALUES ('" . time() . "', '$ip', '$site')");
        }
    }
}

// =======================
// COUNTER
// =======================
/*$time = time();
$date = date("d.m.Y", $time);
$deltime = $time - (3600 * 24);

safe_query("DELETE FROM `counter_iplist` WHERE `del` < '$deltime'");

$ip = $GLOBALS['ip'];
if (!mysqli_num_rows(safe_query("SELECT `ip` FROM `counter_iplist` WHERE `ip` = '$ip'"))) {
    if ($userID) {
        safe_query("UPDATE `users` SET `ip` = '$ip' WHERE `userID` = '$userID'");
    }

    safe_query("UPDATE `counter` SET `hits` = `hits` + 1");
    safe_query("INSERT INTO `counter_iplist` (`dates`, `del`, `ip`) VALUES ('$date', '$time', '$ip')");

    if (!mysqli_num_rows(safe_query("SELECT `dates` FROM `counter_stats` WHERE `dates` = '$date'"))) {
        safe_query("INSERT INTO `counter_stats` (`dates`, `count`) VALUES ('$date', '1')");
    } else {
        safe_query("UPDATE `counter_stats` SET `count` = `count` + 1 WHERE `dates` = '$date'");
    }
}
*/
// Update maxonline
$res = mysqli_fetch_assoc(safe_query("SELECT COUNT(*) as maxuser FROM `whoisonline`"));
safe_query("UPDATE `counter` SET `maxonline` = '" . $res['maxuser'] . "' WHERE `maxonline` < '" . $res['maxuser'] . "'");

// =======================
// SEO / PAGE TITLE
// =======================
if (stristr($_SERVER['PHP_SELF'], "/admin/") === false) {
    if (file_exists('seo.php')) {
        systeminc('seo');
    } else {
        systeminc('../system/seo');
    }
    define('PAGETITLE', getPageTitle());
} else {
    define('PAGETITLE', $GLOBALS['hp_title']);
}

// =======================
// RSS FEEDS
// =======================
if (file_exists('func/feeds.php')) {
    systeminc('func/feeds');
} else {
    systeminc('../system/func/feeds');
}

// =======================
// EMAIL
// =======================
if (file_exists('func/email.php')) {
    systeminc('src/func/email');
} else {
    systeminc('../system/func/email');
}

// =======================
// DIRECTORY CLEANUP
// =======================
function recursiveRemoveDirectory($directory)
{
    foreach (glob("{$directory}/*") as $file) {
        is_dir($file) ? recursiveRemoveDirectory($file) : unlink($file);
    }
    @rmdir($directory);
}

// =======================
// URL / PROTOKOLL HELPER
// =======================
function getCurrentUrl() {
    return ((empty($_SERVER['HTTPS'])) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function httpprotokollsetzen($string) {
    if (stristr($string, 'https://') === false) {
        return "http://$string";
    } else {
        return "https://$string";
    }
}

function httpprotokoll($string) {
    if (strpos($string, 'https://') === 0) {
        return 'https://';
    } elseif (strpos($string, 'http://') === 0) {
        return 'http://';
    } else {
        return 'https://'; // Fallback
    }
}

// =======================
// FORUM GROUP CHECK
// =======================
function usergrpexists($fgrID) {
    return (mysqli_num_rows(safe_query("SELECT `fgrID` FROM `plugins_forum_groups` WHERE `fgrID` = " . (int)$fgrID)) > 0);
}

// =======================
// TABLE EXISTENCE CHECK
// =======================
function tableExists($table) {
    $result = safe_query("SHOW TABLES LIKE '" . $table . "'");
    return $result && mysqli_num_rows($result) > 0;
}
