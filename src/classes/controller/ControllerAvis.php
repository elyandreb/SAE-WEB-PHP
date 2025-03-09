<?php
namespace classes\controller;

use classes\model\CritiqueModel;

class ControllerAvis {
    private CritiqueModel $critiqueModel;

    public function __construct() {
        $this->critiqueModel = new CritiqueModel();
    }

    public function add_avis() {
        if (!isset($_POST['id_res'], $_POST['id_u'], $_POST['note_reception'], $_POST['note_plats'], $_POST['note_service'], $_POST['commentaire'])) {
            die('Erreur : Données manquantes.');
        }
    
        $id_res = htmlspecialchars($_POST['id_res']);
        $id_u = htmlspecialchars($_POST['id_u']);
        $note_reception = (int) $_POST['note_reception'];
        $note_plats = (int) $_POST['note_plats'];
        $note_service = (int) $_POST['note_service'];
        $commentaire = htmlspecialchars($_POST['commentaire']);
    
        if ($note_reception < 1 || $note_reception > 5 ||
            $note_plats < 1 || $note_plats > 5 ||
            $note_service < 1 || $note_service > 5) {
            die('Erreur : Notes invalides.');
        }
    
        // Ajout dans la base de données
        $this->critiqueModel->addCritique($note_reception, $commentaire, $id_res, $id_u, $note_plats, $note_service);
    
        // Redirection correcte après l'ajout
        header('Location: index.php?action=les_avis&id_res=' . urlencode(string: $id_res).'&nomRes='.urlencode(string: $_POST['nomRes']));
        exit;
    }
    public function getCritiquesByRestaurant($id_res): array {
        return $this->critiqueModel->getCritiquesByRestaurant($id_res);
    }
    public function getMoyenneCritiquesByRestaurant($id_res) {
        if (isset($id_res)) {
            $moyenne = $this->critiqueModel->getMoyenneCritiquesByRestaurant($id_res);
        }
        $moyenne = $moyenne ?? 0;
        return round($moyenne, 1);
    }

    public function getAvis() {
        
        return $this->critiqueModel->getAvis();
    }

    public function get_reviews($id_u): array {
        return $this->critiqueModel->getCritiquesByUser($id_u);

    }
    public function getNameUser($id_u) {
        return $this->critiqueModel->getNameUserCritique($id_u);
    }
    
    public function remove_avis($id_c): void {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $id_c = filter_var($id_c, FILTER_VALIDATE_INT);
            
            if ($id_c === false || $id_c === null) {
                echo json_encode(["status" => "error", "message" => "ID de critique invalide."]);
                return;
            }
    
            $success = $this->critiqueModel->deleteCritique($id_c);
           
            if ($success) {
                $controller_restaurant = new ControllerRestaurant();
                $restaurant = $controller_restaurant->getRestaurantById($_GET['id_res']);
                $nomRes = $restaurant['nom_res'] ?? 'Inconnu';
                if ($_SESSION['user_role'] === 'admin') {
                    header("Location: index.php?action=gerer-avis");
                    exit;
                }
                header("Location: index.php?action=les_avis&id_res=" . urlencode($_GET['id_res']) . "&nomRes=" . urlencode($nomRes));

            } else {
                echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'avis."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Méthode non autorisée."]);
        }
    }
    
    public function modify_avis() {
        $errorMessage = '';
        $successMessage = '';
        $critique = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id_c'], $_POST['note_reception'], $_POST['note_plats'], $_POST['note_service'], $_POST['commentaire'])) {
                $errorMessage = 'Erreur : Données manquantes.';
            } else {
                $id_c = filter_var($_POST['id_c'], FILTER_VALIDATE_INT);
                $note_reception = filter_var($_POST['note_reception'], FILTER_VALIDATE_INT);
                $note_plats = filter_var($_POST['note_plats'], FILTER_VALIDATE_INT);
                $note_service = filter_var($_POST['note_service'], FILTER_VALIDATE_INT);
                $commentaire = htmlspecialchars($_POST['commentaire']);
    
                if ($id_c === false || $note_reception === false || $note_plats === false || $note_service === false) {
                    $errorMessage = 'Erreur : Données invalides.';
                } elseif ($note_reception < 1 || $note_reception > 5 || 
                          $note_plats < 1 || $note_plats > 5 || 
                          $note_service < 1 || $note_service > 5) {
                    $errorMessage = 'Erreur : Notes invalides.';
                } else {
                    
                    $success = $this->critiqueModel->updateCritique($id_c, $commentaire,  $note_reception, $note_plats, $note_service);
                    if ($success) {
                        header("Location: index.php?action=les_avis&id_res=" . urlencode($_POST['id_res']) . '&nomRes=' . urlencode($_POST['nomRes']));
                        exit;
                    } else {
                        $errorMessage = 'Erreur : Échec de la mise à jour de l\'avis.';
                    }
                }
            }
        } else {
            // Récupération de l'avis existant si on vient en mode édition
            if (isset($_GET['id_c'])) {
                $id_c = filter_var($_GET['id_c'], FILTER_VALIDATE_INT);
                if ($id_c) {
                    $critique = $this->critiqueModel->getCritiqueById($id_c);
                  
                }
            }
        }
    
        include_once ROOT_PATH . '/views/edit_avis.php';
    }
    
    
    
    
}
