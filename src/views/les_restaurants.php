
<!DOCTYPE html>
<html>
<head>
    <title>Les restaurants</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/restaurant.css">
</head>
<body>

    <?php
        session_start();
        $restaurants = $_SESSION['restaurants'];
        $index = 0;
        
        echo '<div class="restaurants">';
        foreach ($restaurants as $restaurant) {
        
            // Utiliser osm_id comme identifiant unique
            $idRestaurant = $restaurant['id_res'];
            $isFavorite = isset($_SESSION['favoris']) && in_array($idRestaurant, $_SESSION['favoris']);
            $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';

            echo '<div class="restaurant" data-id="' . $idRestaurant . '">';
            echo '<span>' . (isset($restaurant['nom_res']) ? $restaurant['nom_res'] : 'Nom inconnu') . '</span>';
            echo '<p> '.(isset($restaurant['horaires_ouvert']) ? $restaurant['horaires_ouvert'] : 'Horaires inconnus').'</p>';
            echo '<button onclick="toggleFavoris(event, this, \'' . $idRestaurant . '\')">';
            echo '<img src="' . $heartIcon . '" alt="Favori">';
            echo '</button>';


            echo '<button class="btn" onclick="location.href=\'index.php?action=add_avis&id_res=' . urlencode($idRestaurant) .'&nomRes=' . urlencode($restaurant['nom_res']) . '\'">Ajouter un avis</button>';
            echo '<button class="btn" onclick="location.href=\'/index.php?action=les_avis&id_res=' . urlencode($idRestaurant) . '\'">Les avis</button>';
        echo '</div>';
        
    }


    