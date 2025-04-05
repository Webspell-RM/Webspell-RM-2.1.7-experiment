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

global $modRewrite;
if ($modRewrite && !empty($GLOBALS['site']))
	$_SERVER['QUERY_STRING'] = 'site=' . $GLOBALS['site'];
elseif ($modRewrite && empty($GLOBALS['site']))
	$_SERVER['QUERY_STRING'] = 'site=startpage';


class plugin_manager
{
	var $_debug;

	//@debug 		if debug mode ON show failure messages otherwise hide this
	function set_debug($var)
	{
		$this->_debug = $var;
	}

	//@info prüft, ob eine Plugin-Index-Link-Datei vorhanden ist, die aufgerufen werden kann
	// index.php?site=xxx
	function is_plugin($var)
	{
	    global $_database; // Verwendung der globalen $_database-Verbindung
	    try {
	        // Vorbereitete SQL-Abfrage zur Vermeidung von SQL-Injektionen
	        $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins WHERE `activate` = '1' AND `index_link` LIKE ?");
	        if ($stmt) {
	            // Bindet den Parameter (index_link) an die vorbereitete Abfrage
	            $searchVar = "%" . $var . "%"; // Der Platzhalter für LIKE, der das % enthält
	            $stmt->bind_param("s", $searchVar); // "s" bedeutet, dass der Parameter ein String ist
	            $stmt->execute();
	            $result = $stmt->get_result();
	            
	            // Wenn ein Ergebnis gefunden wird
	            if ($result->num_rows > 0) {
	                return 1; // Plugin existiert und ist aktiviert
	            } else {
	                return 0; // Kein Plugin gefunden
	            }
	        } else {
	            return 'Fehler bei der Vorbereitung der Datenbankabfrage.'; // Fehler bei der Vorbereitung der Abfrage
	        }
	    } catch (Exception $e) {
	        return 'Fehler: ' . $e->getMessage(); // Rückgabe einer Fehlermeldung im Falle einer Ausnahme
	    }
	}

	//@info Holen Sie sich die Plugin-Daten aus der Datenbank
	function plugin_data($var, $id = 0, $admin = false)
	{
	    global $_database; // Verwendet die globale Datenbankverbindung

	    // Wenn eine ID übergeben wurde, filtere nach dieser ID
	    if ($id > 0) {
	        $where = " WHERE `activate`='1' AND `pluginID`=?";
	        $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins " . $where);
	        $stmt->bind_param("i", $id); // Bindet die ID als Integer
	        $stmt->execute();
	        $query = $stmt->get_result();
	    } else {
	        // Je nachdem, ob es sich um eine Admin-Datei oder eine Index-Datei handelt
	        if ($admin) {
	            $where = " WHERE `activate`='1' AND `admin_file` LIKE ?";
	            $likeVar = "%" . $var . "%"; // Bereitet die LIKE-Bedingung vor
	            $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins " . $where);
	            $stmt->bind_param("s", $likeVar); // Bindet den String
	            $stmt->execute();
	            $q = $stmt->get_result();
	            if (mysqli_num_rows($q)) {
	                $tmp = mysqli_fetch_array($q);
	                $ifiles = $tmp['index_link'];
	                $tfiles = explode(",", $ifiles);
	                if (in_array($var, $tfiles)) {
	                    $where = " WHERE `pluginID`=?";
	                    $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins " . $where);
	                    $stmt->bind_param("i", $tmp['pluginID']);
	                    $stmt->execute();
	                    $query = $stmt->get_result();
	                }
	            }
	        } else {
	            // Wenn kein Admin-Modus, dann nach dem index_link filtern
	            $where = " WHERE `activate`='1' AND `index_link` LIKE ?";
	            $likeVar = "%" . $var . "%";
	            $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins " . $where);
	            $stmt->bind_param("s", $likeVar);
	            $stmt->execute();
	            $q = $stmt->get_result();
	            if (mysqli_num_rows($q)) {
	                $tmp = mysqli_fetch_array($q);
	                $ifiles = $tmp['index_link'];
	                $tfiles = explode(",", $ifiles);
	                if (in_array($var, $tfiles)) {
	                    $where = " WHERE `pluginID`=?";
	                    $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins " . $where);
	                    $stmt->bind_param("i", $tmp['pluginID']);
	                    $stmt->execute();
	                    $query = $stmt->get_result();
	                }
	            }
	        }
	    }

	    // Falls kein gültiges Ergebnis gefunden wird
	    if (!isset($query)) {
	        return false;
	    }

	    try {
	        // Wenn das Abfrageergebnis Zeilen enthält, gib die erste Zeile zurück
	        if (mysqli_num_rows($query)) {
	            $row = mysqli_fetch_array($query);
	            return $row;
	        }
	    } catch (Exception $e) {
	        // Wenn ein Fehler auftritt, gib die Fehlermeldung aus
	        return $e->getMessage();
	    }
	}


