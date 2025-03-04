<?php
session_start();
require_once 'bd/model_bd.php';
use bd\model_bd;

$db = new model_bd();
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $mdp_confirm = $_POST['mdp_confirm'] ?? '';
    $role = 'utilisateur'; // Rôle par défaut

    if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($mdp_confirm)) {
        $errorMessage = "Tous les champs sont obligatoires.";
    } elseif ($mdp !== $mdp_confirm) {
        $errorMessage = "Les mots de passe ne correspondent pas.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "L'email n'est pas valide.";
    } else {
        // Vérifier si l'email existe déjà
        if ($db->checkEmailExists($email)) {
            $errorMessage = "Cet email est déjà utilisé.";
        } else {
            // Inscription de l'utilisateur
            if ($db->registerUser($nom, $prenom, $email, $mdp, $role)) {
                $_SESSION['user_id'] = $db->getUserIdByEmail($email);
                $_SESSION['user_role'] = $role;
                $_SESSION['user_name'] = $nom;
                
                header('Location: /index.php?action=home');
                exit();
            } else {
                $errorMessage = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
}

// Inclure le formulaire
include '../templates/register_form.php';
?>
