<?php
namespace classes\controller;
use classes\model\UserModel;

class ControllerProfil {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function afficherProfil() {
        error_log("Afficher le profil de l'utilisateur");
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?action=login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $preferences = $this->userModel->getUserPreferences($userId);

        include_once ROOT_PATH . '/views/profil.php';
    }

    
}
?>