	function plugin_check($data, $site)
	{
	    $_language = new \webspell\Language; // Sprachmodul initialisieren
	    $_language->readModule('plugin'); // Lädt die Sprachdatei für Plugins
	    $return = array(); // Array für die Rückgabedaten
	    whouseronline(); // Funktion zum Überprüfen der Online-Benutzer (wird angenommen, dass sie im System existiert)
	    
	    // Überprüft, ob das Plugin aktiviert ist
	    if (isset($data['activate']) == 1) {
	        if (isset($site)) {
	            // Wenn eine Site (Seite) angegeben ist
	            $ifiles = $data['index_link']; // Liest den Index-Link des Plugins
	            $tfiles = explode(",", $ifiles); // Teilt die Links in ein Array
	            if (in_array($site, $tfiles)) { // Überprüft, ob die Site im Array der erlaubten Links enthalten ist
	                // Überprüft, ob die Datei existiert
	                if (file_exists($data['path'] . $site . ".php")) {
	                    $plugin_path = $data['path']; // Setzt den Pfad des Plugins
	                    $return['status'] = 1; // Setzt den Status auf 1 (erfolgreich)
	                    $return['data'] = $data['path'] . $site . ".php"; // Gibt den vollständigen Pfad zurück
	                    return $return;
	                } else {
	                    // Wenn die Datei nicht gefunden wurde
	                    if (DEBUG === "ON") {
	                        echo '<!-- <br /><span class="label label-danger">' . $_language->module['plugin_not_found'] . '</span> -->';
	                    }
	                    // Überprüft, ob die Standard-404-Seite existiert
	                    if (!file_exists(MODULE . $site . ".php")) {
	                        $site = "404"; // Setzt die Seite auf "404", wenn sie nicht gefunden wurde
	                    }
	                    $return['status'] = 1; // Setzt den Status auf 1 (erfolgreich)
	                    $return['data'] = MODULE . $site . ".php"; // Gibt den Standard-404-Pfad zurück
	                    return $return;
	                }
	            }
	        } else {
	            // Wenn keine Site angegeben wurde, überprüft den Standardindex-Link
	            if (file_exists($data['path'] . $data['index_link'] . ".php")) {
	                $plugin_path = $data['path']; // Setzt den Plugin-Pfad
	                $return['status'] = 1; // Setzt den Status auf 1 (erfolgreich)
	                $return['data'] = $data['path'] . $data['index_link'] . ".php"; // Gibt den Pfad zum Index-Link zurück
	                return $return;
	            } else {
	                // Wenn der Index-Link nicht gefunden wurde
	                if (DEBUG === "ON") {
	                    return '<!-- <br /><span class="label label-danger">' . $_language->module['plugin_not_found'] . '</span> -->';
	                }
	                // Überprüft, ob die Standard-404-Seite existiert
	                if (!file_exists(MODULE . $site . ".php")) {
	                    $site = "404"; // Setzt die Seite auf "404", wenn sie nicht gefunden wurde
	                }
	                $return['status'] = 1; // Setzt den Status auf 1 (erfolgreich)
	                $return['data'] = MODULE . $site . ".php"; // Gibt den Standard-404-Pfad zurück
	                return $return;
	            }
	        }
	    } else {
	        // Wenn das Plugin nicht aktiviert ist
	        if (DEBUG === "ON") {
	            echo ('<!-- <br /><span class="label label-warning">' . $_language->module['plugin_deactivated'] . '</span> -->');
	        }
	        // Wenn das Plugin deaktiviert ist, wird der Pfad der Standard-404-Seite zurückgegeben
	        if (!file_exists(MODULE . $site . ".php")) {
	            $site = "404"; // Setzt die Seite auf "404"
	        }
	        $return['status'] = 1; // Setzt den Status auf 1 (erfolgreich)
	        $return['data'] = MODULE . $site . ".php"; // Gibt den Standard-404-Pfad zurück
	        return $return;
	    }
	}


