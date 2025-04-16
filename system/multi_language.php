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

class multiLanguage {

    // Aktuelle Sprache, die verwendet wird
    public $language;

    // Liste der verfügbaren Sprachen im Text
    public $availableLanguages = array();

    // Konstruktor der Klasse, initialisiert mit der gewünschten Sprache
    public function __construct($lang) {
        $this->language = $lang;
    }

    /**
     * Ermittelt alle verfügbaren Sprachen im gegebenen Text.
     * Extrahiert Sprach-Tags aus dem Text und speichert sie in der `availableLanguages`-Liste.
     * 
     * @param string $text Der zu untersuchende Text, der Sprach-Tags enthält.
     */
    public function detectLanguages($text) {
        // Trenne den Text an den Sprach-Tags '{[' und ']}'
        $sox = explode('{[', $text);

        // Iteriere durch alle Textteile und prüfe, ob es ein neues Sprach-Tag gibt
        foreach ($sox as $part) {
            // Extrahiere den Text nach ']}', um das Sprach-Tag zu isolieren
            $eox = explode(']}', $part);
            
            // Füge die Sprache zur Liste der verfügbaren Sprachen hinzu, falls sie noch nicht existiert
            if (isset($eox[0]) && !in_array($eox[0], $this->availableLanguages) && !empty($eox[0])) {
                $this->availableLanguages[] = $eox[0];
            }
        }
    }

    /**
     * Gibt den Text in der ausgewählten Sprache zurück.
     * Falls die gewünschte Sprache nicht verfügbar ist, wird eine andere verfügbare Sprache verwendet.
     * 
     * @param string $text Der Text, der übersetzt werden soll.
     * @return string Der übersetzte Text in der gewünschten Sprache.
     */
    public function getTextByLanguage($text) {
        // Prüfen, ob die angeforderte Sprache verfügbar ist
        if (in_array($this->language, $this->availableLanguages)) {
            return $this->getTextByTag($this->language, $text);
        } elseif (!empty($this->availableLanguages)) {
            // Wenn die ausgewählte Sprache nicht vorhanden ist, verwende eine andere verfügbare Sprache
            return $this->getTextByTag($this->availableLanguages[0], $text);
        } else {
            // Wenn keine Sprachen gefunden wurden, gebe den Originaltext zurück
            return $text;
        }
    }

    /**
     * Hilfsmethode zum Extrahieren des Texts für ein bestimmtes Sprach-Tag.
     * 
     * @param string $language Die gewünschte Sprache, für die der Text extrahiert werden soll.
     * @param string $text Der Text, der das Sprach-Tag enthält.
     * @return string Der extrahierte Text für die angegebene Sprache.
     */
    private function getTextByTag($language, $text) {
        // Extrahiere den Text für die angegebene Sprache
        $output = '';
        $fix = explode('{[' . $language . ']}', $text);

        // Füge den Text ohne Sprach-Tag hinzu
        foreach ($fix as $part) {
            $tmp = explode('{[', $part);
            $output .= $tmp[0];
        }

        return $output;
    }
}
