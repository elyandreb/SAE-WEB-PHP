<?php
namespace classes\controller;
use classes\model\Model_bd;

class ControllerPreferences {
    private Model_bd $model_bd;

    public function __construct(Model_bd $model_bd) {
        $this->model_bd = $model_bd;
    }

    public function preferences(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /views/login_form.php');
            exit;
        }
        $errorMessage = '';
    
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $preferences = $_POST['preferences'] ?? [];

            if (!empty($preferences)) {
                // Enregistrer les préférences de l'utilisateur
                if ($this->model_bd->savePreferences($user_id, $preferences)) {
                    // Rediriger vers la page d'accueil après l'enregistrement des préférences
                    header('Location: /index.php?action=home');
                    exit();
                } else {
                    $errorMessage = "Veuillez sélectionner au moins un type de restaurant.";
                } 
            }
        }
    
        // Récupérer les types de restaurants disponibles
        $restaurantTypes = $this->model_bd->getRestaurantTypes();

        include_once ROOT_PATH . '/views/preferences_form.php';
    }
}