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

if (isset($_GET['new_lang'])) {
    // Prüfen, ob die Sprachdatei existiert
    if (file_exists('languages/' . $_GET['new_lang'])) {
        $lang = preg_replace("[^a-z]", "", $_GET['new_lang']);
        $_SESSION['language'] = $lang;

        // Sprache auch in der Datenbank speichern, falls der Benutzer eingeloggt ist
        if ($userID) {
            safe_query("UPDATE `users` SET `language` = '" . $lang . "' WHERE `userID` = '" . $userID . "'");
        }
    }

    // Weiterleitung zur gewünschten Seite nach Sprachänderung
    if (isset($_GET['query'])) {
        $query = rawurldecode($_GET['query']);
        header("Location: ./" . $query);
    } else {
        header("Location: index.php");
    }

} else {
    $_language->readModule('index');

    $filepath = "languages/";
    $langs = [];

    // Verfügbare Sprachverzeichnisse einlesen
    if ($dh = opendir($filepath)) {
        while ($file = mb_substr(readdir($dh), 0, 2)) {
            if ($file != "." && $file != ".." && is_dir($filepath . $file)) {
                if (isset($mysql_langs[$file])) {
                    $name = ucfirst($mysql_langs[$file]);
                    $langs[$name] = $file;
                } else {
                    $langs[$file] = $file;
                }
            }
        }
        closedir($dh);
    }

    // Sprachen alphabetisch sortieren
    $sortMode = defined("SORT_NATURAL") ? SORT_NATURAL : SORT_LOCALE_STRING;
    ksort($langs, $sortMode);

    // Aktueller Pfad für Rückleitung nach Sprachwechsel
    $querystring = '';
    if ($modRewrite === true) {
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

    // Sprachlinks vorbereiten
    $index_langs = ['de', 'en', 'it'];
    foreach ($index_langs as $short) {
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM `settings` WHERE `" . $short . "_lang` = '1'"));
        ${$short . '_languages'} = ($dx[$short . '_lang'] ?? '0') == '1'
            ? '<a href="index.php?new_lang=' . $short . $querystring . '" data-toggle="tooltip" title="' . $index_language[$short] . '"><img class="flag" src="images/languages/' . $short . '.png" alt="' . $short . '">' . $index_language[$short] . '</a>'
            : '';
    }

    // Aktuelle Sprache mit grünem Haken markieren
    if ($userID) {
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM `users` WHERE `userID` = '" . $userID . "'"));
        $lang = $dx['language'];
    } else {
        global $lang;
    }

    $flag = '<img class="flag" src="images/languages/' . $lang . '.png" alt="' . $lang . '">';
    $lang_ok = '<a href="index.php?new_lang=' . $lang . $querystring . '" data-toggle="tooltip" title="' . $index_language[$lang] . '"><img class="flag" src="images/languages/' . $lang . '.png" alt="' . $lang . '">' . $index_language[$lang] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';

    // Template-Daten vorbereiten
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

        $template = $tpl->loadTemplate("navigation", "languages", $data_array);
        echo $template;
    } else {
        echo "";
    }
}
