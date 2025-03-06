<?php

namespace classes;

use \Exception;

class Provider{
    public static function getRestaurants($fichier)
    {
        $source = __DIR__ . '/../data/'. $fichier .".json";
        $content = file_get_contents($source);
        $restaurants = json_decode($content, true);

        if (empty($restaurants)) {
            throw new Exception('Pas de données :(', 1);
        }

        return $restaurants;
    }
}