<!doctype html>
<html>
<head>
    <title>IU</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/restaurant.css">
    <script src="../static/js/favoris.js" defer></script>
</head>
<body>


<?php
session_start();

//!! Ajouter le code pour récupérer les avis de la base de données

$restaurants = $_SESSION['restaurants'] ?? [];
$index = 0;

foreach ($restaurants as $resto) {
  
    $index++;

}
?>


