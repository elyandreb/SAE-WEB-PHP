<?php

namespace classes\controller;

use classes\model\RestaurantModel;

class Controller{
    private  RestaurantModel $restaurantModel;
    
    public function __construct()
    {
        $this->restaurantModel = new RestaurantModel();
    }
    public function getRestaurants(){
        return $this->restaurantModel->getRestaurants();
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