<?php

require_once __DIR__ . '/classes/autoloader/autoload.php'; // Charge l'autoload
session_start(); // Démarre la session
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

    // Gérer le login
    if ($action === 'login') {
        if (!empty($_POST['email'])) {
            $_SESSION['email'] = htmlspecialchars($_POST['email']);
            header('Location: index.php?action=home');
            exit;
        }
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['email'])) {
        require_once __DIR__ . '/templates/login.php';
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