<?php

namespace webspell;

class TemplateEngine
{
    public string $themes_path;
    public string $template_path;

    public function __construct(string $themes_path = "includes/themes/default/", string $template_path = "templates/")
    {
        $this->themes_path = $themes_path;
        $this->template_path = $template_path;
    }

    /**
     * Lädt einen bestimmten Block aus einer Template-Datei und ersetzt Platzhalter.
     *
     * @param string $file     Dateiname ohne Endung
     * @param string $content  Blockname im HTML-Kommentar (z. B. <!-- datei_block -->)
     * @param array  $replaces Platzhalter-Ersetzungen im Format ['name' => 'Wert']
     *
     * @return string Inhalt des Template-Blocks mit Ersetzungen
     *
     * @throws \Exception Wenn Datei oder Block nicht gefunden wird
     */
    public function loadTemplate(string $file, string $content, array $replaces = []): string
{
    $html_file_path = $this->themes_path . $this->template_path . $file . ".html";

    if (!file_exists($html_file_path)) {
        throw new \Exception("Template-Datei nicht gefunden: $html_file_path");
    }

    $file_content = file_get_contents($html_file_path);

    // Template-Block anhand von Kommentaren extrahieren
    $start_marker = "<!-- " . $file . "_" . $content . " -->";
    $parts = explode($start_marker, $file_content);

    if (!isset($parts[1])) {
        throw new \Exception("Blockmarker '$start_marker' nicht gefunden!");
    }

    $block_parts = explode("<!-- END -->", $parts[1]);
    $template_block = $block_parts[0];

    // Rekursive Verarbeitung verschachtelter Bedingungen
    $template_block = $this->parseConditions($template_block, $replaces);

    // Platzhalter ersetzen
    foreach ($replaces as $key => $value) {
        $value = is_null($value) ? '' : $value;
        $template_block = str_replace('{{ ' . $key . ' }}', $value, $template_block);
    }

    return $template_block;
}


    public function parseConditions(string $template, array $data): string
    {
        // Verarbeitung rekursiv für verschachtelte IFs
        $pattern = '/{{\s*if\s+(\w+)\s*}}(.*?)(({{\s*else\s*}}(.*?))?){{\s*endif\s*}}/s';

        while (preg_match($pattern, $template)) {
            $template = preg_replace_callback($pattern, function ($matches) use ($data) {
                $condition = $matches[1];
                $trueBlock = $matches[2];
                $falseBlock = isset($matches[5]) ? $matches[5] : '';

                $block = !empty($data[$condition]) ? $trueBlock : $falseBlock;

                // Rekursiv verarbeiten, falls weitere Bedingungen im Block enthalten sind
                return $this->parseConditions($block, $data);
            }, $template);
        }

        return $template;
    }



}
