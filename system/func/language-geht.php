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
    public $language = 'en';
    public $module = array();
    private $language_path = 'languages/';

    public function setLanguage($to, $admin = false, $pluginpath = false)
    {
        if ($admin) {
            // Admin-Sprachdateien befinden sich in /admin/language/
            $this->language_path = 'language/';
        }

        if ($pluginpath) {
            // Pluginpfad überschreibt alles, z. B. ../plugins/mymodule/
            $this->language_path = $pluginpath . 'languages/';
        }

        $langs = array();
        if (is_dir($this->language_path)) {
            foreach (new \DirectoryIterator($this->language_path) as $fileInfo) {
                if (!$fileInfo->isDot() && $fileInfo->isDir()) {
                    $langs[] = $fileInfo->getFilename();
                }
            }
        }

        if (in_array($to, $langs)) {
            $this->language = $to;
            return true;
        } else {
            return false;
        }
    }

    public function getRootPath()
    {
        return $this->language_path;
    }

    public function readModule($module, $add = false, $admin = false, $pluginpath = false)
    {
        global $default_language;

        if ($admin) {
            $langFolder = 'language/';
        } elseif ($pluginpath) {
            $langFolder = $pluginpath . 'languages/';
        } else {
            $langFolder = 'languages/';
        }

        $folderPath = '%s%s/%s.php'; // z. B. admin/language/de/modul.php

        $languageFallbackTable = array(
            $this->language,
            $default_language,
            'en'
        );

        $module = str_replace(array('\\', '/', '.'), '', $module);

        foreach ($languageFallbackTable as $folder) {
            $path = sprintf($folderPath, $langFolder, $folder, $module);
            if (file_exists($path)) {
                $module_file = $path;
                break;
            }
        }

        if (!isset($module_file)) {
            return false;
        }

        include($module_file);

        if (!isset($language_array) || !is_array($language_array)) {
            return false;
        }

        if (!$add) {
            $this->module = array();
        }

        foreach ($language_array as $key => $val) {
            $this->module[$key] = $val;
        }

        return true;
    }

    public function replace($template)
    {
        foreach ($this->module as $key => $val) {
            // Unterstützt {{ KEY }} Platzhalter
            $template = str_replace('{{ ' . $key . ' }}', $val, $template);
        }
        return $template;
    }

    public function getTranslationTable()
    {
        $map = array();
        foreach ($this->module as $key => $val) {
            $map['{{ ' . $key . ' }}'] = $val;
        }
        return $map;
    }
}
