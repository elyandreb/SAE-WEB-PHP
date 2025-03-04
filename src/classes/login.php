<?php
session_start();
require_once 'bd/model_bd.php';
use bd\model_bd;

$db = new model_bd();
$email = $_POST['email'] ?? '';
$mdp = $_POST['mdp'] ?? '';
$errorMessage = '';

if (!empty($email) && !empty($mdp)) {
    $user = $db->loginUser($email, $mdp);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id_u'];
        $_SESSION['user_role'] = $user['le_role'];
        $_SESSION['user_name'] = $user['nom_u'];

        header('Location: ../index.php?action=home');
        exit();
    } else {
        $errorMessage = "Email ou mot de passe incorrect.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "Veuillez remplir tous les champs.";
}

// Inclure le formulaire de connexion et passer l'erreur
include_once '../templates/login_form.php';
?>

