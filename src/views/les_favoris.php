<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les favoris</title>
    <link rel="stylesheet" href="../static/css/favoris.css">
    <link rel="stylesheet" href="../static/css/restaurant.css">
    
    <script src="../static/js/favoris.js" defer></script>
</head>
<body>
    <h1 class="favtitle">Mes favoris</h1>

    <?php
    use classes\controller\ControllerCuisine;
    use classes\controller\ControllerAvis;

    $controller_cuisine = new ControllerCuisine();
    $controller_avis = new ControllerAvis();
  
    $favoris = isset($_SESSION['favoris']) && is_array($_SESSION['favoris']) ? $_SESSION['favoris'] : [];
    if (empty($favoris)) {
        echo "<h2>Aucun favoris pour le moment.</h2>";
    } else { ?>

    <div class="restaurants">
        
        <?php foreach ($favoris as $restaurant) { ?>

            <a class="restaurant" href="index.php?action=les_avis&id_res=<?= urlencode($restaurant['id_res']) ?>&nomRes=<?= urlencode($restaurant['nom_res']) ?>">

                <div id='titre_resto'>
                    <h2><?= isset($restaurant['nom_res']) ? htmlspecialchars($restaurant['nom_res']) : 'Nom inconnu' ?></h2>
                    <button onclick="toggleFavoris(event, this, '<?= $restaurant['id_res'] ?>')" id="coeur">
                        <img class="coeur" src="<?= '../static/img/coeur.svg' ?>" alt="Favori">
                    </button>
                </div>

                <div id="info_resto">
                    <div style="display: flex; justify-content: space-between;">
                        <p><strong>Type d'établissement : </strong> <?php echo $restaurant['type_res'] ?></p>
                        <p style="text-align: center;"><?= $controller_avis->getMoyenneCritiquesByRestaurant($restaurant['id_res']) ?> /5 
                            <img src="../static/img/star.svg" alt="star" style="width:20px;height:20px;">
                        </p>
                    </div>
                    <p style="margin: 0"><strong>Ville :</strong> <?php echo $restaurant["commune"] ?> </p>
                    <p style="margin-top: 0"><strong>Département : </strong> <?php echo $restaurant["departement"] ?> </p>

                    <p><strong>Type de cuisine : </strong>
                        <?php
                        $type_cuisines = $controller_cuisine->getCuisinesByRestaurant($restaurant['id_res']);
                        if(!empty($type_cuisines)) {
                            $str_types_cuisine = [];
                            foreach ($type_cuisines as $value) {
                                $str_types_cuisine[] = htmlspecialchars($value['nom_type']);
                            }
                            echo implode(", ", $str_types_cuisine);
                        } else {
                            echo "Inconnu";
                        }
                        ?>
                     </p>
                </div>
            </a>
        <?php
        }
    }
    ?>
    </div>
</body>
</html>
