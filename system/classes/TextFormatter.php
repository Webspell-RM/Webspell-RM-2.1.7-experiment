<?php

class TextFormatter
{
    /**
     * Wandelt Zeilenumbrüche in <br> um (für HTML-Ausgabe).
     */
    public static function toHtml($text)
    {
        return nl2br(htmlspecialchars($text));
    }

    /**
     * Wandelt Windows- und Mac-Zeilenumbrüche in Unix-Zeilenumbrüche (\n).
     */
    public static function normalizeNewlines($text)
    {
        return str_replace(["\r\n", "\r"], "\n", $text);
    }

    /**
     * Entfernt alle Zeilenumbrüche.
     */
    public static function removeNewlines($text)
    {
        return str_replace(["\r\n", "\r", "\n"], '', $text);
    }

    /**
     * Gibt ein Array aller Zeilen zurück.
     */
    public static function toLines($text)
    {
        $normalized = self::normalizeNewlines($text);
        return explode("\n", $normalized);
    }
}
