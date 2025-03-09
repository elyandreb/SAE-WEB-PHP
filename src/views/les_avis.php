<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Les avis</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/avis.css">
</head>
<body>

    <h1  class="avistitle">
        <?php
        if (isset($_GET['nomRes'])) {
            echo "Restaurant : " . $_GET['nomRes'];
        }
        ?>
    </h1>

    <?php
  
    require_once __DIR__ . '/../classes/autoloader/autoload.php';
    use classes\controller\ControllerAvis;
    use classes\controller\ControllerRestaurant;
    use classes\controller\ControllerCuisine;

    $controller_avis = new ControllerAvis();
    $controller_restaurant = new ControllerRestaurant();
   
    $id_current_user = $_SESSION['user_id'] ?? null;
    $nom_role = $_SESSION['user_role'] ?? null;
   
    
    if (isset($_GET['id_res'])) {
        $id_res = $_GET['id_res'];
    }
    else {
        $id_res = null;
    }

    $perso = false;
    if (isset($id_res)) {
        $avis = $controller_avis->getCritiquesByRestaurant($id_res);
        $restaurant = $controller_restaurant->getRestaurantById($id_res);
    }

    else {
        $avis = $_SESSION['avis_persos'] ? $_SESSION['avis_persos'] : [];
        $perso = true;
    }



        if (isset($restaurant)) {
            echo "<div id=info>";
            echo "<h2>Informations</h2>";
            $controller_cuisine = new ControllerCuisine();
            $cuisines = $controller_cuisine->getCuisinesByRestaurant($id_res);
            echo "<p>Type d'établissement : " . $restaurant['type_res'] . "</p>";
            if (!empty($cuisines)) {
                echo "<p>Types de cuisines : ";
                $cuisine_names = array_map(function($cuisine) {
                    return $cuisine['nom_type'];
                }, $cuisines);
                echo implode(', ', $cuisine_names);
                echo "</p>";
            }
            else {
                echo "<p>Types de cuisines : Inconnu</p>";
            }
            echo "<p>Département : " . $restaurant['departement'] . "</p>";
            echo "<p>Commune : " . $restaurant['commune'] . "</p>";
            if (!empty($restaurant['telephone'])) {
                echo "<p>Téléphone : " . $restaurant['telephone'] . "</p>";
            } else {
                echo "<p>Téléphone : Inconnu</p>";
            }
            if (!empty($restaurant['lien_site'])) {
                echo "<p>Site web : <a href='" . htmlspecialchars($restaurant['lien_site']) . "'>" . htmlspecialchars($restaurant['lien_site']) . "</a></p>";
            } else {
                echo "<p>Site web : Inconnu</p>";
            }
            if (!empty($restaurant['horaires_ouvert'])) {
                $horaires = explode(';', $restaurant['horaires_ouvert']);
            } else {
                $horaires = [];
            }
            
            if (!empty($horaires)) {
                echo "<p>Horaires :</p><ul>";
                $jours = [
                    'Mo' => 'Lundi',
                    'Tu' => 'Mardi',
                    'We' => 'Mercredi',
                    'Th' => 'Jeudi',
                    'Fr' => 'Vendredi',
                    'Sa' => 'Samedi',
                    'Su' => 'Dimanche'
                ];
                foreach ($horaires as $horaire) {
                    $horaire = trim($horaire);
                    foreach ($jours as $en => $fr) {
                        $horaire = str_replace($en, $fr, $horaire);
                    }
                    echo "<li>" . $horaire . "</li>";
                }
            } else {
                echo "<p>Horaires : Inconnu</p>";
            }
            echo "</ul>";
            $coordinates = $restaurant['coordonnees'];
            echo "<h3> Emplacement : </h3>";
            echo "<div id='map' style='height: 400px; width: 100%;'></div>";
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var map = L.map('map').setView([$coordinates], 18);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    L.marker([$coordinates]).addTo(map);
                });
            </script>";
        }
        ?>
    </div>

    <?php
    $nom_resto = isset($_GET['nomRes']) ? $_GET['nomRes'] : 'Inconnu';
    
    if (empty($avis) && isset($_GET["nomRes"])){
        if (isset($_SESSION['user_id'])){
        echo "<div id='section_avis'>";
        echo '<div class ="ajout_avis_rien">';
        echo "<h1  class='avistitle'>Aucun avis pour le moment</h1>";
        echo "<div >";  
        echo '<a class="btn_ajouter_avis" href="index.php?action=add_avis&id_res=' . urlencode($restaurant['id_res']) . '&nomRes=' . urlencode($restaurant['nom_res']) . '">Ajouter un avis</a>';
        echo "</div>";
        echo "</div>";
        echo "</div>";
        }
    }
    
    else {
        echo "<div id='section_avis'>";
        if ($perso) {
            echo "<h1  class='avistitle'>Mes avis</h1>";
            if (empty($avis)) {
                echo "<h2 class='emptyavis'>Aucun avis pour le moment.</h2>";
            }
        }
        else{
            echo '<div class ="ajout_avis_rien">';
            echo "<h1  class='avistitle'>Les avis du restaurant $nom_resto</h1>";
            echo "<div >";
            echo '<a class="btn_ajouter_avis" href="index.php?action=add_avis&id_res=' . urlencode($restaurant['id_res']) . '&nomRes=' . urlencode($restaurant['nom_res']) . '">Ajouter un avis</a>';
            echo "</div>";
            echo "</div>";
        }
        
        foreach ($avis as $a) {
            $id_user_commu = $a['id_u'];
            $userName = $controller_avis->getNameUser($id_user_commu);
          
            echo "<div id='avis'>";
            if (!$perso) {
                echo "<strong>" . htmlspecialchars($userName) . "</strong> - " . date("d/m/Y - H:i", strtotime($a['date_creation'])) . "<br>";
            }
            else {
                echo "<strong>" . htmlspecialchars($userName) . "</strong> - ". $a['nom_res']. " - " . date("d/m/Y - H:i", strtotime($a['date_creation'])) . "<br>";
            }            
            echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_r']) . "<br>";
            echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_p']) . "<br>";
            echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_s']) . "<br>";
            echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";

            
                if (($id_user_commu ===  $id_current_user) || $nom_role ===  'admin') {
                   echo '<div id="btns">';
                   echo "<button class='btn_suppr' onclick=\"location.href='/index.php?action=remove_avis&id_c={$a['id_c']}&id_res={$a['id_res']}'\">Supprimer mon avis</button>";
                   echo "<button class='btn_suppr' onclick=\"location.href='/index.php?action=modify_avis&id_c={$a['id_c']}&id_res={$a['id_res']}&nomRes=" . urlencode($restaurant['nom_res'] ?? $a['nom_res']) . "'\">Modifier mon avis</button>";
                   echo "</div>";
                }
            

            echo "<hr></div>";
        }
        echo "</div>";
                
    }
    ?>


    

    

</body>
</html>
