<?php
session_start();
require_once __DIR__ . '/classes/autoloader/autoload.php';
require_once __DIR__ . '/config.php';
use classes\provider\Provider;
use classes\controller\ControllerAvis;
use classes\controller\Controller;
use classes\controller\ControllerLogin;
use classes\controller\ControllerRegister;
use classes\controller\ControllerPreferences;
use classes\controller\ControllerFavoris;
use classes\model\Model_bd;

// Récupérer l'action dès le début
$action = $_GET['action'] ?? 'home';

try {
    $db = new Model_bd();
    $restaurants = Provider::getRestaurants(fichier: 'restaurants_orleans');
    $restaurants = $db->getRestaurants();
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
        $controllerRegister = new ControllerRegister($db);
        $controllerRegister->register();
        exit;
    }

    if ($action === 'login') {
        $controllerLogin = new ControllerLogin($db);
        $controllerLogin->login();
        exit;
    }

    if ($action === 'preferences') {
        $controllerPreferences = new ControllerPreferences($db);
        $controllerPreferences->preferences();
        exit;
    }
    
 
    if ($action === 'home') {
        require_once __DIR__ . '/views/header.php';
        require_once __DIR__ . '/views/les_restaurants.php';
        exit;
    }

    elseif ($action === 'add_avis' || $action === 'les_avis' || $action === 'remove_avis') {
        $controller_avis = new ControllerAvis(model_bd: $db);
    
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
            $favoris = $controller_favoris->getFavorisByUser($id_u);
            $_SESSION['favoris'] = $favoris; 
            require_once __DIR__ . '/views/les_favoris.php';
            exit;
        }
        
    }
    
    
    
} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>