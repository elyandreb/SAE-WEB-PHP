<?php 

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use classes\Provider;


$provider = new Provider();
$restaurants = $provider->getRestaurants('restaurants_orleans');
$cpt = 0;
foreach ($restaurants as $restaurant) {
    if ($cpt >= 3) {
        break;
    }
    echo $restaurant['region'] . '<br>';
    $cpt++;
}

?>