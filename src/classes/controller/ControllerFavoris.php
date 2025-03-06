<?php
namespace classes\controller;
use classes\model\Model_bd;

class ControllerFavoris {
    private Model_bd $model_bd;

    public function __construct(Model_bd $model_bd) {
        $this->model_bd = $model_bd;
    }

    public function toggleFavorite(string $id_res, string $id_u): void {
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
    
        if (in_array($id_res, $_SESSION['favoris'])) {
            // Supprimer des favoris 
            $_SESSION['favoris'] = array_filter($_SESSION['favoris'], fn($id) => $id !== $id_res);
            $this->model_bd->deleteFavoris($id_res, $id_u);
            echo json_encode(['status' => 'success', 'favoris' => false]);
        } else {
            
            $_SESSION['favoris'][] = $id_res;
            $this->model_bd->addFavoris($id_res, $id_u);
            echo json_encode(['status' => 'success', 'favoris' => true]);
        }
    
        exit;
    }

    public function getFavorisByUser(string $id_u): array {
        try {
    
            $favoris = $this->model_bd->getFavorisByUser($id_u);
            
            if ($favoris === null || empty($favoris)) {
                return [];  
            }
    
            return $favoris;
        } catch (Exception $e) {
            // En cas d'exception,
        }
    }
    
    
    
    
}
