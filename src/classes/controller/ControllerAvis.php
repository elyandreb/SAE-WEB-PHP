<?php
namespace classes\controller;
use classes\model\Model_bd;

class ControllerAvis {
    private Model_bd $model_bd;

    public function __construct(Model_bd $model_bd) {
        $this->model_bd = $model_bd;
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
        $this->model_bd->addCritique($note_reception, $commentaire, $id_res, $id_u, $note_plats, $note_service);
        header('Location: index.php?action=les_avis&id_res=' . urlencode(string: $id_res));
        exit;
    }

    public function get_avis($id_u, $id_res): array {
        return $this->model_bd->getCritiquesByUserResto($id_u, $id_res);

    }
    
    
    public function remove_avis($id_c): void {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $id_c = filter_var($id_c, FILTER_VALIDATE_INT);
            
            if ($id_c === false || $id_c === null) {
                echo json_encode(["status" => "error", "message" => "ID de critique invalide."]);
                return;
            }
    
            $success = $this->model_bd->deleteCritique($id_c);
           
            if ($success) {
                header("Location: index.php?action=les_avis&id_res=" . urlencode($_GET['id_res']));
                exit;
            } else {
                echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'avis."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Méthode non autorisée."]);
        }
    }
    
}
