<?php
spl_autoload_register(static function(string $fqcn) {

    $segments = explode('\\', $fqcn);
    $className = end($segments);
    
    if ($segments[0] === 'classes') {
        // Si c'est dans le namespace Classes :
        $path = __DIR__ . '/../' . $className . '.php';

    } elseif ($segments[0] === 'tests'){
        $path = __DIR__ . '../../tests/' . $className . '.php';
    }
    elseif ($segments[0] === 'bd') {
        // Si c'est dans le namespace BD :
        $path = __DIR__ . '/../bd/' . $className . '.php';
    }
    
    require_once $path;
});