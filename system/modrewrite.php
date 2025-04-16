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

namespace webspell;

class ModRewrite
{
    // Array für Übersetzungen (Regex-Übersetzungen)
    private $translation = array();

    // Cache für gespeicherte Übersetzungen
    private $cache = null;

    // Statischer Wert für die Rewrite-Basis
    private static $rewriteBase;

    // Zustand für Aktivierung des Rewrites
    private $state = false;

    // Konstruktor der Klasse, der Standardübersetzungen initialisiert
    public function __construct()
    {
        // Übersetzungen für verschiedene Typen definieren
        // 'integer' für numerische Werte
        $this->translation['integer'] = array('replace' => '([0-9]+)', 'rebuild' => '([0-9]+?)');
        // 'string' für alphanumerische Strings
        $this->translation['string'] = array('replace' => '(\w*?)', 'rebuild' => '(\w*?)');
        // 'everything' für alle möglichen Zeichen
        $this->translation['everything'] = array('replace' => '([^\'\\"]*)', 'rebuild' => '([^\'\\"]*)');
        
        // Setzt die Rewrite-Basis für die URL-Struktur
        $GLOBALS['rewriteBase'] = $this->getRewriteBase();
    }

    // Gibt die verfügbaren Übersetzungstypen zurück
    public function getTypes()
    {
        return array_keys($this->translation);
    }

    // Gibt zurück, ob das Rewrite aktiviert ist
    public function enabled()
    {
        return $this->state;
    }

    // Aktiviert das ModRewrite und registriert Callback-Funktionen
    public function enable()
    {
        $this->state = true;
        $this->buildCache(); // Cache für die Übersetzungen aufbauen
        ob_start(array($this, 'rewriteBody')); // Puffer starten für das Umwandeln des Inhalts

        // Überprüft PHP-Version, um zu entscheiden, wie Header zu registrieren sind
        $fixedHeader = false;
        if (PHP_MAJOR_VERSION == 5) {
            if (PHP_MINOR_VERSION == 4) {
                if (PHP_RELEASE_VERSION > 24) {
                    $fixedHeader = true;
                }
            } elseif (PHP_MINOR_VERSION == 5) {
                if (PHP_RELEASE_VERSION > 8) {
                    $fixedHeader = true;
                }
            }
        }

        // Header-Callback-Funktion für korrektes Umleiten registrieren
        if ($fixedHeader) {
            header_register_callback(array($this, 'rewriteHeaders'));
        } else {
            register_shutdown_function(array($this, 'rewriteHeaders'));
        }
    }

    // Baut den Cache mit den Regeln aus der Datenbank
    private function buildCache()
    {
        $this->cache = array();
        // Holt alle Rewrite-Regeln aus der Datenbank
        $get = safe_query("SELECT `replace_regex`, `replace_result` FROM `modrewrite` ORDER BY `link` DESC");
        while ($ds = mysqli_fetch_assoc($get)) {
            $this->cache[] = $ds;
        }
    }

    // Holt die Rewrite-Basis-URL (z.B. '/site/')
    public function getRewriteBase()
    {
        if (!isset(self::$rewriteBase)) {
            $path = str_replace(
                realpath($_SERVER['DOCUMENT_ROOT']),
                '',
                ''
            );
            $path = str_replace('\\', '/', $path);
            if (strlen($path) > 0) {
                if ($path[0] != '/') {
                    $path = '/' . $path;
                }
                if ($path[strlen($path) - 1] != '/') {
                    $path = $path . '/';
                }
            } else {
                $path = '/';
            }
            self::$rewriteBase = $path; // Setzt die Basis-URL
        }
        return self::$rewriteBase;
    }

    // Generiert den Inhalt für die .htaccess-Datei
    public function generateHtAccess($basepath, $rewriteFileName = "rewrite.php")
    {
        return '<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteBase ' . $basepath . '
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ ' . $rewriteFileName . '?url=$1 [L,QSA]
</IfModule>';
    }

    // Bearbeitet alle HTTP-Header und ersetzt die Location-Header
    public function rewriteHeaders()
    {
        $headers = headers_list();
        foreach ($headers as $header) {
            // Wenn der Header "Location" enthält, wird er umgeschrieben
            if (stristr($header, "Location")) {
                header($this->rewrite($header, true), true);
            }
        }
    }

