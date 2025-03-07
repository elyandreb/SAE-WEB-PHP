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

        $user_id = $_SESSION['user_id'];
    
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $preferences = $_POST['preferences'] ?? [];
            
            if (!empty($preferences)) {
                // Enregistrer les préférences de l'utilisateur
                if ($this->userModel->saveUserPreferences($user_id, $preferences)) {
                    header('Location: /index.php?action=home'); // Redirection après enregistrement
                    exit();
                } else {
                    $errorMessage = "Une erreur est survenue. Veuillez réessayer.";
                }
            } else {
                $errorMessage = "Veuillez sélectionner au moins un type de restaurant.";
            }
        }
    
        // Récupérer tous les types de restaurants
        $restaurantTypes = $this->typeCuisineModel->getAllTypesCuisine();

        // Récupérer les préférences actuelles de l'utilisateur
        $selectedPreferences = $this->userModel->getUserPreferencesId($user_id);

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

