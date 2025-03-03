<?php

require_once __DIR__ . '/classes/autoloader/autoload.php'; // Charge l'autoload
use classes\Provider;
use classes\Controller;


try {
    $action = $_GET['action'] ?? 'home';
    $action = "ask_avis";
    
    // Gérer le logout en premier
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');
    $controller = new Controller(restaurants: $restaurants);


    if ($action === 'home') {
        $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();

    } 

    
    
    if ($action === 'ask_avis') {
        $controller->askAvis();
        header('Location: templates/FormAvis.php');
    }


} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>