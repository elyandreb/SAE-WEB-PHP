<?php

require_once __DIR__ . '/classes/autoloader/autoload.php'; // Charge l'autoload
use classes\Provider;
use classes\Controller;
session_start();

try {
    $action = $_GET['action'] ?? 'home';

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_name'])) {
        require_once __DIR__ . '/templates/login_form.php';
        exit;
    }
    // Gérer le logout en premier
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }


    if ($action === 'home') {
        require_once __DIR__ . '/templates/header.php';
        $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();

    } 

    
    
    if ($action === 'ask_avis') {
        $controller->addAvisToResto();
        header('Location: templates/FormAvis.php');
    }


} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>