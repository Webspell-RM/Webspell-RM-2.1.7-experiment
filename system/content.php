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
 * @copyright       2018-2024 by webspell-rm.de                                                              *
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

#Funktionen für die index.php (/includes/themes/default/)

// Funktion zur Ausgabe des Seitentitels
function get_sitetitle()
{
    // Neues Plugin-Manager-Objekt erstellen
    $sitetitle = new plugin_manager();
    
    // Überprüfen, ob der 'site'-Parameter in der URL gesetzt ist
    if (isset($_GET['site'])) {
        // Überprüfen, ob das Plugin den Titel für diese Seite aktualisiert
        $updated_title = $sitetitle->plugin_updatetitle($_GET['site']);
        
        if ($updated_title) {
            // Wenn das Plugin den Titel geändert hat, diesen Titel ausgeben
            echo $updated_title;
        } else {
            // Wenn das Plugin keinen Titel geändert hat, den Standardtitel ausgeben
            echo PAGETITLE;
        }
    } else {
        // Wenn der 'site'-Parameter nicht gesetzt ist, den Standardtitel ausgeben
        echo PAGETITLE;
    }
}

# content Ausgabe für die index.php
function get_mainContent()
{
    global $cookievalue, $userID, $date, $loggedin, $_language, $tpl, $myclanname, $hp_url, $imprint_type, $admin_email, $admin_name;
    global $maxtopics, $plugin_path, $maxposts, $page, $action, $preview, $message, $topicID, $_database, $maxmessages, $new_chmod;
    global $hp_title, $default_format_date, $default_format_time, $register_per_ip, $rewriteBase;

    // Holen der Website-Einstellungen
    $settings = safe_query("SELECT * FROM " . PREFIX . "settings");
    if (!$settings) {
        system_error("Fehler beim Abrufen der Einstellungen.");
    }
    $ds = mysqli_fetch_array($settings);

    // Bestimmen der gewünschten Seite
    $site = isset($_GET['site']) ? getinput($_GET['site']) : $ds['startpage'];

    // Bereinigen des Seitennamens von ungültigen Zeichen
    $invalide = array('\\', '/', '/\/', ':', '.');
    $site = str_replace($invalide, ' ', $site);

    // Prüfen, ob es sich um ein Plugin handelt
    $_plugin = new plugin_manager();
    $_plugin->set_debug(DEBUG);
    if (!empty($site) && $_plugin->is_plugin($site) > 0) {
        $data = $_plugin->plugin_data($site);
        $plugin_path = !empty($data['path']) ? $data['path'] : '';
        $check = $_plugin->plugin_check($data, $site);
        
        if ($check['status'] == 1) {
            $inc = $check['data'];
            if ($inc == "exit") {
                $site = "404";
            }
            include($check['data']);
        } else {
            echo $check['data'];
        }
    } else {
        // Wenn es keine Plugin-Seite ist, prüfe auf bestehende Module
        if (!file_exists("includes/modules/" . $site . ".php")) {
            $site = "404"; // Wenn die Seite nicht existiert, zeige 404
        }
        include("includes/modules/" . $site . ".php");
    }
}



// Funktion zum Registrieren eines Widgets für verschiedene Bereiche
function register_widget_module($widget_name) {
    $widget_menu = new widgets();
    $widget_menu->registerWidget($widget_name);
}

// Header Modul
function get_header_modul() {
    register_widget_module("header_widget");
}

// Navigations Modul
function get_navigation_modul() {
    global $logo, $theme_name, $themes, $site, $_language, $loggedin, $url;
    global $modulname;
    register_widget_module("navigation_widget");
}

// Content Head Modul
function get_content_head_modul() {
    register_widget_module("content_head_widget");
}

// Content Above Center Modul
function get_content_up_modul() {
    register_widget_module("content_up_widget");
}

