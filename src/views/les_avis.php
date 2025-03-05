
<!DOCTYPE html>
<html>
<head>
    <title>Les avis</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/style.css">
    <script src="../static/js/avis.js" defer></script>
</head>
<body>
    <?php
    session_start();
    $avis = $_SESSION['avis'];

    $index = 0;
    foreach ($avis as $a) {
        echo "<div>";
        
        echo "<strong>Utilisateur #" . htmlspecialchars($a['id_u']) . "</strong> - " . date("d/m/Y", strtotime($a['date_creation'])) . "<br>";
        
        echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_r']) . "<br>";
        echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_p']) . "<br>";
        echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_s']) . "<br>";
    
        echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";

        echo '<button class="btn" onclick="location.href=\'/index.php?action=remove_avis\'">Supprimer</button>';
        
        $index++;
        echo "<hr></div>";
    }
    ?>


    

    

</body>
</html>
