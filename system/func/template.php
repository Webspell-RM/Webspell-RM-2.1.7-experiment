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

class Template
{
    /**
     * @var string Der Basisordner, in dem sich die Template-Dateien befinden.
     */
    private $rootFolder;

    /**
     * @var string Pfad zu den Templates
     */
    public $themes_path;

    /**
     * @var string Pfad zu den Template-Dateien
     */
    public $template_path;

    /**
     * Konstruktor zur Initialisierung des Template-Ordners.
     *
     * @param string $rootFolder Basisordner, in dem sich die Template-Dateien befinden.
     */
    public function __construct($rootFolder = "templates")
    {
        $this->rootFolder = $rootFolder;
    }

    /**
     * Lädt den Inhalt einer Template-Datei.
     *
     * @param string $template Der Name des Templates.
     *
     * @return string Der Inhalt des Templates.
     * @throws \Exception Wenn die Datei nicht gefunden wurde.
     */
    private function loadFile($template)
    {
        $file = $this->rootFolder . "/" . $template . ".html";
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            throw new \Exception("Unbekannte Template-Datei: " . $file, 1);
        }
    }

    /**
     * Lädt den HTML-Inhalt eines Templates und gibt einen spezifischen Abschnitt des Templates zurück.
     *
     * @param string $template Der Name des Templates.
     * @param string $content Der Name des Inhaltsabschnitts.
     * @param string $path Optionaler zusätzlicher Pfad.
     *
     * @return string Der Inhalt des spezifischen Abschnitts.
     * @throws \Exception Wenn die Datei oder der Abschnitt nicht gefunden wurde.
     */
    private function load_html($template, $content, $path = false)
    {
        if ($path != false) {
            $file = $path . "templates/" . $template . ".html";
        } else {
            $file = $this->rootFolder . "/" . $template . ".html";
        }

        if (file_exists($file)) {
            $lo = file_get_contents($file);
            $a = explode("<!-- " . $template . "_" . $content . " -->", $lo);
            $b = explode("<!-- END -->", $a[1]);
            if ($a[1] == '') {
                system_error('Unbekannte Template-Datei<br> ' . $file . ' <br>' . $template . '_' . $content, 0, 1);
            } else {
                return $b[0];
            }
        } else {
            throw new \Exception("Unbekannte Template-Datei: " . $file, 1);
        }
    }

    /**
     * Ersetzt alle Platzhalter im Template mit den entsprechenden Werten aus den Daten.
     *
     * @param string $template Der Inhalt des Templates.
     * @param array $data Ein assoziatives Array mit Platzhalter-Daten.
     *
     * @return string Der ersetzte Template-Inhalt.
     */
    private function replace($template, $data = array())
    {
        return preg_replace_callback('/{{\s*(\w+)\s*}}/', function ($matches) use ($data) {
            $key = $matches[1];
            return array_key_exists($key, $data) ? $data[$key] : $matches[0];
        }, $template);
    }

    /**
     * Ersetzt ein Template mit einem Satz von Daten und übersetzt alle Sprachplatzhalter.
     *
     * @param string $template Der Name des Templates.
     * @param array $data Ein assoziatives Array mit Platzhalter-Daten.
     *
     * @return string Der ersetzte Template-Inhalt.
     * @throws \Exception Wenn ein Fehler auftritt.
     */
    public function replaceTemplate($template, $data = array())
    {
        $templateString = $this->loadFile($template);
        $templateTranslated = $this->replaceLanguage($templateString);
        return $this->replace($templateTranslated, $data);
    }

    /**
     * Lädt und ersetzt ein Template und einen spezifischen Abschnitt mit Daten.
     *
     * @param string $template Der Name des Templates.
     * @param string $content Der Name des Inhaltsabschnitts.
     * @param array $data Ein assoziatives Array mit Platzhalter-Daten.
     * @param string|null $path Optionaler zusätzlicher Pfad.
     *
     * @return string Der ersetzte Template-Inhalt.
     */
    public function loadTemplate($template, $content, $data = array(), $path = false)
    {
        $newpath = $path ? $path : false;
        $templateString = $this->load_html($template, $content, $newpath);
        $templateTranslated = $this->replaceLanguage($templateString);
        return $this->replace($templateTranslated, $data);
    }

    /**
     * Ersetzt alle Sprachplatzhalter im Template mit den entsprechenden Übersetzungen.
     *
     * @param string $template Der Inhalt des Templates.
     *
     * @return string Der Template-Inhalt mit den ersetzten Sprachvariablen.
     */
    private function replaceLanguage($template)
    {
        return $this->replace($template, $GLOBALS['_language']->getTranslationTable());
    }

    /**
     * Ersetzt ein Template mehrfach mit mehreren Datensätzen.
     *
     * @param string $template Der Name des Templates.
     * @param array $datas Ein Array mit mehreren Datensätzen für die Ersetzungen.
     *
     * @return string Der zusammengesetzte Inhalt mit allen ersetzten Daten.
     * @throws \Exception Wenn kein multidimensionales Array übergeben wurde.
     */
    public function replaceMulti($template, &$datas = array())
    {
        if (!is_array($datas) || !isset($datas[0]) || !is_array($datas[0])) {
            throw new \Exception("Kein multidimensionales Array übergeben", 2);
        }

        $templateString = $this->loadFile($template);
        $templateBase = $this->replaceLanguage($templateString);
        $return = '';
        
        foreach ($datas as $data) {
            $return .= $this->replace($templateBase, $data);
        }

        unset($datas);
        return $return;
    }
}

class TemplateService
{
    private Template $template;

    /**
     * Konstruktor zur Initialisierung des Template-Service.
     *
     * @param string|null $templateRoot Der Basisordner für Templates (optional).
     */
    public function __construct(?string $templateRoot = null)
    {
        $this->template = new Template($templateRoot ?? 'templates');
    }

    /**
     * Rendert ein Template mit einem bestimmten Abschnitt und Datensatz.
     *
     * @param string $template Der Name des Templates.
     * @param string $section Der Abschnitt des Templates.
     * @param array $data Ein assoziatives Array mit Platzhalter-Daten.
     * @param string|null $path Optionaler zusätzlicher Pfad.
     *
     * @return string Der gerenderte Template-Inhalt.
     */
    public function render(string $template, string $section, array $data = [], ?string $path = null): string
    {
        return $this->template->loadTemplate($template, $section, $data, $path);
    }

    /**
     * Rendert ein einfaches Template mit den angegebenen Daten.
     *
     * @param string $template Der Name des Templates.
     * @param array $data Ein assoziatives Array mit Platzhalter-Daten.
     *
     * @return string Der gerenderte Template-Inhalt.
     */
    public function renderSimple(string $template, array $data = []): string
    {
        return $this->template->replaceTemplate($template, $data);
    }

    /**
     * Rendert ein Template mehrfach mit mehreren Datensätzen.
     *
     * @param string $template Der Name des Templates.
     * @param array $dataList Ein Array mit mehreren Datensätzen für die Ersetzungen.
     *
     * @return string Der zusammengesetzte Inhalt mit allen ersetzten Daten.
     */
    public function renderMulti(string $template, array $dataList = []): string
    {
        return $this->template->replaceMulti($template, $dataList);
    }
}
