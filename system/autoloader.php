<?php
spl_autoload_register(function ($class) {
    if (strpos($class, 'webspell\\') === 0) {
        $class = str_replace('webspell\\', '', $class);
        $class = str_replace('\\', '/', $class);
        $file = __DIR__ . '/../includes/classes/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});


/*/system/
    autoloader.php
    config.inc.php
    settings.php
    functions.php
    plugin.php
    widget.php
    multi_language.php
/includes/
    /classes/
        Template.php   ← hier rein kommt deine neue Klasse
        Theme.php      ← wenn du willst
        PluginManager.php
    /themes/
        /default/
            /templates/
    /plugins/
        /[pluginname]/
            /templates/
index.php*/