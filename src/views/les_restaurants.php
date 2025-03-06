
<!DOCTYPE html>
<html>
<head>
    <title>Les restaurants</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/restaurant.css">
    <script src="../static/js/favoris.js" defer></script>
</head>
<body>

   
    <?php
    
    
    // Récupérer les restaurants et les favoris
    $restaurants = $_SESSION['restaurants'] ?? [];
    $favoris = $_SESSION['favoris'] ?? [];
    
    echo '<div class="restaurants">';
    foreach ($restaurants as $restaurant) {
        $idRestaurant = $restaurant['id_res'];
        // On vérifie si l'ID du restaurant est présent dans le tableau des favoris
        $isFavorite = in_array($idRestaurant, array_column($favoris, 'id_res')); 
        $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';
    
        echo '<div class="restaurant" data-id="' . $idRestaurant . '">';
        echo '<span>' . (isset($restaurant['nom_res']) ? $restaurant['nom_res'] : 'Nom inconnu') . '</span>';
        echo '<p>' . (isset($restaurant['horaires_ouvert']) ? $restaurant['horaires_ouvert'] : 'Horaires inconnus') . '</p>';
        echo '<button onclick="toggleFavoris(event, this, \'' . $idRestaurant . '\')">';
        echo '<img class="coeur" src="' . $heartIcon . '" alt="Favori">';
        echo '</button>';
    
        echo '<button class="btn" onclick="location.href=\'index.php?action=add_avis&id_res=' . urlencode($idRestaurant) . '&nomRes=' . urlencode($restaurant['nom_res']) . '\'">Ajouter un avis</button>';
        echo '<button class="btn" onclick="location.href=\'index.php?action=les_avis&id_res=' . urlencode($idRestaurant) . '&nomRes=' . urlencode($restaurant['nom_res']) . '\'">Les avis</button>';
        echo '</div>';
    }
    echo '</div>';
    ?>
    
        
    

</body>
</html>


    