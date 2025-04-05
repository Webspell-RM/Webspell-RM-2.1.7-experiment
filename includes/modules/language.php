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

if (isset($_GET[ 'new_lang' ])) {
    if (file_exists('languages/' . $_GET[ 'new_lang' ])) {

        $lang = preg_replace("[^a-z]", "", $_GET[ 'new_lang' ]);
        $_SESSION[ 'language' ] = $lang;

        if ($userID) {
            safe_query("UPDATE " . PREFIX . "user SET language='" . $lang . "' WHERE userID='" . $userID . "'");
        }
    }

    if (isset($_GET[ 'query' ])) {
        $query = rawurldecode($_GET[ 'query' ]);
        header("Location: ./" . $query);        
    } else {
        header("Location: index.php");
    }
} else {
    $_language->readModule('index');

    $filepath = "languages/";
    $langs = array();    

    if ($dh = opendir($filepath)) {
        while ($file = mb_substr(readdir($dh), 0, 2)) {
            if ($file != "." && $file != ".." && is_dir($filepath . $file)) {
                if (isset($mysql_langs[ $file ])) {
                    $name = $mysql_langs[ $file ];
                    $name = ucfirst($name);
                    $langs[ $name ] = $file;
                } else {
                    $langs[ $file ] = $file;
                }
            }
        }
        closedir($dh);
    }
    if (defined("SORT_NATURAL")) {
        $sortMode = SORT_NATURAL;
    } else {
        $sortMode = SORT_LOCALE_STRING;
    }
    ksort($langs, $sortMode);

    $querystring = '';
    if ($modRewrite === true) {
        $path = rawurlencode(str_replace($GLOBALS[ 'rewriteBase' ], '', $_SERVER[ 'REQUEST_URI' ]));

    } else {
        $path = rawurlencode($_SERVER[ 'QUERY_STRING' ]);
        if (!empty($path)) {
            $path = "?".$path;
        }
    }
    if (!empty($path)) {
        $querystring = "&amp;query=" . $path;
    }        

    foreach ($langs as $lang => $flag) {
    }

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE de_lang='1'"));
    if (@$dx[ 'de_lang' ] != '1') {
        $de_languages = '';
        $de_button = '';
    } else {
        $de_languages = '<a href="index.php?new_lang=de'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'de' ] . '"><img class="flag" src="images/languages/de.png" alt="de">' . $index_language[ 'de' ] . '</a>';        
        
    };

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE en_lang='1'"));
    if (@$dx[ 'en_lang' ] != '1') {
        $en_languages = '';
        $en_button = '';
    } else {
        $en_languages = '<a href="index.php?new_lang=en'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'en' ] . '"><img class="flag" src="images/languages/en.png" alt="en">' . $index_language[ 'en' ] . '</a>';
        
    };

    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE it_lang='1'"));
    if (@$dx[ 'it_lang' ] != '1') {
        $it_languages = '';
        $it_button = '';
    } else {
        $it_languages = '<a href="index.php?new_lang=it'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'it' ] . '"><img class="flag" src="images/languages/it.png" alt="it">' . $index_language[ 'it' ] . '</a>';
        
    };  


    if ($userID) {
        $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "user WHERE userID='" . $userID . "'"));


        $lang=$dx['language']; 


        if($lang == 'de'){
            $de_languages_ok = '<a href="index.php?new_lang=de'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'de' ] . '"><img class="flag" src="images/languages/de.png" alt="de">' . $index_language[ 'de' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/de.png" alt="de">';
        } elseif ($lang == 'en') {
            $de_languages_ok = '<a href="index.php?new_lang=en'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'en' ] . '"><img class="flag" src="images/languages/en.png" alt="en">' . $index_language[ 'en' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/en.png" alt="de">';
        } else {
            $de_languages_ok = '<a href="index.php?new_lang=it'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'it' ] . '"><img class="flag" src="images/languages/it.png" alt="it">' . $index_language[ 'it' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/it.png" alt="de">';
        }


    }else{
        global $lang;

        if($lang == "de"){
            $de_languages_ok = '<a href="index.php?new_lang=de'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'de' ] . '"><img class="flag" src="images/languages/de.png" alt="de">' . $index_language[ 'de' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/de.png" alt="de">';
        } elseif ($lang == 'en') {
            $de_languages_ok = '<a href="index.php?new_lang=en'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'en' ] . '"><img class="flag" src="images/languages/en.png" alt="en">' . $index_language[ 'en' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/en.png" alt="de">';
        } else {
            $de_languages_ok = '<a href="index.php?new_lang=it'. $querystring . '" data-toggle="tooltip" title="' . $index_language[ 'it' ] . '"><img class="flag" src="images/languages/it.png" alt="it">' . $index_language[ 'it' ] . ' <i class="bi bi-check2 text-success" style="font-size: 2rem;"></i></a>';
            $flag='<img class="flag" src="images/languages/it.png" alt="de">';
        }

        
    }


    $dx = mysqli_fetch_array(safe_query("SELECT * FROM " . PREFIX . "settings WHERE de_lang='1' OR en_lang='1' OR it_lang='1'"));

    if (isset($dx['de_lang']) && $dx['de_lang'] == '1' || isset($dx['en_lang']) && $dx['en_lang'] == '1' || isset($dx['it_lang']) && $dx['it_lang'] == '1') {
        // Sprachen, die aktiv sind, einfügen
        $data_array = [
            'flag' => $flag,
            'de_languages_ok' => $de_languages_ok,
            'de_languages' => $de_languages,
            'en_languages' => $en_languages,
            'it_languages' => $it_languages,
        ];

        // Template mit den Sprachdaten laden und ausgeben
        $template = $tpl->loadTemplate("navigation", "languages", $data_array);
        echo $template;
    } else {
        // Keine der Sprachen ist aktiv - könnte hier eine Default-Anzeige oder Logik hinzugefügt werden
        echo "";
    }

}