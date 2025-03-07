<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="static/css/edit_profil.css">
</head>
<body>
    <div class="profile-container">
        <h2>Modifier mon Profil</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?= htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form action="index.php?action=editProfil" method="POST">
            <h3>Informations Personnelles</h3>

            <label for="nom">Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom_u']) ?>" required>

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom_u']) ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email_u']) ?>" required>

            <h3>Changer le Mot de Passe</h3>

            <label for="old_password">Ancien mot de passe</label>
            <input type="password" name="old_password">

            <label for="new_password">Nouveau mot de passe</label>
            <input type="password" name="new_password">

            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password">

            <button type="submit" class="btn">Enregistrer</button>
            <a href="index.php?action=profil" class="btn-cancel">Annuler</a>
        </form>
    </div>
</body>
</html>
