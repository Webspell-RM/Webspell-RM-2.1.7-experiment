<?php

class Template
{
    public string $themes_path;
    public string $template_path;
    public string $plugins_path;
    public string $admin_path;
    public string $theme;

    public function __construct(
        string $themes_path = 'includes/themes/',
        string $template_path = 'templates/',
        string $plugins_path = 'includes/plugins/',
        string $admin_path = 'templates/',
        string $theme = '' // Default Theme-Name
    ) {
        // Korrekte Pfad-Kombination, ohne doppeltes Theme-Verzeichnis
        $this->themes_path = rtrim($themes_path, '/') . '/';
        $this->template_path = rtrim($template_path, '/') . '/';
        $this->plugins_path = rtrim($plugins_path, '/') . '/';
        $this->admin_path = rtrim($admin_path, '/') . '/';
        $this->theme = $theme; // Nur der Name des Themes
    }

    public function loadTemplate(string $file, string $block, array $replaces = [], string $source = 'theme', string $plugin_name = ''): string
    {
        // Bestimme den Pfad zur Template-Datei basierend auf der Quelle
        $path = $this->getTemplatePath($file, $block, $source, $plugin_name);

        // Überprüfe, ob die Datei existiert
        if (!file_exists($path)) {
            throw new \Exception("Template-Datei nicht gefunden: $path");
        }

        // Lade den Inhalt der Template-Datei
        $content = file_get_contents($path);

        // Block-Marker
        $start_marker = "<!-- " . $file . "_" . $block . " -->";
        $parts = explode($start_marker, $content);

        if (!isset($parts[1])) {
            throw new \Exception("Blockmarker '$start_marker' nicht gefunden!");
        }

        $block_parts = explode("<!-- END -->", $parts[1]);
        $template_block = isset($block_parts[0]) ? $block_parts[0] : '';

        if (trim($template_block) === '') {
            throw new \Exception("Der Template-Block '$file -> $content' ist leer oder fehlt.");
        }

        // Platzhalter ersetzen: {{ name }} → Wert
        if (!empty($replaces)) {
            $converted = [];
            foreach ($replaces as $key => $value) {
                $converted["{{ " . $key . " }}"] = is_scalar($value) ? (string)$value : '';
            }
            $template_block = strtr($template_block, $converted);
        }

        // Bedingungen {{ if key }} ... {{ else }} ... {{ endif }}
        $template = $this->parseConditions($template_block, $replaces);

        return $template;
    }

    // Pfad zum Template je nach Quelle bestimmen
    private function getTemplatePath(string $file, string $block, string $source, string $plugin_name): string
    {
        switch ($source) {
            case "plugin":
                if (empty($plugin_name)) {
                    // Plugin-Name dynamisch ermitteln
                    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    foreach ($backtrace as $trace) {
                        if (isset($trace['file']) && preg_match("#includes/plugins/([^/]+)/#", $trace['file'], $matches)) {
                            $plugin_name = $matches[1];
                            break;
                        }
                    }
                }

                if (!$plugin_name) {
                    throw new Exception("Plugin-Name konnte nicht automatisch ermittelt werden.");
                }

                return "includes/plugins/" . $plugin_name . "/templates/" . $file . ".html";  // Verwende $file anstelle von $template_name
            case 'admin':
                $path = $this->admin_path . $file . '.html';
                if (!file_exists($path)) {
                    throw new \Exception("Template-Datei nicht gefunden: $path");
                }
                return $path;
            case 'theme':
                // Hier wird der Pfad korrekt gesetzt, ohne doppelte "default"
                return $this->themes_path . rtrim($this->theme, '/') . '/' . $this->template_path . $file . '.html';
            default:
                throw new \Exception("Unbekannte Quelle: $source");
        }
    }


    // Verarbeitung von Bedingungen wie {{ if key }} ... {{ endif }}
    private function parseConditions(string $template, array $data): string
    {
        $pattern = '/{{\s*if\s+(\w+)\s*}}(.*?){{\s*endif\s*}}/s';

        while (preg_match($pattern, $template)) {
            $template = preg_replace_callback($pattern, function ($matches) use ($data) {
                $key = $matches[1];
                $content = $matches[2];

                // Prüfe auf {{ else }} für bedingte Logik
                if (strpos($content, '{{ else }}') !== false) {
                    [$trueBlock, $falseBlock] = explode('{{ else }}', $content, 2);
                } else {
                    $trueBlock = $content;
                    $falseBlock = '';
                }

                $value = $data[$key] ?? false;
                return $value ? $trueBlock : $falseBlock;
            }, $template);
        }

        return $template;
    }

    private static ?Template $instance = null;

    public static function setInstance(Template $tpl): void
    {
        self::$instance = $tpl;
    }

    public static function getInstance(): Template
    {
        if (!self::$instance) {
            throw new \Exception("Template-Instanz wurde noch nicht gesetzt.");
        }
        return self::$instance;
    }
}