	####################################
	function plugin_widget_data($var, $id = 0, $admin = false)
	{
	    if ($id <= 0) {
	        echo 'leer';
	        return false;
	    }

	    parse_str($_SERVER['QUERY_STRING'], $qs_arr);
	    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : 'startpage';  // Default auf 'startpage', falls kein 'site' gesetzt

	    // Gültige Seiten in einem Array speichern
	    $valid_sites = [
	        'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile', 'error_404', 'report',
	        'static', 'loginoverview', 'register', 'lostpassword', 'login', 'logout', 'footer', 
	        'navigation', 'topbar', 'news_comments', 'articles_comments', 'blog_comments', 'gallery_comments',
	        'news_recomments', 'polls_comments', 'videos_comments'
	    ];

	    // Abfrage basierend auf der Seite
	    if (in_array($getsite, $valid_sites)) {
	        $query = safe_query("SELECT * FROM " . PREFIX . "settings_plugins_widget_settings WHERE id='" . intval($id) . "'");
	    } elseif ($getsite == 'forum_topic') {
	        $query = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_settings_widgets WHERE id='" . intval($id) . "'");
	    } else {
	        $query = safe_query("SELECT * FROM " . PREFIX . "plugins_" . $getsite . "_settings_widgets WHERE id='" . intval($id) . "'");
	    }

	    // Prüfen, ob das Ergebnis existiert und zurückgeben
	    if ($query && mysqli_num_rows($query) > 0) {
	        return mysqli_fetch_array($query);
	    }

	    return false;
	}



	// @info      Überprüft, ob das Plugin aktiviert ist und existiert. 
	//            Wenn ja, wird die sc_file aus dem Plugin-Verzeichnis eingebunden.
	//            Wenn nicht, wird dieses Plugin nicht geladen.

	// Verwendung von safe_query mit der richtigen globalen Datenbankverbindung
	// Aktualisierte plugin_widget Funktion mit vorbereitetem Statement, um SQL-Injektionen zu vermeiden
	function plugin_widget($id, $name = false, $css = false)
	{
	    global $_database; // Verwendet die globale $_database Verbindung
	    $pid = intval($id); // Wandelt die ID in eine Ganzzahl um
	    $_language = new \webspell\Language; // Initialisiert die Sprachübersetzungs-Instanz
	    $_language->readModule('plugin'); // Lädt die Sprachdatei für Plugins
	    if (!empty($pid)) { // Wenn die ID nicht leer ist
	        $manager = new plugin_manager(); // Erstellen des Plugin-Managers
	        $row = $manager->plugin_widget_data("", $pid); // Ruft die Plugin-Daten ab

	        // Prepared Statement, um SQL-Injektionen zu verhindern
	        $stmt = $_database->prepare("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname = ?");
	        if ($stmt) {
	            // Bindet den Parameter an das vorbereitete Statement
	            $stmt->bind_param("s", $row['modulname']); // "s" bedeutet, dass der Parameter ein String ist
	            $stmt->execute(); // Führt das Statement aus
	            $result = $stmt->get_result(); // Holt das Ergebnis der Abfrage
	            $ds = $result->fetch_array(); // Wandelt das Ergebnis in ein Array um

	            // Überprüft, ob das Plugin aktiviert ist
	            if (isset($ds['activate']) && $ds['activate'] != "1") {
	                if ($this->_debug === "ON") {
	                    return ''; // Kein Fehler, aber nichts anzeigen, wenn Debugging aktiviert ist
	                }
	                return false; // Plugin ist nicht aktiviert, also wird false zurückgegeben
	            }

	            // Überprüft, ob der Pfad des Plugins und der Name der Widget-Datei gesetzt sind
	            if (isset($ds['path']) && isset($row['widgetdatei'])) {
	                $pluginFilePath = $ds['path'] . $row['widgetdatei'] . ".php"; // Setzt den vollständigen Pfad zur Plugin-Datei
	                
	                if (file_exists($pluginFilePath)) { // Überprüft, ob die Datei existiert
	                    $plugin_path = $ds['path']; // Setzt den Plugin-Pfad, falls er später verwendet wird
	                    require_once($pluginFilePath); // Lädt die Plugin-Datei, nur einmalig
	                    return false; // Erfolgreiches Laden des Plugins
	                } else {
	                    // Wenn die Datei nicht existiert, wird eine Fehlermeldung ausgegeben, falls Debugging aktiviert ist
	                    if ($this->_debug === "ON") {
	                        return ('<span class="label label-danger">' . $_language->module['plugin_not_found'] . ' - ' . htmlspecialchars($pluginFilePath) . '</span>');
	                    }
	                    return false; // Wenn Debugging nicht aktiviert ist, wird keine Ausgabe gemacht
	                }
	            } else {
	                // Wenn der Pfad oder der Dateiname nicht gesetzt ist, wird eine Fehlermeldung ausgegeben, falls Debugging aktiviert ist
	                if ($this->_debug === "ON") {
	                    return ('<span class="label label-danger">Plugin-Dateipfad oder Dateiname fehlt.</span>');
	                }
	                return false; // Rückgabe von false, wenn der Pfad oder Dateiname fehlt
	            }
	        } else {
	            // Wenn das vorbereitete Statement nicht erstellt werden konnte, wird eine Fehlermeldung angezeigt
	            if ($this->_debug === "ON") {
	                return ('<span class="label label-danger">Datenbankabfrage konnte nicht vorbereitet werden.</span>');
	            }
	            return false; // Rückgabe von false, wenn das Statement nicht erstellt werden konnte
	        }
	    }
	}


