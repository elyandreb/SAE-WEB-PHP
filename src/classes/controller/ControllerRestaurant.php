<?php

namespace classes\controller;

use classes\model\RestaurantModel;

class ControllerRestaurant {
    private  RestaurantModel $restaurantModel;
    
    public function __construct()
    {
        $this->restaurantModel = new RestaurantModel();
    }
    public function getRestaurants(){
        return $this->restaurantModel->getRestaurants();
    }

    public function getRestaurantById($id_res){
        return $this->restaurantModel->getRestaurantById($id_res);
    }

    public function getRestaurantsTriee(){
        return $this->restaurantModel->getRestaurantsTriee();
    }
}