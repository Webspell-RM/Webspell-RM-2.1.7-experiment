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

global $_language, $_database;

$_language->readModule('index');

// Sprache wechseln über URL
if (isset($_GET['new_lang'])) {
    $lang = preg_replace("/[^a-z]/", "", $_GET['new_lang']); // nur Buchstaben erlauben

    // Existiert Sprachordner?
    if (file_exists('languages/' . $lang)) {
        $_SESSION['language'] = $lang;

        // Sprache in DB speichern (wenn eingeloggt)
        if (isset($userID) && $userID > 0) {
            safe_query("UPDATE `users` SET `language` = '" . $lang . "' WHERE `userID` = '" . intval($userID) . "'");
        }
    }

    // Weiterleitung
    if (isset($_GET['query'])) {
        $query = rawurldecode($_GET['query']);
        header("Location: ./" . $query);
    } else {
        header("Location: index.php");
    }

    exit();
}

// Sprache aus Session oder DB laden
$lang = 'de'; // Standard

if (isset($_SESSION['language'])) {
    $lang = $_SESSION['language'];
} elseif (isset($userID) && $userID > 0) {
    $result = mysqli_fetch_array(safe_query("SELECT `language` FROM `users` WHERE `userID` = '" . intval($userID) . "'"));
    if (!empty($result['language'])) {
        $lang = $result['language'];
    }
}

// Sprachlinks vorbereiten
$filepath = "languages/";
$langs = [];
$index_language = [
    'de' => 'Deutsch',
    'en' => 'English',
    'it' => 'Italiano'
];

if ($dh = opendir($filepath)) {
    while (($file = readdir($dh)) !== false) {
        if (strlen($file) == 2 && is_dir($filepath . $file)) {
            $langs[ucfirst($file)] = $file;
        }
    }
    closedir($dh);
}

ksort($langs, defined("SORT_NATURAL") ? SORT_NATURAL : SORT_LOCALE_STRING);

// Rücksprung-URL vorbereiten
$querystring = '';
if (defined('modRewrite') && modRewrite === true) {
    $path = rawurlencode(str_replace($GLOBALS['rewriteBase'], '', $_SERVER['REQUEST_URI']));
} else {
    $path = rawurlencode($_SERVER['QUERY_STRING']);
    if (!empty($path)) {
        $path = "?" . $path;
    }
}
if (!empty($path)) {
    $querystring = "&amp;query=" . $path;
}

// Sprachlinks erzeugen
$de_languages = $en_languages = $it_languages = '';
$index_langs = ['de', 'en', 'it'];

foreach ($index_langs as $short) {
    $enabled = mysqli_fetch_array(safe_query("SELECT * FROM `settings` WHERE `" . $short . "_lang` = '1'"));
    if ($enabled[$short . '_lang'] == '1') {
        ${$short . '_languages'} = '<a class="dropdown-item" href="index.php?new_lang=' . $short . $querystring . '" data-toggle="tooltip" title="' . $index_language[$short] . '"><img class="flag" src="images/languages/' . $short . '.png" alt="' . $short . '">&nbsp;' . $index_language[$short] . '</a>';
    }
}

// Aktuelle Sprache anzeigen mit Haken
$language_name = isset($index_language[$lang]) ? $index_language[$lang] : ucfirst($lang);
$flag = '<img class="flag" src="images/languages/' . $lang . '.png" alt="' . $lang . '">';
$lang_ok = '<a class="dropdown-item" href="index.php?new_lang=' . $lang . $querystring . '" data-toggle="tooltip" title="' . $language_name . '">

<img class="flag" src="images/languages/' . $lang . '.png" alt="' . $lang . '">&nbsp;' . $language_name . ' <i class="bi bi-check2 text-success" style="font-size: 1rem;"></i></a>';

// Template einfügen
$dx = mysqli_fetch_array(safe_query("SELECT * FROM `settings` WHERE `de_lang` = '1' OR `en_lang` = '1' OR `it_lang` = '1'"));

if (
    (isset($dx['de_lang']) && $dx['de_lang'] == '1') ||
    (isset($dx['en_lang']) && $dx['en_lang'] == '1') ||
    (isset($dx['it_lang']) && $dx['it_lang'] == '1')
) {
    $data_array = [
        'flag' => $flag,
        'de_languages_ok' => $lang_ok,
        'de_languages' => $de_languages,
        'en_languages' => $en_languages,
        'it_languages' => $it_languages,
    ];

    echo $tpl->loadTemplate("navigation", "languages", $data_array, 'theme');
}
