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

    //Renvoie à la page d'accueil avec l'affichage des restaurants
    if ($action === 'home') {
        require_once __DIR__ . '/views/header.php';
       
        $controller = new Controller(restaurants: $restaurants);
        $controller->showRestaurants();

    } 
    //!!Gestion du bouton des favoris !!!A débugger
    elseif (preg_match('#toggle-favoris/(.+)#', $action, $matches)) {
        $idRestaurant = urldecode($matches[1]);
        $controller->toggleFavorite($idRestaurant);
        exit;
    }

    $avis = $db->getCritiquesByUser($_SESSION['user_id']);
    $_SESSION['avis'] = $avis; // Stocker les avis dans la session

    //Pour ajouter un avis
    if ($action === 'add_avis') {
        $controller_avis = new ControllerAvis(model_bd: $db);
        $controller_avis-> add_avis();

        header('Location: views/add_avis.php');
        exit;
    }

    //!! Pour les avis
    elseif ($action === 'les_avis') {
        $avis = $db->getCritiquesByUser($_SESSION['user_id']);
        $_SESSION['avis'] = $avis; // Stocker les avis dans la session
        
        header('Location: views/les_avis.php');
        exit;
    }

    //!!Pour les favoris
    // Traitement immédiat de l'action AJAX pour toggle-favoris
    if (preg_match('#^toggle-favoris/(.+)$#', $action, $matches)) {
     $controller = new Controller($restaurants);
     $idRestaurant = urldecode($matches[1]);
     $controller->toggleFavorite($idRestaurant);
     exit; // Arrêter l'exécution après l'envoi de la réponse JSON
    }

    
   

} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>