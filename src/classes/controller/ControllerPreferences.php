<?php
namespace classes\controller;
use classes\model\TypeCuisineModel;
use classes\model\UserModel;

class ControllerPreferences {
    private UserModel $userModel;
    private TypeCuisineModel $typeCuisineModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->typeCuisineModel = new TypeCuisineModel();
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
            error_log("Préférences de l'utilisateur : " . print_r($preferences, true));

            if (!empty($preferences)) {
                // Enregistrer les préférences de l'utilisateur
                if ($this->userModel->saveUserPreferences($user_id, $preferences)) {
                    // Rediriger vers la page d'accueil après l'enregistrement des préférences
                    header('Location: /index.php?action=home');
                    exit();
                } else {
                    $errorMessage = "Veuillez sélectionner au moins un type de restaurant.";
                } 
            }
        }
    
        // Récupérer les types de restaurants disponibles
        $restaurantTypes = $this->typeCuisineModel->getAllTypesCuisine();

        include_once ROOT_PATH . '/views/preferences_form.php';
    }
    public function getPreferences() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /views/login_form.php');
            exit;
        }
        $user_id = $_SESSION['user_id'];
        return $this->userModel->getUserPreferences($user_id);
    }
}