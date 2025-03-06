<?php

namespace classes\controller;

use classes\model\Model_bd;

class Controller{
    private  Model_bd $model_bd;
    
    public function __construct($model_bd)
    {
        $this->model_bd = $model_bd;
    }
    public function getRestaurants(){
        return $this->model_bd->getRestaurants();
    }

    public function toggleFavorite(string $idRestaurant): void {
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
        if (in_array($idRestaurant, $_SESSION['favoris'])) {
            // Supprimer du favori
            $_SESSION['favoris'] = array_filter($_SESSION['favoris'], function($id) use ($idRestaurant) {
                return $id !== $idRestaurant;
            });
            echo json_encode(['status' => 'success', 'favoris' => false]);
        } else {
            // Ajouter aux favoris
            $_SESSION['favoris'][] = $idRestaurant;
            echo json_encode(['status' => 'success', 'favoris' => true]);
        }
    }
    
}