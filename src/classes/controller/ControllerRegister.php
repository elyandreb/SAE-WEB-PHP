<?php
namespace classes\controller;
use classes\model\Model_bd;
use classes\model\UserModel;

class ControllerRegister {
    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function register(): void {
        $errorMessage = '';
    
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire

            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $mdp = $_POST['mdp'] ?? '';
            $mdp_confirm = $_POST['mdp_confirm'] ?? '';
            $role = 'utilisateur'; // Par défaut, rôle utilisateur
    
            // Validation des données
            if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($mdp_confirm)) {
                $errorMessage = "Tous les champs sont obligatoires.";
            } elseif ($mdp !== $mdp_confirm) {
                $errorMessage = "Les mots de passe ne correspondent pas.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "L'email n'est pas valide.";
            } else {
                // Vérifier si l'email existe déjà
                if ($this->userModel->checkEmailExists($email)) {
                    $errorMessage = "Cet email est déjà utilisé.";
                } else {
                    // Ajout du débogage pour vérifier si la méthode est bien appelée
                    error_log("Les données sont validées, inscription de l'utilisateur...");
                    
                    // Si tout est validé, enregistrer l'utilisateur
                    if ($this->userModel->registerUser($nom, $prenom, $email, $mdp, $role)) {
                        $_SESSION['user_id'] = $this->userModel->getUserIdByEmail($email);
                        $_SESSION['user_role'] = $role;
                        $_SESSION['user_name'] = $nom;
                        header('Location: /index.php?action=preferences');                        
                        exit();
                    } else {
                        $errorMessage = "Erreur lors de l'inscription. Veuillez réessayer.";
                    }
                }
            }
        }
    
        // Afficher le formulaire d'inscription avec les éventuelles erreurs
        include_once ROOT_PATH . '/views/register_form.php';
    }
    
}

