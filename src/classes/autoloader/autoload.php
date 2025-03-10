<?php
spl_autoload_register(static function(string $fqcn) {
    $segments = explode('\\', $fqcn);
    $className = end($segments);

    if (!isset($segments[1])) {
        throw new Exception("Namespace incorrect pour : " . $fqcn);
    }

    // Définition des chemins vers les dossiers
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR; // Aller à la racine de "classes"

    $folders = [
        'provider'   => 'provider',
        'controller' => 'controller',
        'model'      => 'model',
        'tests'      => '../tests' // Les tests sont en dehors de "classes"
    ];

    $folder = $folders[$segments[1]] ?? null;

    if (!$folder) {
        throw new Exception("Impossible de charger la classe : " . $fqcn);
    }

    $path = realpath($baseDir . $folder . DIRECTORY_SEPARATOR . $className . '.php');

    if ($path && file_exists($path)) {
        require_once $path;
    } else {
        throw new Exception("Fichier introuvable : " . $path);
    }
});
