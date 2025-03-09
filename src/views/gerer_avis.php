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

    <?php
 

    $id_current_user = $_SESSION['user_id'];
    $nom_role = $_SESSION['user_role'] ?? null;

    // Récupérer tous les avis sans filtrer par restaurant
    $avis = $controller_avis->getAvis();  // Utilise une méthode qui récupère tous les avis

    if (empty($avis)) {
        echo "<div class='ajout_avis_rien'>";
        echo "<h1 class='avistitle'>Aucun avis pour le moment</h1>";
        echo "</div>";
    } else {
        echo "<div id='section_avis'>";
        echo "<h1 class='avistitle'>Tous les avis</h1>";

        foreach ($avis as $a) {
            $id_user_commu = $a['id_u'];
            $userName = $controller_avis->getNameUser($id_user_commu);

            echo "<div id='avis'>";
            echo "<strong>" . htmlspecialchars($userName) . "</strong> - " . date("d/m/Y - H:i", strtotime($a['date_creation'])) . "<br>";
            echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_r']) . "<br>";
            echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_p']) . "<br>";
            echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_s']) . "<br>";
            echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";

            echo '<div id="btns">';
            // Afficher les boutons pour supprimer ou modifier l'avis si l'utilisateur est l'auteur ou admin
            if (($id_user_commu === $id_current_user) || $nom_role === 'admin') {
                echo "<button class='btn_suppr' onclick=\"location.href='/index.php?action=remove_avis&id_c={$a['id_c']}&id_res={$a['id_res']}'\">Supprimer cet avis</button>";
                echo "<button class='btn_suppr' onclick=\"location.href='/index.php?action=modify_avis&id_c={$a['id_c']}'\">Modifier cet avis</button>";
            }
            echo "</div>";

            echo "<hr></div>";
        }
        echo "</div>";
    }
    ?>

</body>
</html>
