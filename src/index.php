<?php

require_once __DIR__ . '/classes/autoloader/autoload.php'; // Charge l'autoload
use classes\Provider;
use classes\Controller;


try {
    $action = $_GET['action'] ?? 'home';

    // Gérer le logout en premier
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
    print_r($action);
    if ($action === 'home') {
        $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();
        $action = $_GET['action'] ?? 'home';
    }

} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>