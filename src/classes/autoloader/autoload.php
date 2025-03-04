<?php
spl_autoload_register(static function(string $fqcn) {

    $segments = explode('\\', $fqcn);
    $className = end($segments);

    if($segments[1] === 'provider') {
        $path = __DIR__ . '../../provider/' . $className . '.php';
    
         
    } elseif ($segments[1] === 'controller'){
        $path = __DIR__ . '../../controller/' . $className . '.php';
    
    } elseif ($segments[0] === 'tests'){
        $path = __DIR__ . '../../tests/' . $className . '.php';
    }
    elseif ($segments[1] === 'model') {
        // Si c'est dans le namespace BD :
        $path = __DIR__ . '../../model/' . $className . '.php';
    }
    
    require_once $path;
});