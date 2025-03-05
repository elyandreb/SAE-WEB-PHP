<?php include 'header.php'; ?>
<!doctype html>
<html>
<head>
    <title>IU </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/avis.css">
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

    <form action="/index.php?action=add_avis" method="POST" id="avisForm" style="width: 50%; margin: auto;">

        <input type="hidden" id="siret" value="123456789">

        <input type="hidden" id="id_u" value="1">

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
    
</body>

   
</div>
