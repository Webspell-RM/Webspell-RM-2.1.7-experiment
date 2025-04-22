<?php

class multiLanguage {

    public $language;
    public $availableLanguages = array();

    public function __construct($lang) {
        $this->language = $lang;
    }

    /**
     * Ermittelt alle verfügbaren Sprachen im gegebenen Text.
     * 
     * @param string $text Der zu untersuchende Text mit [[lang:xx]]-Tags
     */
    public function detectLanguages($text) {
        preg_match_all('/\[\[lang:([a-z]{2})\]\]/i', $text, $matches);
        $this->availableLanguages = array_unique($matches[1]);
    }

    /**
     * Gibt den Text in der ausgewählten Sprache zurück.
     * 
     * @param string $text Der mehrsprachige Text
     * @return string Der passende Textausschnitt
     */
    public function getTextByLanguage($text) {
        if (in_array($this->language, $this->availableLanguages)) {
            return $this->getTextByTag($this->language, $text);
        } elseif (!empty($this->availableLanguages)) {
            return $this->getTextByTag($this->availableLanguages[0], $text);
        } else {
            return $text;
        }
    }

    /**
     * Holt den konkreten Textabschnitt einer Sprache
     * 
     * @param string $language Sprachkürzel
     * @param string $text Ursprünglicher Text mit Sprachblöcken
     * @return string Nur der passende Text
     */
    private function getTextByTag($language, $text) {
        $pattern = '/\[\[lang:' . preg_quote($language, '/') . '\]\](.*?)(?=\[\[lang:|$)/is';
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }
}
