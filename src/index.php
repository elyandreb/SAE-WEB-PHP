<?php
session_start();
require_once __DIR__ . '/classes/autoloader/autoload.php';
use classes\Provider;
use classes\Controller;

// Récupérer l'action dès le début
$action = $_GET['action'] ?? 'home';

// Traitement immédiat de l'action AJAX pour toggle-favoris
if (preg_match('#^toggle-favoris/(.+)$#', $action, $matches)) {
    $restaurants = Provider::getRestaurants('restaurants_orleans');
    $controller = new Controller($restaurants);
    $idRestaurant = urldecode($matches[1]);
    $controller->toggleFavorite($idRestaurant);
    exit; // Arrêter l'exécution après l'envoi de la réponse JSON
}
?>
<!doctype html>
<html>
<head>
    <title>IU</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/style.css">
    <script src="../static/js/favoris.js" defer></script>
</head>
<body>

session_start();


<?php
try {

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
    elseif (preg_match('#toggle-favoris/(.+)#', $action, $matches)) {
        $idRestaurant = urldecode($matches[1]);
        $controller->toggleFavorite($idRestaurant);
        exit;

    } elseif ($action === 'ask_avis') {
        $controller->addAvisToResto();
        header('Location: templates/FormAvis.php');
        exit;
    }


} catch (Exception $e) {
    echo ''. $e->getMessage();
}
?>