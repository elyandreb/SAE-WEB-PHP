<?php
session_start();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les favoris</title>
    <link rel="stylesheet" href="../static/css/favoris.css">
</head>
<body>
    <h1>Les favoris</h1>

    <?php
  
    $favoris = isset($_SESSION['favoris']) && is_array($_SESSION['favoris']) ? $_SESSION['favoris'] : [];

    if (empty($favoris)) {
        echo "<h2>Aucun favoris pour le moment.</h2>";
    } else {
        // Afficher les restaurants favoris
        echo '<div class="favoris">';

        foreach ($favoris as $restaurant) {
            echo '<div class="restaurant">';

            echo '<p>ID Restaurant: ' . htmlspecialchars($restaurant['id_res']) . '</p>'; // Affiche l'ID si nécessaire
            echo '</div>';
        }

        echo '</div>';
    }
    ?>
</body>
</html>
