<?php include 'header.php'; ?>
<!doctype html>
<html>
<head>
    <title>IU </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/avis.css">
    <link rel="stylesheet" href="../static/css/style.css">
    
</head>

<body>
<div class="flex">
    <div>
        <h1 class ="title">Votre avis compte pour nous ! Laissez un commentaire et partagez votre expérience. </h1>
    </div>
    <div >
        <h2 class ="subtitle"> Le restaurant <?= htmlspecialchars($_GET['nomRes'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
    </div>

    <form action="index.php?action=add_avis" method="POST" id="avisForm" style="width: 50%; margin: auto;"> 

        <input type="hidden" name="id_res" id="id_res" value="<?= htmlspecialchars($_GET['id_res'] ?? '') ?>">

        <input type="hidden" name="nomRes" id="nomRes" value="<?= htmlspecialchars($_GET['nomRes'] ?? '') ?>">

        <input type="hidden" name="id_u" id="id_u" value="1">

        <label>Réception :</label>
        <input type="number" id="note_reception" name= "note_reception" min="1" max="5" required style="width: 100%;"><br>

        <label>Plats :</label>
        <input type="number" id="note_plats"name= "note_plats" min="1" max="5" required style="width: 100%;"><br>

        <label>Service :</label>
        <input type="number" id="note_service" name= "note_service" min="1" max="5" required style="width: 100%;"><br>

        <label>Commentaire :</label><br>
        <textarea id="commentaire"  name= "commentaire" required style="width: 100%; height:150px; resize: none;"></textarea><br>

        <button type="submit" class="button-red" style="width: 100%; ">Envoyer</button>
    </form>
    
</body>

   
</div>