	#################################################	
	//@info		search a plugin by name and return the ID
	function pluginID_by_name($name)
	{
	    // Sicherstellen, dass der Name als sicherer String behandelt wird
	    $name = mysqli_real_escape_string($GLOBALS['mysqli'], $name);

	    // Abfrage mit Platzhaltern (Prepared Statements) statt direkter Einfügung
	    $query = "SELECT pluginID FROM `" . PREFIX . "settings_plugins` WHERE `activate` = 1 AND `name` LIKE ?";
	    $stmt = $GLOBALS['mysqli']->prepare($query);
	    $search_name = '%' . $name . '%';
	    $stmt->bind_param('s', $search_name);  // 's' bedeutet, dass der Parameter ein String ist

	    if ($stmt->execute()) {
	        $result = $stmt->get_result();
	        if ($result && $result->num_rows > 0) {
	            $tmp = $result->fetch_array(MYSQLI_ASSOC);
	            return $tmp['pluginID'];  // Rückgabe der pluginID
	        }
	    }

	    return 0;  // Rückgabe 0, wenn kein passendes Plugin gefunden wurde
	}


	//@info		include a file which saved in hiddenfiles
	function plugin_hf($id, $name)
	{
		$pid = intval($id);
		$_language = new \webspell\Language;
		$_language->readModule('plugin');
		if (!empty($pid) and !empty($name)) {
			$manager = new plugin_manager();
			$row = $manager->plugin_data("", $pid);
			$hfiles = $row['hiddenfiles'];
			$tfiles = explode(",", $hfiles);
			if (in_array($name, $tfiles)) {
				if (file_exists($row['path'] . $name . ".php")) {
					$plugin_path = $row['path'];
					require_once($row['path'] . $name . ".php");
					return false;
				} else {
					if ($this->_debug === "ON") {
						return ('<span class="label label-danger">' . $_language->module['plugin_not_found'] . '</span>');
					}
				}
			}
		}
	}

