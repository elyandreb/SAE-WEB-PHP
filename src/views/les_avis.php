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
    session_start();
    $avis = $_SESSION['avis'];
    $nom_resto = $_SESSION['nom_res'];
    $nom_user = $_SESSION['user_name'];
    if (empty($avis)){
        echo "<h1>Aucun avis pour ce restaurant</h1>";
    }
    
    else {
        echo "<h1>Les avis du restaurant $nom_resto</h1>";
        foreach ($avis as $a) {
            echo "<div id='avis'>";

            echo "<strong> $nom_user #" . htmlspecialchars($a['id_u']) . "</strong> - " . date("d/m/Y", strtotime($a['date_creation'])) . "<br>";

            echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_r']) . "<br>";
            echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_p']) . "<br>";
            echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_s']) . "<br>";
        
            echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";

            echo "<button class='btn' onclick=\"location.href='/index.php?action=remove_avis&id_c={$a['id_c']}&id_res={$a['id_res']}'\">Supprimer</button>";

            echo "<hr></div>";
        }
    }
    ?>


    

    

</body>
</html>
