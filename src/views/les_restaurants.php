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

        <?php if (!empty($_SESSION['bon_restos'])): ?>
            <a href="index.php?action=home&filter=bon_restos" class="filter-btn <?php echo isset($_GET['filter']) && $_GET['filter'] === 'bon_restos' ? 'active' : ''; ?>">Trier par note</a>
        <?php endif; ?>
    </div>



   
    <?php
    
    require_once __DIR__ . '/../classes/autoloader/autoload.php';
    use classes\controller\ControllerAvis;

    $controller_avis = new ControllerAvis();
   

    // Récupérer les restaurants et les favoris
    $restaurants = $_SESSION['restaurants'] ?? [];
    $favoris = $_SESSION['favoris'] ?? [];
    $userPreferences = $_SESSION['preferences'] ?? [];
    $bons_restaurants = $_SESSION['bons_restaurants'] ?? [];
    $filter_preferences = isset($_GET['filter']) && $_GET['filter'] === 'preferences';
    $filter_bon_restos = isset($_GET['filter']) && $_GET['filter'] === 'bon_restos';

    // Par défaut, on affiche tous les restaurants
    $restaurants_to_display = $_SESSION['restaurants'] ?? [];

    // Si l'utilisateur veut trier par note, on affiche les restaurants triés
    if ($filter_bon_restos) {
        $restaurants_to_display = $_SESSION['bon_restos'] ?? [];
    }

    // Si l'utilisateur veut filtrer par préférences, on applique le filtre
    if ($filter_preferences) {
        $userPreferences = $_SESSION['preferences'] ?? [];
        $restaurants_to_display = array_filter($_SESSION['restaurants'] ?? [], function ($restaurant) use ($userPreferences) {
            if (empty($restaurant['types']) || empty($userPreferences)) {
                return false;
            }
            foreach ($restaurant['types'] as $type) {
                if (in_array($type, $userPreferences)) {
                    return true;
                }
            }
            return false;
        });
    }

    // Nombre de restaurants par page
    $restaurantsPerPage = 24; // Ajuste selon ton design

    // Page actuelle
    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

    // Nombre total de restaurants
    $total_restaurants = count($restaurants_to_display);

    // Découper la liste pour afficher seulement les restaurants de la page actuelle
    $offset = ($current_page - 1) * $restaurantsPerPage;
    $restaurants_to_display = array_slice($restaurants_to_display, $offset, $restaurantsPerPage);

    // Calcul du nombre total de pages
    $total_pages = ceil($total_restaurants / $restaurantsPerPage);
    ?>

    <div class="restaurants">
        <?php if (!empty($restaurants_to_display)): ?>
            <?php foreach ($restaurants_to_display as $restaurant): ?>
                <?php
                $idRestaurant = $restaurant['id_res'];
                $isFavorite = in_array($idRestaurant, array_column($_SESSION['favoris'] ?? [], 'id_res'));
                $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';
                ?>
                <div class="restaurant" data-id="<?= $idRestaurant ?>">
                    <p><?= $controller_avis->getMoyenneCritiquesByRestaurant($idRestaurant) ?> /5 
                        <img src="../static/img/star.svg" alt="star" style="width:20px;height:20px;">
                    </p>
                    <span><?= isset($restaurant['nom_res']) ? htmlspecialchars($restaurant['nom_res']) : 'Nom inconnu' ?></span>
                    <p><?= isset($restaurant['horaires_ouvert']) ? htmlspecialchars($restaurant['horaires_ouvert']) : 'Horaires inconnus' ?></p>

                    <button onclick="toggleFavoris(event, this, '<?= $idRestaurant ?>')">
                        <img class="coeur" src="<?= $heartIcon ?>" alt="Favori">
                    </button>

                    <button class="btn" onclick="location.href='index.php?action=add_avis&id_res=<?= urlencode($idRestaurant) ?>&nomRes=<?= urlencode($restaurant['nom_res']) ?>'">Ajouter un avis</button>
                    <button class="btn" onclick="location.href='index.php?action=les_avis&id_res=<?= urlencode($idRestaurant) ?>&nomRes=<?= urlencode($restaurant['nom_res']) ?>'">Les avis</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">Aucun restaurant ne correspond à votre recherche.</p>
        <?php endif; ?>
    </div>

    <!-- Affichage de la pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="index.php?action=home&page=<?= $current_page - 1 ?>" class="page-btn">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?action=home&page=<?= $i ?>" class="page-btn <?= ($i === $current_page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="index.php?action=home&page=<?= $current_page + 1 ?>" class="page-btn">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>
</html>