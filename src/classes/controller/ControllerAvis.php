<?php
namespace classes\controller;
use classes\model\Model_bd;

class ControllerAvis {
    private Model_bd $model_bd;

    public function __construct(Model_bd $model_bd) {
        $this->model_bd = $model_bd;
    }

    public function add_avis(): void {
        print_r('banananannanan;');
        var_dump('banananannanan;');
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Récupère les données envoyées via POST
            $note_r = $_POST["note_reception"] ?? null;
            $note_p = $_POST["note_plats"] ?? null;
            $note_s = $_POST["note_service"] ?? null;
            $commentaire = $_POST["commentaire"] ?? "";
            $siret = $_POST["siret"] ?? null;
            $id_u = $_POST["id_u"] ?? null; // ID de l'utilisateur, à récupérer selon ton système
            print_r($_POST);
            // Vérifie les données nécessaires
            if ($siret && $id_u && $note_r !== null) {
                $success = $this->model_bd->addCritique($note_r, $commentaire, $siret, $id_u, $note_p, $note_s);
                print_r($success);
    
                if ($success) {
                    echo json_encode(["status" => "success", "message" => "Avis ajouté avec succès !"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout de l'avis."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Données manquantes pour ajouter l'avis."]);
            }
        }
    }
    
    public function remove_avis(): void {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id_critique = $_POST['id_critique'] ?? null;

            $success = $this->model_bd->deleteCritique($id_critique);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Avis supprimé avec succès !"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erreur lors de la suppression de l'avis."]);
            }
        }
    }
}

