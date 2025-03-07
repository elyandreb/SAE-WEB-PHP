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

    public function modifierProfil() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $errorMessage = '';
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (!empty($nom) && !empty($prenom) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->userModel->updateUser($userId, $nom, $prenom, $email);
                $_SESSION['user_name'] = $nom;
                $successMessage = "Profil mis à jour avec succès.";
            } else {
                $errorMessage = "Veuillez remplir tous les champs correctement.";
            }

            if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {
                if ($newPassword === $confirmPassword) {
                    if ($this->userModel->updateUserPassword($userId, $oldPassword, $newPassword)) {
                        $successMessage .= " Mot de passe mis à jour avec succès.";
                    } else {
                        $errorMessage = "Ancien mot de passe incorrect.";
                    }
                } else {
                    $errorMessage = "Les nouveaux mots de passe ne correspondent pas.";
                }
            }
        }

        include_once ROOT_PATH . '/views/edit_profil.php';
    }
}
?>
