<?php
namespace classes\controller;

use classes\model\TypeCuisineModel;

class ControllerCuisine {
    private TypeCuisineModel $typeCuisineModel;

    public function __construct() {
        $this->typeCuisineModel = new TypeCuisineModel();
    }

    public function getCuisinesByRestaurant($id_res): array {
        return $this->typeCuisineModel->getTypesCuisineByRestaurant($id_res);
    }
}