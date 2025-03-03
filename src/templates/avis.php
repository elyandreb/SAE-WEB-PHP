<!doctype html>
<html>
<head>
    <title>Restaurant</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <script src="../static/js/script.js" defer></script>
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
    <form id="avisForm">
        <label>Nom :</label>
        <input type="text" id="nom" required><br><br>

        <label>Réception :</label>
        <input type="number" id="note_reception" min="1" max="5" required><br>

        <label>Plats :</label>
        <input type="number" id="note_plats" min="1" max="5" required><br>

        <label>Service :</label>
        <input type="number" id="note_service" min="1" max="5" required><br>

        <label>Commentaire :</label><br>
        <textarea id="commentaire" required></textarea><br>

        <button type="submit">Envoyer</button>
    </form>

    <h2>Avis des clients :</h2>
    <div id="listeAvis"></div>
</body>

   
</div>