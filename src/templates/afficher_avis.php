<html>
<head>
    <title>Les avis</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/style.css">
</head>
</html>

<?php
session_start();

//!! Ajouter le code pour récupérer les avis de la base de données

$avis = $_SESSION['avis'] ?? [];
$index = 0;

foreach ($avis as $a) {
    echo "<div>";
    echo "<strong>" ."Nom utilisateur" . "</strong> - " . date("d/m/Y", strtotime($a['date_publication'])) . "<br>";
    echo "Réception : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_reception']) . "<br>";
    echo "Plats : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_plats']) . "<br>";
    echo "Service : " . str_repeat("<img src='../static/img/star.svg' alt='star' style='width:20px;height:20px;'>", $a['note_service']) . "<br>";
    echo "<p>" . htmlspecialchars($a['commentaire']) . "</p>";
    echo "<button type='submit' class='supprimerAvis button-red' data-index='$index'>Supprimer</button>";
    $index++;

    echo "<hr></div>";

}
?>
