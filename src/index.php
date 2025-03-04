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

    if ($action === 'home') {
        require_once __DIR__ . '/views/header.php';
       
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();

    } 
    elseif (preg_match('#toggle-favoris/(.+)#', $action, $matches)) {
        $idRestaurant = urldecode($matches[1]);
        $controller->toggleFavorite($idRestaurant);
        exit;
    }

    $avis = $db->getAvis();
    $_SESSION['avis'] = $avis; // Stocker les avis dans la session
    
    if ($action === 'add_avis') {
        $controller_avis = new ControllerAvis(model_bd: $db);
        $controller_avis-> add_avis();

        header('Location: views/add_avis.php');
        exit;
    }
    elseif ($action === 'les_avis') {
        $avis = $db->getAvis();
        $_SESSION['avis'] = $avis; // Stocker les avis dans la session
        
        header('Location: views/les_avis.php');
        exit;
    }

   

} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>