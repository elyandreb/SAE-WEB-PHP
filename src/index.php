<?php

session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/autoloader/autoload.php';
use classes\provider\Provider;
use classes\controller\ControllerAvis;
use classes\controller\Controller;
use classes\controller\ControllerLogin;
use classes\controller\ControllerRegister;
use classes\controller\ControllerPreferences;
use classes\controller\ControllerFavoris;
use classes\model\Model_bd;
use classes\model\RestaurantModel;
use classes\model\CritiqueModel;

// Récupérer l'action dès le début
$action = $_GET['action'] ?? 'home';

try {
    $db = Model_bd::getInstance();
    $resto_model = new RestaurantModel();
    $critique_model = new CritiqueModel();
    $restaurants = $resto_model->getRestaurants();
    $_SESSION['restaurants'] = $restaurants;
    $id_u = $_SESSION['user_id'] ?? null; 
    

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
        $controllerRegister = new ControllerRegister();
        $controllerRegister->register();
        exit;
    }

    if ($action === 'login') {
        $controllerLogin = new ControllerLogin();
        $controllerLogin->login();
        exit;
    }

    if ($action === 'preferences') {
        $controllerPreferences = new ControllerPreferences();
        $controllerPreferences->preferences();
        exit;
    }
    
    $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');


    // Traitement immédiat de l'action AJAX pour toggle-favoris
    if (preg_match('#^toggle-favoris/(.+)$#', $action, $matches)) {
     $controller = new Controller();
     $idRestaurant = urldecode($matches[1]);
     $controller->toggleFavorite($idRestaurant);
     exit; // Arrêter l'exécution après l'envoi de la réponse JSON
    }

    $avis = $critique_model->getAvis();
    $_SESSION['avis'] = $avis; // Stocker les avis dans la session

    $restaurants =$resto_model->getRestaurants();
    $_SESSION['restaurants'] = $restaurants;
    
    if ($action === 'home') {
        $controller_favoris = new ControllerFavoris($db);
        $_SESSION['favoris'] = $controller_favoris->getFavorisByUser($id_u);
        require_once __DIR__ . '/views/header.php';
        require_once __DIR__ . '/views/les_restaurants.php';
        exit;
    }

    elseif ($action === 'add_avis' || $action === 'les_avis' || $action === 'remove_avis') {
        $controller_avis = new ControllerAvis($db);
    
        if ($action === 'add_avis') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $controller_avis->add_avis();
                header('Location: index.php?action=home');
                exit;
            } else {
                require_once __DIR__ . '/views/add_avis.php';
                exit;
            }
        }
        elseif ($action === 'remove_avis') {
            if (!isset($_GET['id_c']) || !isset($_GET['id_res'])) {
                die('Erreur : ID de la critique ou du restaurant manquant.');
            }
            
            $id_c = $_GET['id_c'];
            $controller_avis->remove_avis($id_c);
            require_once __DIR__ . '/views/les_avis.php';
            exit;
        }
        
        elseif ($action === 'les_avis') {
            if (!isset($_GET['id_res'])) {
                die('Erreur : ID du restaurant manquant.');
            }
    
            $id_res = $_GET['id_res'];
            $_SESSION['avis'] = $controller_avis->get_avis($id_u, $id_res);
            require_once __DIR__ . '/views/les_avis.php';
            exit;
        }
    }

    elseif ($action==='toggle-favoris' || $action ==='les-favoris') {
        $controller_favoris = new ControllerFavoris($db);
       
        if ($action === 'toggle-favoris') {
            $controller_favoris->toggleFavorite($_GET['id'],$id_u);
            header('Location: index.php?action=home');
            exit;
        }
        elseif( $action === 'les-favoris') {
            $_SESSION['favoris'] = $controller_favoris->getFavorisByUser($id_u);
            require_once __DIR__ . '/views/les_favoris.php';
            exit;
        }
        
    }
    
    
    
} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>