//#################################################
// Hilfsfunktion für die Ausgabe der Seitenmodule
// Ausgabe Left Side
#Ausgabe Left Side
function get_left_side_modul()
{
    $qs_arr = array();
    parse_str($_SERVER['QUERY_STRING'], $qs_arr);

    // Standardwert für 'getsite'
    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : 'startpage';

    // Liste der Seiten, bei denen kein Widget angezeigt wird
    $noWidgetPages = [
        'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile', 'error_404', 
        'report', 'static', 'loginoverview', 'register', 'lostpassword', 'login', 
        'logout', 'footer', 'navigation', 'topbar', 'articles_comments', 'blog_comments', 
        'gallery_comments', 'news_comments', 'news_recomments', 'polls_comments', 
        'videos_comments'
    ];

    // Wenn die Seite in der Liste der Seiten ohne Widgets ist, tue nichts
    if (in_array($getsite, $noWidgetPages)) {
        return;
    }

    // Überprüfe für 'forum_topic' und andere Seiten
    $tableName = ($getsite === 'forum_topic') 
        ? PREFIX . "plugins_forum_settings_widgets" 
        : PREFIX . "plugins_" . $getsite . "_settings_widgets";
    
    // Hole die Widget-Daten aus der entsprechenden Tabelle
    $dx = mysqli_fetch_array(safe_query("SELECT * FROM $tableName WHERE position='left_side_widget' OR position = 'full_activated'"));
    
    // Wenn die Position 'left_side_widget' oder 'full_activated' ist, zeige das Widget an
    if (isset($dx['position']) && ($dx['position'] == 'left_side_widget' || $dx['position'] == 'full_activated')) {
        echo '<div id="leftcol" class="col-md-3">';
        $left_page = $widget_menu = new widgets();
        $widget_menu->registerWidget("left_side_widget");
        $widget_menu->registerWidget("full_activated");
        echo '</div>';
    }
}


#Ausgabe Right Side
function get_right_side_modul()
{
    $qs_arr = array();
    parse_str($_SERVER['QUERY_STRING'], $qs_arr);

    // Standardwert für 'getsite'
    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : 'startpage';

    // Liste der Seiten, bei denen kein Widget angezeigt wird
    $noWidgetPages = [
        'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile', 'error_404', 
        'report', 'static', 'loginoverview', 'register', 'lostpassword', 'login', 
        'logout', 'footer', 'navigation', 'topbar', 'articles_comments', 'blog_comments', 
        'gallery_comments', 'news_comments', 'news_recomments', 'polls_comments', 
        'videos_comments'
    ];

    // Wenn die Seite in der Liste der Seiten ohne Widgets ist, tue nichts
    if (in_array($getsite, $noWidgetPages)) {
        return;
    }

    // Überprüfe für 'forum_topic' und andere Seiten
    $tableName = ($getsite === 'forum_topic') 
        ? PREFIX . "plugins_forum_settings_widgets" 
        : PREFIX . "plugins_" . $getsite . "_settings_widgets";
    
    // Hole die Widget-Daten aus der entsprechenden Tabelle
    $dx = mysqli_fetch_array(safe_query("SELECT * FROM $tableName WHERE position='right_side_widget' OR position = 'full_activated'"));
    
    // Wenn die Position 'right_side_widget' oder 'full_activated' ist, zeige das Widget an
    if (isset($dx['position']) && ($dx['position'] == 'right_side_widget' || $dx['position'] == 'full_activated')) {
        echo '<div id="rightcol" class="col-md-3">';
        $right_page = $widget_menu = new widgets();
        $widget_menu->registerWidget("right_side_widget");
        $widget_menu->registerWidget("full_activated");
        echo '</div>';
    }
}

//#############################################

// Content Below Center Modul
function get_content_down_modul() {
    register_widget_module("content_down_widget");
}

// Content Foot Modul
function get_content_foot_modul() {
    register_widget_module("content_foot_widget");
}

// Footer Modul
function get_footer_modul() {
    register_widget_module("footer_widget");
}

#Wartungsmodus wird anezeigt
function get_lock_modul()
{
    global $closed, $_database; // Sicherstellen, dass $_database (Datenbankverbindung) verfügbar ist

    $query = safe_query("SELECT closed FROM " . PREFIX . "settings WHERE closed='1'");
    if ($query && mysqli_num_rows($query) > 0) {
        $dm = mysqli_fetch_assoc($query);
        
        if (!isset($closed) || $closed != '1') {
            return; // Falls $closed nicht '1' ist, wird die Funktion einfach beendet
        }

        echo '<div class="alert alert-danger" role="alert" style="margin-bottom: -5px;">
            <center>Die Seite befindet sich im Wartungsmodus | The site is in maintenance mode | Il sito è in modalità manutenzione</center>
        </div>';
    }
}

#ckeditor Quelltext Button wird angezeigt für Superadmin
function get_editor()
{
    global $userID;

    // Überprüfen, ob die Funktion 'issuperadmin' existiert
    if (!function_exists('issuperadmin')) {
        // Falls nicht, Fehlermeldung ausgeben oder eine Standardkonfiguration verwenden
        echo '<p>Error: Superadmin function is not defined.</p>';
        return;
    }

    // Laden des CKEditors
    echo '<script src="./components/ckeditor/ckeditor.js"></script>';

    // Prüfen, ob der Benutzer ein Superadmin ist und die entsprechende Konfiguration laden
    if (issuperadmin($userID)) {
        echo '<script src="./components/ckeditor/config.js"></script>';
    } else {
        echo '<script src="./components/ckeditor/user_config.js"></script>';
    }
}


