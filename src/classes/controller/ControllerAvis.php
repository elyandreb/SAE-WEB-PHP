<?php
namespace classes\controller;
use classes\model\Model_bd;

class ControllerAvis {
    private Model_bd $model_bd;

    public function __construct(Model_bd $model_bd) {
        $this->model_bd = $model_bd;
    }

    public function add_avis(): void {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $note_r = filter_input(INPUT_POST, "note_reception", FILTER_VALIDATE_INT);
            $note_p = filter_input(INPUT_POST, "note_plats", FILTER_VALIDATE_INT);
            $note_s = filter_input(INPUT_POST, "note_service", FILTER_VALIDATE_INT);
            $commentaire = filter_input(INPUT_POST, "commentaire", FILTER_SANITIZE_STRING);
            $siret = filter_input(INPUT_POST, "siret", FILTER_SANITIZE_STRING);
            $id_u = filter_input(INPUT_POST, "id_u", FILTER_VALIDATE_INT);
    
            if (!$note_r || !$note_p || !$note_s || !$siret || !$id_u) {
                echo json_encode(["status" => "error", "message" => "Données manquantes ou invalides."]);
                return;
            }
    
            $success = $this->model_bd->addCritique($note_r, $commentaire, $siret, $id_u, $note_p, $note_s);
            
            header('Content-Type: application/json'); // 🔥 Indique qu'on renvoie du JSON
            echo json_encode($success 
                ? ["status" => "success", "message" => "Avis ajouté avec succès !"] 
                : ["status" => "error", "message" => "Erreur lors de l'ajout de l'avis."]
            );
        }
    }
    

    public function get_avis(): void {
        $avis = $this->model_bd->getAvis();
        echo json_encode($avis);
    }
    
    
    public function remove_avis(): void {
        header('Content-Type: application/json');
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id_critique = filter_input(INPUT_POST, 'id_critique', FILTER_VALIDATE_INT);

            if ($id_critique === false || $id_critique === null) {
                echo json_encode(["status" => "error", "message" => "ID de critique invalide."]);
                return;
            }

            $success = $this->model_bd->deleteCritique($id_critique);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Avis supprimé avec succès !"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'avis."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Méthode non autorisée."]);
        }
    }
}
