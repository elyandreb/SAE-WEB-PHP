<?php

session_start();

require_once __DIR__ . '/classes/autoloader/autoload.php';

use classes\provider\Provider;
use classes\controller\ControllerAvis;
use classes\controller\Controller;
use classes\model\Model_bd;


// Récupérer l'action dès le début
$action = $_GET['action'] ?? 'home';

try {
    $db = new Model_bd();


    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_name'])) {
        require_once __DIR__ . '/views/login_form.php';
        exit;
    }

    // Gérer le logout en premier
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');

    // Traitement immédiat de l'action AJAX pour toggle-favoris
    if (preg_match('#^toggle-favoris/(.+)$#', $action, $matches)) {
     $controller = new Controller($restaurants);
     $idRestaurant = urldecode($matches[1]);
     $controller->toggleFavorite($idRestaurant);
     exit; // Arrêter l'exécution après l'envoi de la réponse JSON
    }

    $action ='ask_avis';

    if ($action === 'home') {
        require_once __DIR__ . '/views/header.php';
       
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();

    } 
    elseif (preg_match('#toggle-favoris/(.+)#', $action, $matches)) {
        $idRestaurant = urldecode($matches[1]);
        $controller->toggleFavorite($idRestaurant);
        exit;

    } elseif ($action === 'ask_avis') {
        $controller_avis = new ControllerAvis();
        $controller_avis->showRestaurants();

        $controller->addAvisToResto();
        header('Location: views/add_avis.php');
        exit;
    }


} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>