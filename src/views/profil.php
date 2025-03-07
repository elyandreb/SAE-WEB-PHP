<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="static/css/profil.css">
</head>
<body>
    <div class="profile-container">
        <h2>Mon Profil</h2>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom_u']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom_u']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email_u']) ?></p>

        <h3>Mes préférences</h3>
        <ul>
            <?php foreach ($preferences as $pref): ?>
                <li><?= htmlspecialchars($pref['nom_type']) ?></li>
            <?php endforeach; ?>
        </ul>

        <div class="profile-actions">
            <a href="index.php?action=editProfil" class="btn">Modifier le profil</a>
            <a href="index.php?action=preferences" class="btn">Modifier préférences</a>
        </div>
    </div>
</body>
</html>
