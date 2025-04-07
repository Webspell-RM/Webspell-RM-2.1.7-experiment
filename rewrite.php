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

if (basename($_SERVER['SCRIPT_FILENAME']) == basename("rewrite.php")) {
    include_once("system/sql.php");
    $_database = new mysqli($host, $user, $pwd, $db);

    // Prüfen, ob die Verbindung zum MySQL-Server erfolgreich ist
    if ($_database->connect_error) {
        die('ERROR: Cannot connect to MySQL Server');
    }
    
    // Setzen der richtigen Zeichencodierung
    $_database->query("SET NAMES 'utf8'");

    $_site = null;
    $start_time = microtime(true);

    // Prüfen, ob die URL-Parameter gesetzt sind
    if (isset($_GET['url']) && !empty($_GET['url'])) {
        // URL in Teile aufsplitten, um sie zu verarbeiten
        $url_parts = preg_split("/[\._\/-]/", $_GET['url']);
        $first = $url_parts[0];

        // Vorbereitetes Statement zur sicheren Abfrage
        if ($stmt = $_database->prepare("SELECT * FROM " . PREFIX . "modrewrite WHERE regex LIKE ? ORDER BY LENGTH(regex) ASC")) {
            $like_first = '%' . $first . '%';
            $stmt->bind_param("s", $like_first);  // Bindet den Parameter
            $stmt->execute();
            $result = $stmt->get_result();

            while ($ds = $result->fetch_assoc()) {
                $replace = $ds['rebuild_result'];
                $regex = $ds['rebuild_regex'];

                // Ersetzen mit regulärem Ausdruck
                $new = preg_replace("/" . $regex . "/i", $replace, $_GET['url'], -1, $replace_count);

                // Wenn Ersetzung erfolgreich war
                if ($replace_count > 0) {
                    $url = parse_url($new);
                    if (isset($url['query'])) {
                        $parts = explode("&", $url['query']);
                        foreach ($parts as $part) {
                            $k = explode("=", $part);
                            $_GET[$k[0]] = $k[1];
                            $_REQUEST[$k[0]] = $k[1];
                        }
                    }
                    $_site = $url['path'];
                    break;
                }
            }

            $stmt->close();  // Schließt das Statement
        }
    }

    // Wenn keine Seite gefunden wurde, 404-Fehler zurückgeben
    if ($_site === null) {
        header("HTTP/1.0 404 Not Found");
        $_site = "index.php";
        $_GET['site'] = "./includes/modules/404.php";
        $_GET['type'] = 404;
    }

    // Berechnung der benötigten Zeit
    $needed = microtime(true) - $start_time;
    header('X-Rebuild-Time: ' . $needed);

    // Lade die entsprechende Seite
    require($_site);
}
