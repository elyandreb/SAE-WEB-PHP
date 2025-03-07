<?php include 'header.php'; ?>
<!doctype html>
<html>
<head>
    <title>Profil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/profil.css">
</head>
<body>
    <div class="profil-container">
        <h2>Profil de <?= $name ?></h2>
        <div class="profil-info">
            <p>Prénom : <?= $prenom ?></p>
            <p>Nom : <?= $nom ?></p>
            <p>Email : <?= $email ?></p>
            <p>Date de naissance : <?= $date_naissance ?></p>
            <p>Adresse : <?= $adresse ?></p>
            <p>Ville : <?= $ville ?></p>
            <p>Code postal : <?= $code_postal ?></p>
        </div>
        <button class="btn" onclick="location.href='/index.php?action=preferences'">Modifier mes préférences</button>
    </div>