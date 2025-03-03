<?php

namespace classes;
class Controller{
    private $restaurants;

    public function __construct($restaurants)
    {
        $this->restaurants = $restaurants;
    }

    public function showRestaurants(): void {
        {
            $cpt = 0;
            foreach ($this->restaurants as $restaurant) {
                if ($cpt >= 5) {
                    break;
                }
                echo $restaurant['region'] . '<br>';
                $cpt++;
            }
        }
    }

    public function addAvisToResto($id): void{
        {
            foreach ($this->restaurants as $restaurant) {
                try {
                    if ($restaurant['id'] == $id) {
                        return $restaurant;
                    }
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                
            }
        }
    }


}