<?php

namespace classes;
class Controller{
    private $restaurants;

    public function __construct($restaurants)
    {
        $this->restaurants = $restaurants;
    }

public function showRestaurants(): void
    {
        $cpt = 0;
        foreach ($this->restaurants as $restaurant) {
            if ($cpt >= 3) {
                break;
            }
            echo $restaurant['region'] . '<br>';
            $cpt++;
        }
    }
}