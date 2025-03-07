<!DOCTYPE html>
<html>
<head>
    <title>Les restaurants</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/restaurant.css">
    <link rel="stylesheet" href="../static/css/bouton_filtre.css">
    <script src="../static/js/favoris.js" defer></script>
</head>
<body>

    <!-- Ajout des boutons de filtrage -->
    <div class="filter-options">
        <a href="index.php?action=home" class="filter-btn <?php echo !isset($_GET['filter']) ? 'active' : ''; ?>">Tous les restaurants</a>
        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['preferences'])): ?>
            <a href="index.php?action=home&filter=preferences" class="filter-btn <?php echo isset($_GET['filter']) && $_GET['filter'] === 'preferences' ? 'active' : ''; ?>">Voir avec mes préférences uniquement</a>
        <?php endif; ?>
    </div>
   
    <?php
    // Récupérer les restaurants et les favoris
    $restaurants = $_SESSION['restaurants'] ?? [];
    $favoris = $_SESSION['favoris'] ?? [];
    $userPreferences = $_SESSION['preferences'] ?? [];
    
    // Appliquer le filtre si demandé
    $filtered = isset($_GET['filter']) && $_GET['filter'] === 'preferences';
    
    echo '<div class="restaurants">';
    foreach ($restaurants as $restaurant) {
        $idRestaurant = $restaurant['id_res'];
        // On vérifie si l'ID du restaurant est présent dans le tableau des favoris
        $isFavorite = in_array($idRestaurant, array_column($favoris, 'id_res')); 
        $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';
        
        // On vérifie si ce restaurant appartient à un type préféré par l'utilisateur
        $restaurantTypes = isset($restaurant['types']) ? $restaurant['types'] : [];
        $matchesPreference = false;
        
        if (!empty($restaurantTypes) && !empty($userPreferences)) {
            foreach ($restaurantTypes as $type) {
                if (in_array($type, $userPreferences)) {
                    $matchesPreference = true;
                    break;
                }
            }
        }
        
        // Sauter ce restaurant s'il ne correspond pas aux préférences et que le filtre est activé
        if ($filtered && !$matchesPreference) {
            continue;
        }
    
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