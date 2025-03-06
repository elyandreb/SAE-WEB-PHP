<?php
namespace classes\controller;

use classes\model\FavoriModel;
use \Exception;

class ControllerFavoris {
    private FavoriModel $favoriModel;

    public function __construct() {
        $this->favoriModel = new FavoriModel();
    }

    public function toggleFavorite(string $id_res, string $id_u): void {
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
    
        if (in_array($id_res, $_SESSION['favoris'])) {
            // Supprimer des favoris 
            $_SESSION['favoris'] = array_filter($_SESSION['favoris'], fn($id) => $id !== $id_res);
            $this->favoriModel->deleteFavori($id_res, $id_u);
            echo json_encode(['status' => 'success', 'favoris' => false]);
        } else {
            
            $_SESSION['favoris'][] = $id_res;
            $this->favoriModel->addFavori($id_res, $id_u);
            echo json_encode(['status' => 'success', 'favoris' => true]);
        }
    
        exit;
    }

    public function getFavorisByUser(string $id_u): array  {
        try {
    
            $favoris = $this->favoriModel->getFavorisByUser($id_u);
            
            if ($favoris === null || empty($favoris)) {
                return [];  
            }
    
            return $favoris;
        } catch (Exception $e) {
            // En cas d'exception,
            error_log("Erreur lors de la récupération des favoris: " . $e->getMessage());
            return [];
        }
    }
    
    
    
    
}
