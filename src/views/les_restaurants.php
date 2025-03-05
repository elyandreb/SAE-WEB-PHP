
<!DOCTYPE html>
<html>
<head>
    <title>Les restaurants</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>

    <?php

    $restaurants = $_SESSION['restaurants'];
    $index = 0;
    

    echo '<div class="restaurants">';
    foreach ($restaurants as $restaurant) {
        // Utiliser osm_id comme identifiant unique
        $idRestaurant = $restaurant['siret'];
        $isFavorite = isset($_SESSION['favoris']) && in_array($idRestaurant, $_SESSION['favoris']);
        $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';

        echo '<div class="restaurant" data-id="' . $idRestaurant . '">';
        echo '<span>' . $restaurant['name'] . '</span>';
        echo '<p> '.$restaurant['opening_hours'].'</p>';
        echo '<button onclick="toggleFavoris(event, this, \'' . $idRestaurant . '\')">';
        echo '<img src="' . $heartIcon . '" alt="Favori">';
        echo '</button>';


        echo '<button class="btn" onclick="location.href=\'/index.php?action=add_avis\'">Détails</button>';
        echo '<button class="btn" onclick="location.href=\'/index.php?action=les_avis\'">Les avis</button>';
    echo '</div>';
    
}



    