	//@info 		get the plugin directories from database and check 
	//				if in any plugin (direct) or in the subfolders (css & js)
	//				are file which must load into the <head> Tag
	function plugin_loadheadfile_css($pluginadmin = false)
	{
	    global $_database; // Zugriff auf die globale $_database-Verbindung

	    // Hole die Einstellungen
	    $settingsQuery = safe_query("SELECT * FROM " . PREFIX . "settings");
	    $settings = mysqli_fetch_array($settingsQuery);
	    
	    parse_str($_SERVER['QUERY_STRING'], $qs_arr);
	    $getsite = $settings['startpage'];
	    
	    if (isset($qs_arr['site'])) {
	        $getsite = $qs_arr['site'];
	    }

	    // Verwende eine vorbereitete Abfrage, um SQL-Injektionen zu verhindern
	    $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_plugins` WHERE index_link LIKE ? AND `activate` = '1'");
	    if ($stmt) {
	        $searchSite = "%" . $getsite . "%";
	        $stmt->bind_param("s", $searchSite); // binde den Parameter
	        $stmt->execute();
	        $result = $stmt->get_result();
	        $ds = $result->fetch_array();
	        
	        @$modulname = $ds['modulname']; // Hier wird möglicherweise ein Fehler unterdrückt, was besser vermieden werden sollte
	    } else {
	        // Fehlerbehandlung, falls die Abfrage nicht vorbereitet werden konnte
	        echo "Fehler bei der Vorbereitung der Abfrage.";
	        return '';
	    }

	    $css = "\n";
	    // Hole alle Plugins mit demselben Modulnamen
	    $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_plugins` WHERE `activate` = '1' AND modulname = ?");
	    if ($stmt) {
	        $stmt->bind_param("s", $modulname); // binde den Modulnamen-Parameter
	        $stmt->execute();
	        $query = $stmt->get_result();
	    } else {
	        // Fehlerbehandlung
	        echo "Fehler bei der Vorbereitung der Abfrage für Modulnamen.";
	        return '';
	    }

	    if ($pluginadmin) {
	        $pluginpath = "../";
	    } else {
	        $pluginpath = "";
	    }

	    while ($res = mysqli_fetch_array($query)) {
	        if ($res['modulname'] == $modulname || $res == 1) {
	            $subf1 = is_dir($pluginpath . $res['path'] . "css/") ? "css/" : "";
	            $f = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $pluginpath . $res['path'] . $subf1) . '*.css');
	            $fc = count((array($f)), COUNT_RECURSIVE);
	            
	            if ($fc > 0) {
	                global $loaded_css_files;
	                if (!isset($loaded_css_files)) {
	                    $loaded_css_files = array();
	                }

	                foreach ($f as $file) {
	                    if (!in_array($file, $loaded_css_files)) { // Vermeidung von Duplikaten
	                        $css .= '<link type="text/css" rel="stylesheet" href="./' . $file . '" />' . chr(0x0D) . chr(0x0A);
	                        $loaded_css_files[] = $file; // Füge die Datei zu den geladenen Dateien hinzu
	                    }
	                }
	            }
	        }
	    }
	    return $css;
	}



	function plugin_loadheadfile_js($pluginadmin = false)
	{
	    global $_database; // Zugriff auf die globale $_database-Verbindung

	    parse_str($_SERVER['QUERY_STRING'], $qs_arr);
	    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : '';

	    // Verwende vorbereitete Abfrage für die Sicherheit
	    $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_plugins` WHERE index_link LIKE ? AND `activate` = '1'");
	    if ($stmt) {
	        $searchSite = "%" . $getsite . "%";
	        $stmt->bind_param("s", $searchSite); // binde den Parameter
	        $stmt->execute();
	        $result = $stmt->get_result();
	        $dk = $result->fetch_array();
	        @$modulname = $dk['modulname']; // Hier wird möglicherweise ein Fehler unterdrückt, was besser vermieden werden sollte
	    } else {
	        echo "Fehler bei der Vorbereitung der Abfrage für plugins.";
	        return '';
	    }

	    $js = "\n";
	    // Hole alle Plugins mit demselben Modulnamen
	    $stmt = $_database->prepare("SELECT * FROM `" . PREFIX . "settings_plugins` WHERE `activate` = '1' AND modulname = ?");
	    if ($stmt) {
	        $stmt->bind_param("s", $modulname); // binde den Modulnamen-Parameter
	        $stmt->execute();
	        $query = $stmt->get_result();
	    } else {
	        echo "Fehler bei der Vorbereitung der Abfrage für Modulnamen.";
	        return '';
	    }

	    if ($pluginadmin) {
	        $pluginpath = "../";
	    } else {
	        $pluginpath = "";
	    }

	    while ($res = mysqli_fetch_array($query)) {
	        if ($res['modulname'] == $modulname || $res == 1) {
	            $subf2 = is_dir($pluginpath . $res['path'] . "js/") ? "js/" : "";
	            $f = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $pluginpath . $res['path'] . $subf2) . '*.js');
	            $fc = count((array($f)), COUNT_RECURSIVE);
	            
	            if ($fc > 0) {
	                global $loaded_js_files;
	                if (!isset($loaded_js_files)) {
	                    $loaded_js_files = array();
	                }

	                foreach ($f as $file) {
	                    if (!in_array($file, $loaded_js_files)) { // Vermeidung von Duplikaten
	                        $js .= '<script defer src="./' . $file . '"></script>' . chr(0x0D) . chr(0x0A);
	                        $loaded_js_files[] = $file; // Füge die Datei zu den geladenen JS-Dateien hinzu
	                    }
	                }
	            }
	        }
	    }

	    return $js;
	}




	################################################################################


	function plugin_loadheadfile_widget_css()
	{
	    parse_str($_SERVER['QUERY_STRING'], $qs_arr);
	    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : 'startpage';
	    $pluginpath = "includes/plugins/";

	    $css = "\n";
	    
	    // Vermeidung der Verwendung von @ und direkte Fehlerbehandlung
	    if (
	        in_array($getsite, ['contact', 'imprint', 'privacy_policy', 'profile', 'myprofile', 'error_404', 'report', 
	            'static', 'loginoverview', 'register', 'lostpassword', 'login', 'logout', 'footer', 'navigation', 'topbar', 
	            'news_comments', 'articles_comments', 'blog_comments', 'gallery_comments', 'news_recomments', 'polls_comments', 
	            'videos_comments'])
	    ) {
	        $query = safe_query("SELECT * FROM " . PREFIX . "settings_plugins_widget_settings");
	    } elseif ($getsite == 'forum_topic') {
	        $query = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_settings_widgets");
	    } elseif (tableExists(PREFIX . "plugins_" . $getsite . "_settings_widgets")) {
	        $query = safe_query("SELECT * FROM " . PREFIX . "plugins_" . $getsite . "_settings_widgets");
	    } else {
	        // Fehlerbehandlung: Umleitung zu einer 404-Seite
	        header("Location: ./index.php?site=error_404");
	        exit;
	    }

	    // Verarbeitung der Ergebnisse
	    while ($res = mysqli_fetch_array($query)) {
	        // Wenn 'widget_agency_header' geladen wird, dann gesondertes CSS
	        if ($res['widgetdatei'] == 'widget_agency_header') {
	            echo '<link type="text/css" rel="stylesheet" href="./includes/plugins/carousel/css/style/agency_header.css" />';
	        }

	        $subf1 = is_dir($pluginpath . $res['modulname'] . "/css/") ? "/css/" : "";
	        $f = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $pluginpath . $res['modulname'] . $subf1) . '*.css');
	        $fc = count($f, COUNT_RECURSIVE);

	        if ($fc > 0) {
	            global $loaded_css_files;
	            if (!isset($loaded_css_files)) {
	                $loaded_css_files = array();
	            }

	            foreach ($f as $file) {
	                if (!in_array($file, $loaded_css_files)) { // Vermeidung von Duplikaten
	                    $css .= '<link type="text/css" rel="stylesheet" href="./' . $file . '" />' . chr(0x0D) . chr(0x0A);
	                    $loaded_css_files[] = $file; // Hinzufügen der Datei zur Liste der geladenen Dateien
	                }
	            }
	        }
	    }

	    return $css;
	}



	function plugin_loadheadfile_widget_js()
	{
	    parse_str($_SERVER['QUERY_STRING'], $qs_arr);
	    $getsite = isset($qs_arr['site']) ? $qs_arr['site'] : 'startpage';
	    $pluginpath = "includes/plugins/";

	    $js = "\n";
	    
	    // Verwendung von in_array() für URL-Überprüfung
	    $valid_sites = [
	        'contact', 'imprint', 'privacy_policy', 'profile', 'myprofile', 'error_404', 'report', 'static',
	        'loginoverview', 'register', 'lostpassword', 'login', 'logout', 'footer', 'navigation', 'topbar',
	        'news_comments', 'articles_comments', 'blog_comments', 'gallery_comments', 'news_comments',
	        'news_recomments', 'polls_comments', 'videos_comments'
	    ];

	    if (in_array($getsite, $valid_sites)) {
	        $query = safe_query("SELECT * FROM " . PREFIX . "settings_plugins_widget_settings");
	    } elseif ($getsite == 'forum_topic') {
	        $query = safe_query("SELECT * FROM " . PREFIX . "plugins_forum_settings_widgets");
	    } else {
	        // Überprüfung auf die Existenz der Tabelle
	        if (tableExists(PREFIX . "plugins_" . $getsite . "_settings_widgets")) {
	            $query = safe_query("SELECT * FROM " . PREFIX . "plugins_" . $getsite . "_settings_widgets");
	        } else {
	            header("Location: ./index.php?site=error_404");
	            exit;
	        }
	    }

	    // Verarbeitung der Ergebnisse
	    while ($res = mysqli_fetch_array($query)) {
	        $subf1 = is_dir($pluginpath . $res['modulname'] . "/js/") ? "/js/" : "";
	        $f = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $pluginpath . $res['modulname'] . $subf1) . '*.js');
	        $fc = count($f, COUNT_RECURSIVE);

	        if ($fc > 0) {
	            global $loaded_js_files;
	            if (!isset($loaded_js_files)) {
	                $loaded_js_files = array();
	            }

	            // Verwenden von foreach statt for
	            foreach ($f as $file) {
	                if (!in_array($file, $loaded_js_files)) { // Vermeidung von Duplikaten
	                    $js .= '<script defer src="./' . $file . '"></script>' . chr(0x0D) . chr(0x0A);
	                    $loaded_js_files[] = $file; // Hinzufügen der Datei zur Liste der geladenen Dateien
	                }
	            }
	        }
	    }

	    return $js;
	}





	//@info Ruft die Standardsprache der Seite ab und prüft, ob der Benutzer/Gäste
	// in seine eigene Sprache wechseln, andernfalls die Standardsprache auf EN setzen
	//@name legt den Namen der zu ladenden Sprachdatei fest
	/* NENNEN SIE ES
	/!\ NIEMALS die Variable $_sprache verwenden (Konflikt mit dem Hauptmodul)

	$pm = neuer Plugin_Manager();
	$_lang = $pm->plugin_sprache("mein-plugin", $plugin_path);
	*/
	function plugin_language($name, $plugin_path)
	{
	    // Datenbankabfrage für die Standard-Sprache
	    $res = safe_query("SELECT `default_language` FROM `" . PREFIX . "settings` WHERE 1");
	    
	    // Überprüfung, ob die Abfrage erfolgreich war
	    if ($res) {
	        $row = mysqli_fetch_array($res);
	        // Standard-Sprache aus der Datenbank holen
	        $default_language = isset($row['default_language']) ? $row['default_language'] : 'en';
	    } else {
	        // Falls die Abfrage fehlschlägt, Standard auf Englisch setzen
	        $default_language = 'en';
	    }
	    
	    // Sprache aus der Sitzung holen, falls vorhanden
	    if (isset($_SESSION['language']) && !empty($_SESSION['language'])) {
	        $lng = $_SESSION['language'];
	    } else {
	        // Wenn keine Sprache in der Sitzung vorhanden ist, auf die Standard-Sprache zurückgreifen
	        $lng = $default_language;
	    }

	    try {
	        // Initialisierung der Language-Klasse
	        $_lang = new webspell\Language();
	        $_lang->setLanguage($lng, false);
	        $_lang->readModule($name, true, false, $plugin_path);
	    } catch (Exception $e) {
	        // Fehlerbehandlung im Falle eines Problems mit der Sprachdatei
	        error_log('Fehler beim Laden der Sprache: ' . $e->getMessage());
	        return null; // Rückgabe von null, falls ein Fehler auftritt
	    }
	    
	    // Rückgabe des geladenen Sprachmoduls
	    return $_lang->module;
	}

	function plugin_adminLanguage($plugin, $file, $admin = false)
	{
	    try {
	        // Datenbankabfrage für die Standard-Sprache
	        $res = safe_query("SELECT `default_language` FROM `" . PREFIX . "settings` WHERE 1");
	        
	        // Überprüfung, ob die Abfrage erfolgreich war
	        if (!$res) {
	            throw new Exception("Fehler beim Abrufen der Spracheinstellungen.");
	        }

	        $row = mysqli_fetch_array($res);
	        $lng = isset($_SESSION['language']) && !empty($_SESSION['language']) ? $_SESSION['language'] : (isset($row['default_language']) ? $row['default_language'] : 'en');

	        // Festlegen des Admin-Ordners, wenn angegeben
	        $adminFolder = $admin ? 'admin' : '';

	        // Pfad zur Sprachdatei
	        $path = "./$file/languages/$lng/$adminFolder/$plugin.php";

	        // Überprüfung, ob die Datei existiert
	        if (!file_exists($path)) {
	            throw new Exception("Sprachdatei nicht gefunden: $path");
	        }

	        // Laden der Sprachdatei
	        include($path);

	        // Rückgabe des Spracharrays
	        $arr = [];
	        if (isset($language_array) && is_array($language_array)) {
	            foreach ($language_array as $key => $val) {
	                $arr[$key] = $val;
	            }
	        } else {
	            throw new Exception("Die Sprachdatei enthält keine gültigen Daten.");
	        }

	        return $arr;
	    } catch (Exception $ex) {
	        // Fehlerbehandlung
	        return ['error' => $ex->getMessage()];
	    }
	}

	//@info		update website title for SEO
	function plugin_updatetitle($site)
	{
	    try {
	        // Überprüfen, ob das Plugin über $_GET['site'] aufgerufen wurde
	        if (!isset($_GET['site']) || empty($_GET['site'])) {
	            throw new Exception('Kein Plugin-Name angegeben.');
	        }
	        
	        $pluginSite = htmlspecialchars($_GET['site']); // Eingabe validieren (Sicherheitsmaßnahme)

	        $pm = new plugin_manager();

	        // Prüfen, ob das angeforderte Plugin existiert
	        if ($pm->is_plugin($pluginSite) == 1) {
	            $arr = $pm->plugin_data($pluginSite);
	            
	            if (isset($arr['name'])) {
	                // Wenn der Name des Plugins gesetzt ist, den Titel aktualisieren
	                return settitle($arr['name']);
	            } else {
	                throw new Exception('Der Plugin-Name wurde nicht gefunden.');
	            }
	        } else {
	            throw new Exception('Das angeforderte Plugin ist nicht aktiv oder existiert nicht.');
	        }
	    } catch (Exception $x) {
	        // Fehlerbehandlung für alle Fehler
	        if ($this->_debug === "ON") {
	            return '<span class="label label-danger">' . htmlspecialchars($x->getMessage()) . '</span>';
	        } else {
	            // Generische Fehlerausgabe, wenn Debug nicht aktiviert ist
	            return '<span class="label label-danger">Fehler beim Aktualisieren des Titels.</span>';
	        }
	    }
	}
}


/*Plugins manuell einbinden 
get_widget('modulname','widgetdatei'); 
*/
function get_widget($modulname, $widgetdatei)
{
    // Holen der Plugin-Daten aus der Datenbank
    $query = safe_query("SELECT * FROM " . PREFIX . "settings_plugins WHERE modulname = '" . mysqli_real_escape_string($mysqli, $modulname) . "'");
    $ds = mysqli_fetch_array($query);

    // Prüfen, ob der Pfad des Plugins und die Widget-Datei existieren
    if ($ds && isset($ds['path'])) {
        $plugin_path = $ds['path'];
        $file_path = $plugin_path . $widgetdatei . ".php";
        
        // Wenn die Datei existiert, lade sie
        if (file_exists($file_path)) {
            require($file_path);
            return true;
        } else {
            // Datei existiert nicht, gib eine Fehlermeldung zurück
            echo '<p class="error">Die Widget-Datei ' . htmlspecialchars($widgetdatei) . '.php wurde nicht gefunden.</p>';
            return false;
        }
    } else {
        // Fehler: Das Plugin wurde nicht in der Datenbank gefunden
        echo '<p class="error">Das Plugin mit dem Modulnamen ' . htmlspecialchars($modulname) . ' wurde nicht gefunden.</p>';
        return false;
    }
}

