<?php
namespace webspell;

use plugin_manager;  // Importiert die Klasse plugin_manager, um Sprachdateien zu laden

class PluginService
{
    // Statische Variable zur Speicherung der Template-Engine
    private static $templateEngine;
    
    // Statische Variable zur Speicherung der geladenen Sprachdateien
    private static $pluginLanguages = [];

    /**
     * Lädt die Template-Engine für ein Plugin. Die Engine wird nur einmalig geladen.
     *
     * @param string $pluginPath Der Pfad zum Plugin
     * @return Template Die Template-Engine des Plugins
     */
    public static function getTemplateEngine($pluginPath)
    {
        // Wenn die Template-Engine noch nicht geladen wurde, laden wir sie jetzt
        if (self::$templateEngine === null) {
            self::$templateEngine = new Template($pluginPath . 'templates');
        }
        return self::$templateEngine;
    }

    /**
     * Lädt die Sprachdateien für ein Plugin. Die Sprachdateien werden nur einmalig geladen.
     *
     * @param string $pluginName Der Name des Plugins
     * @param string $pluginPath Der Pfad zum Plugin
     * @return array|null Die Sprachdateien des Plugins oder null bei Fehler
     */
    public static function getPluginLanguage($pluginName, $pluginPath)
    {
        // Wenn die Sprachdateien für das Plugin noch nicht geladen wurden, laden wir sie jetzt
        if (!isset(self::$pluginLanguages[$pluginName])) {
            // Instanziiere plugin_manager nur, wenn die Klasse existiert
            if (class_exists('plugin_manager')) {
                $pm = new plugin_manager();  // Instanziiere die Klasse plugin_manager
                // Sprachdateien abrufen und speichern
                self::$pluginLanguages[$pluginName] = $pm->plugin_language($pluginName, $pluginPath);
            } else {
                echo 'plugin_manager Klasse wurde nicht gefunden';
                return null;
            }
        }
        return self::$pluginLanguages[$pluginName];
    }

    /**
     * Ruft sowohl die Sprachdateien als auch die Template-Engine für ein Plugin ab.
     * Diese Methode kombiniert die beiden vorherigen Methoden.
     *
     * @param string $pluginName Der Name des Plugins
     * @param string $pluginPath Der Pfad zum Plugin
     * @return array Ein Array mit den Sprachdateien und der Template-Engine des Plugins
     */
    public static function getPluginData($pluginName, $pluginPath)
    {
        // Sprachdateien abrufen
        $pluginLanguage = self::getPluginLanguage($pluginName, $pluginPath);

        // Template Engine abrufen
        $pluginTemplate = self::getTemplateEngine($pluginPath);

        // Beide Daten als Array zurückgeben
        return [
            'language' => $pluginLanguage,
            'template' => $pluginTemplate
        ];
    }
}
