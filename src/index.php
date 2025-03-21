<?php

session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/autoloader/autoload.php';
use classes\provider\Provider;
use classes\controller\ControllerAvis;
use classes\controller\ControllerRestaurant;
use classes\controller\ControllerLogin;
use classes\controller\ControllerRegister;
use classes\controller\ControllerPreferences;
use classes\controller\ControllerFavoris;
use classes\controller\ControllerProfil;
use classes\controller\ControllerCuisine;
use classes\model\Model_bd;
use classes\model\RestaurantModel;
use classes\model\CritiqueModel;

// Récupérer l'action dès le début

$action = $_GET['action'] ?? 'home';

try {
    $db = Model_bd::getInstance();
    $critique_model = new CritiqueModel();

    $controller_restaurants = new ControllerRestaurant();
    $restaurants = $controller_restaurants->getRestaurants();

    $controller_avis = new ControllerAvis();
    $avis = $controller_avis->getAvis(); 

    $bon_restos = $controller_restaurants-> getRestaurantsTriee();
    $_SESSION['bon_restos'] = $bon_restos;
    $_SESSION['restaurants'] = $restaurants;
    $_SESSION['avis'] = $avis; 

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

    if ($action === 'profil') {
        $controllerProfil = new ControllerProfil();
        $controllerProfil->afficherProfil();
        exit;
    }

    if ($action === 'editProfil') {
        $controllerProfil = new ControllerProfil();
        $controllerProfil->modifierProfil();
        exit;
    }

    
    
    $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');

    if ($action === 'home') {
        $controller_favoris = new ControllerFavoris();
        if (!empty($id_u)) {
            $_SESSION['favoris'] = $controller_favoris->getFavorisByUser($id_u);
        } else {
            $_SESSION['favoris'] = [];
        }
        $controllerPreferences = new ControllerPreferences();
        $_SESSION['preferences'] = $controllerPreferences->getPreferences();
        $controllerCuisine = new ControllerCuisine();
        $_SESSION['types_cuisines'] = $controllerCuisine->getAllCuisines();
        $_SESSION['types_restaurants'] = array_unique(array_column($_SESSION['restaurants'] ?? [], 'type_res'));


        require_once __DIR__ . '/views/header.php';
        require_once __DIR__ . '/views/les_restaurants.php';
        exit;
    }

    elseif ($action === 'add_avis' || $action === 'remove_avis'|| $action === 'modify_avis' || $action === 'mes_reviews'  || $action === 'gerer-avis'  || $action === 'les_avis') {
        $controller_avis = new ControllerAvis();
    
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
        
        elseif ($action === 'mes_reviews') {
            $_SESSION['avis_persos'] = $controller_avis->get_reviews($id_u);
            require_once __DIR__ . '/views/les_avis.php';
            exit;
        }
        elseif ($action === 'les_avis') {
            require_once __DIR__ . '/views/les_avis.php';
            exit;
        }

        elseif ($action === 'modify_avis') {
            $controller_avis->modify_avis();
            exit;
        }
        elseif ($action === 'gerer-avis') {
            require_once __DIR__ . '/views/gerer_avis.php';
            exit;
        }
    
        
    }
     
    elseif ($action==='toggle-favoris' || $action ==='les-favoris') {
        $controller_favoris = new ControllerFavoris();
       
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