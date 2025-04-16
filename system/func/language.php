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
 * @copyright       2018-2025 by webspell-rm.de                                                              *
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

class Language
{
    // Sprache, die aktuell verwendet wird
    public $language = 'en';

    // Array, das die Modulübersetzungen speichert
    public $module = array();

    // Pfad zu den Sprachdateien
    private $language_path = 'languages/';

    /**
     * Setzt die zu verwendende Sprache.
     *
     * @param string $to Die zu verwendende Sprache.
     * @param bool $admin Gibt an, ob es sich um die Admin-Oberfläche handelt.
     * @param string|false $pluginpath Gibt einen alternativen Pluginpfad an.
     * @return bool True, wenn die Sprache gesetzt wurde, andernfalls false.
     */
    public function setLanguage($to, $admin = false, $pluginpath = false)
    {
        // Wenn es sich um Admin-Sprache handelt, setze den Pfad für Admin-Dateien
        if ($admin) {
            $this->language_path = 'language/';
        }

        // Falls ein Pluginpfad angegeben wird, überschreibt dieser den Standardpfad
        if ($pluginpath) {
            $this->language_path = $pluginpath . 'languages/';
        }

        // Array, das alle verfügbaren Sprachordner speichert
        $langs = array();
        if (is_dir($this->language_path)) {
            foreach (new \DirectoryIterator($this->language_path) as $fileInfo) {
                if (!$fileInfo->isDot() && $fileInfo->isDir()) {
                    $langs[] = $fileInfo->getFilename();
                }
            }
        }

        // Überprüfen, ob die gewünschte Sprache verfügbar ist
        if (in_array($to, $langs)) {
            $this->language = $to;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gibt den Pfad zu den Sprachdateien zurück.
     *
     * @return string Der Pfad zu den Sprachdateien.
     */
    public function getRootPath()
    {
        return $this->language_path;
    }

    /**
     * Lädt die Sprachdatei für ein Modul.
     *
     * @param string $module Das Modul, für das die Sprachdatei geladen werden soll.
     * @param bool $add Gibt an, ob die Übersetzungen hinzugefügt werden sollen (anstelle des Überschreibens).
     * @param bool $admin Gibt an, ob es sich um die Admin-Oberfläche handelt.
     * @param string|false $pluginpath Gibt einen alternativen Pluginpfad an.
     * @return bool True, wenn die Sprachdatei erfolgreich geladen wurde, andernfalls false.
     */
    public function readModule($module, $add = false, $admin = false, $pluginpath = false)
    {
        global $default_language;

        // Bestimmen des Sprachordners je nach Kontext (Admin, Plugin, Standard)
        if ($admin) {
            $langFolder = 'language/';
        } elseif ($pluginpath) {
            $langFolder = $pluginpath . 'languages/';
        } else {
            $langFolder = 'languages/';
        }

        // Pfadformat für Sprachdateien, z. B. admin/language/de/modul.php
        $folderPath = '%s%s/%s.php';

        // Fallback-Reihenfolge der Sprachen (Priorität: aktuelle Sprache > Standard-Sprache > Englisch)
        $languageFallbackTable = array(
            $this->language,
            $default_language,
            'en'
        );

        // Entfernen von unerwünschten Zeichen aus dem Modulnamen
        $module = str_replace(array('\\', '/', '.'), '', $module);

        // Versuch, die passende Sprachdatei zu laden
        foreach ($languageFallbackTable as $folder) {
            $path = sprintf($folderPath, $langFolder, $folder, $module);
            if (file_exists($path)) {
                $module_file = $path;
                break;
            }
        }

        // Wenn keine Datei gefunden wurde, Rückgabe false
        if (!isset($module_file)) {
            return false;
        }

        // Inkludieren der Sprachdatei
        include($module_file);

        // Überprüfen, ob die Sprachdaten korrekt geladen wurden
        if (!isset($language_array) || !is_array($language_array)) {
            return false;
        }

        // Wenn $add nicht gesetzt ist, wird das Modul-Array geleert
        if (!$add) {
            $this->module = array();
        }

        // Füge alle geladenen Übersetzungen hinzu
        foreach ($language_array as $key => $val) {
            $this->module[$key] = $val;
        }

        return true;
    }

    /**
     * Ersetzt Platzhalter im Template durch die entsprechenden Übersetzungen.
     *
     * @param string $template Das Template mit den Platzhaltern.
     * @return string Das Template mit den ersetzten Werten.
     */
    public function replace($template)
    {
        foreach ($this->module as $key => $val) {
            // Unterstützt {{ KEY }} Platzhalter
            $template = str_replace('{{ ' . $key . ' }}', $val, $template);
        }
        return $template;
    }

    /**
     * Gibt eine Tabelle mit allen Platzhaltern und ihren Übersetzungen zurück.
     *
     * @return array Die Tabelle der Übersetzungen.
     */
    public function getTranslationTable()
    {
        $map = array();
        foreach ($this->module as $key => $val) {
            $map['{{ ' . $key . ' }}'] = $val;
        }
        return $map;
    }
}