    // Hauptfunktion zum Umwandeln des Inhalts
    public function rewriteBody($content, $phase)
    {
        return $this->rewrite($content, false);
    }

    // Übernimmt die Umwandlung und das Ersetzen von URLs und Links
    private function rewrite($content, $headers = false)
    {
        $start_time = microtime(true); // Startzeit für Performance-Messung
        // Bestimmen, ob der erweiterte Ersatz benötigt wird (für JavaScript-Funktionen)
        if (stristr($content, "MM_goToURL") ||
            stristr($content, "window.open") ||
            stristr($content, 'http-equiv="refresh"')
        ) {
            $extended_replace = true;
        } else {
            $extended_replace = false;
        }

        // Geht durch den Cache mit den Ersetzungsregeln
        foreach ($this->cache as $ds) {
            $regex = $ds['replace_regex'];
            $replace = $ds['replace_result'];
            // Ersetzt URLs im Header (z.B. für Weiterleitungen)
            if ($headers === true) {
                $content = preg_replace(
                    "/()()Location:\s" . $regex . "/i",
                    'Location: ' . $this->getRewriteBase() . $replace,
                    $content
                );
            } else {
                // Ersetzt Links im Body (z.B. href, action, etc.)
                $content = preg_replace(
                    "/(href|action|option value)=(['\"])" . $regex . "[\"']/iS",
                    '$1=$2' . $replace . '$2',
                    $content
                );
                // Wenn erweiterter Ersatz benötigt wird, werden zusätzliche JavaScript- und Meta-Tag-Ersetzungen gemacht
                if ($extended_replace) {
                    $content =
                        preg_replace(
                            "/onclick=(['\"])(window.open\(|MM_goToURL\('parent',|MM_confirm\('.*?',\s)'" .
                            $regex . "'/Si",
                            'onclick=$1$2\'' . $replace . '\'',
                            $content
                        );
                    $content =
                        preg_replace(
                            "/href=(['\"])(javascript:window.open\(|window.open\(|MM_goToURL\('parent',)'" .
                            $regex . "'/Si",
                            'href=$1$2\'' . $replace . '\'',
                            $content
                        );
                    $content = preg_replace(
                        "/()(<meta .*?;URL=)" . $regex . "\"/Si",
                        '$2' . $this->getRewriteBase() . $replace . '"',
                        $content
                    );
                }
            }
        }

        // Berechnet die Zeit, die für die Umwandlung benötigt wurde
        $needed = microtime(true) - $start_time;
        header('X-Rewrite-Time: ' . $needed); // Setzt den Header für die Zeitmessung
        return $content; // Gibt den umgewandelten Inhalt zurück
    }

    // Baut das Regex und den Ersatz für den Build-Vorgang
    public function buildReplace($regex, $replace, $fields = array())
    {
        $regex = str_replace(array('.', '?', '&', '/'), array('\.', '\?', '[&|&amp;]*', '\/'), $regex);
        if (count($fields)) {
            $i = 3;
            preg_match_all("/{(\w*)}/si", $regex, $matches, PREG_SET_ORDER);
            foreach ($matches as $field) {
                $regex =
                    str_replace("{" . $field[1] . "}", $this->translation[$fields[$field[1]]]['replace'], $regex);
                $replace = str_replace("{" . $field[1] . "}", '$' . $i, $replace);
                $i++;
            }
        }
        return array($regex, $replace);
    }

    // Baut das Regex und den Ersatz für den Rebuild-Vorgang
    public function buildRebuild($regex, $replace, $fields = array())
    {
        $i = 1;
        $regex = str_replace(array('.', '?', '/'), array('\.', '\?', '\/'), $regex);
        if (count($fields)) {
            preg_match_all("/{(\w*)}/si", $regex, $matches, PREG_SET_ORDER);
            foreach ($matches as $field) {
                $regex =
                    str_replace("{" . $field[1] . "}", $this->translation[$fields[$field[1]]]['rebuild'], $regex);
                $replace = str_replace("{" . $field[1] . "}", '$' . $i, $replace);
                $i++;
            }
        }
        return array($regex, $replace);
    }
}
