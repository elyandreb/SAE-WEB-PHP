<?php

session_start();

require_once __DIR__ . '/classes/autoloader/autoload.php';
define('ROOT_PATH', __DIR__);
use classes\provider\Provider;
use classes\controller\ControllerAvis;
use classes\controller\Controller;
use classes\controller\ControllerLogin;
use classes\controller\ControllerRegister;
use classes\model\Model_bd;


// Récupérer l'action dès le début
$action = $_GET['action'] ?? 'home';

try {
    $db = new Model_bd();
    $db-> init_resto_json();


    // Gérer le logout en premier
    if ($action === 'logout') {
        session_unset();
        session_destroy();
        header('Location: views/login_form.php');
        exit;
    }


    if (!empty($_POST['username'])) {
        $_SESSION['utilisateur'] = htmlspecialchars($_POST['username']);
        header('Location: index.php?action=home');
        exit;
    }

    if ($action === 'register') {
        $controllerRegister = new ControllerRegister($db);
        $controllerRegister->register();
        exit;
    }

    if ($action === 'login') {
        $controllerLogin = new ControllerLogin($db);
        $controllerLogin->login();
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

    $avis = $db->getAvis();
    $_SESSION['avis'] = $avis; // Stocker les avis dans la session

    $restaurants =$db->getRestaurants();
    $_SESSION['restaurants'] = $restaurants;
    
    if ($action === 'home') {
        require_once __DIR__ . '/views/header.php';
        require_once __DIR__ . '/views/les_restaurants.php';
        exit ;
    }
    elseif ($action === 'add_avis') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller_avis = new ControllerAvis(model_bd: $db);
            $controller_avis->add_avis();
            header('Location: index.php?action=home');
            exit;
        } else {
            require_once __DIR__ . '/views/add_avis.php';
            exit;
        }
    }
    
    
    elseif ($action === 'get_avis') {
        $controller_avis = new ControllerAvis(model_bd: $db);
        $controller_avis->get_avis();
        exit;
    }
    elseif ($action === 'les_avis') {
        if (!isset($_GET['siret'])) {
            die('Erreur : SIRET manquant.');
        }
    
        $siret = $_GET['siret'];
    
        // Récupérer les avis depuis le modèle
        $controller_avis = new ControllerAvis($db);
        $controller_avis->get_avis();
    
        // Afficher la page correspondante
        require_once __DIR__ . '/views/les_avis.php';
        exit;
    }
    
    
    
    elseif ($action === 'remove_avis') {
        $controller_avis = new ControllerAvis(model_bd: $db);
        $controller_avis->remove_avis();
        header('Location: views/les_avis.php');
        exit;
    }

    elseif (preg_match('#toggle-favoris/(.+)#', $action, $matches)) {
        $idRestaurant = urldecode($matches[1]);
        $controller->toggleFavorite($idRestaurant);
        exit;
    }
    
    
    
} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>