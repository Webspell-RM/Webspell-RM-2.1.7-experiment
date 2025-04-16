<?php

$baseDir = __DIR__; // Arbeitsverzeichnis (z. B. dein Webspell-Root)

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));

foreach ($rii as $file) {
    if (!$file->isFile()) continue;

    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;

    $content = file_get_contents($file->getPathname());

    // Ersetze typische Varianten: " . PREFIX . "users
    $content = preg_replace_callback('/"\s*\.\s*PREFIX\s*\.\s*"([a-zA-Z0-9_]+)"/', function ($matches) {
        return '"' . $matches[1] . '"';
    }, $content);

    // Speichern, wenn Änderungen vorgenommen wurden
    file_put_contents($file->getPathname(), $content);
}

echo "Alle PREFIX-Verwendungen entfernt.\n";
