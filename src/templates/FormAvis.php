<!doctype html>
<html>
<head>
    <title>IU </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/style.css">
    <script src="../static/js/avis.js" defer></script>
</head>
<body>
<div class="flex">
    <div>
        <h1 class ="title">Votre avis compte pour nous ! Laissez un commentaire et partagez votre expérience. </h1>
    </div>
    <div >
        <h2 class ="subtitle"> Nom du restaurant </h2>
    </div>
    <body>
    <form id="avisForm" style="width: 50%; margin: auto;">
        <label>Nom :</label>
        <input type="text" id="nom" required style="width: 100%;"><br><br>

        <label>Réception :</label>
        <input type="number" id="note_reception" min="1" max="5" required style="width: 100%;"><br>

        <label>Plats :</label>
        <input type="number" id="note_plats" min="1" max="5" required style="width: 100%;"><br>

        <label>Service :</label>
        <input type="number" id="note_service" min="1" max="5" required style="width: 100%;"><br>

        <label>Commentaire :</label><br>
        <textarea id="commentaire" required style="width: 100%; height:150px; resize: none;"></textarea><br>

        <button type="submit" class="button-red" style="width: 100%; ">Envoyer</button>
    </form>

    <h2>Avis des clients :</h2>
    <div id="listeAvis"></div>

</body>

   
</div>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $note_reception = $_POST["note_reception"];
    $note_plats = $_POST["note_plats"];
    $note_service = $_POST["note_service"];
    $commentaire = $_POST["commentaire"];
    $date_publication = date("Y-m-d H:i:s");

    $_SESSION['avis'][] = [
        'nom' => $nom,
        'note_reception' => $note_reception,
        'note_plats' => $note_plats,
        'note_service' => $note_service,
        'commentaire' => $commentaire,
        'date_publication' => $date_publication
    ];

    //!! Ajouter le code pour ajouter l'avis dans la base de données
    echo "Avis ajouté avec succès !";
}
