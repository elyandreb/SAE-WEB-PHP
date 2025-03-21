<?php

namespace classes\controller;

use classes\model\UserModel;

class ControllerLogin {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login(): void {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $mdp = $_POST['mdp'] ?? '';

            if (!empty($email) && !empty($mdp)) {
                $user = $this->userModel->loginUser($email, $mdp);
                
                if ($user) {

                    session_regenerate_id(true);
                    
                    $_SESSION = array();

                    $_SESSION['user_id'] = $user['id_u'];
                    $_SESSION['user_role'] = $user['le_role'];
                    $_SESSION['user_name'] = $user['nom_u'];
                
                    header('Location: ../index.php?action=home');
                    exit();
                } else {
                    $errorMessage = "Email ou mot de passe incorrect.";
                }
            } else {
                $errorMessage = "Veuillez remplir tous les champs.";
            }
        }
        
        include_once ROOT_PATH . '/views/login_form.php';


    }
}
