<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - IUTables'O</title>
    <link rel="stylesheet" href="/static/css/inscription.css">
</head>
<body>
    <div class="register-container">
        <div class="left-side">
            <h1>IUTables’O</h1>
            <div class="image-container"></div>
        </div>
        <div class="right-side">
            <h2>Inscription</h2>

            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form action="/classes/register.php" method="POST">
                <label for="nom">Nom</label>
                <input type="text" name="nom" required>

                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" required>

                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="mdp">Mot de passe</label>
                <input type="password" name="mdp" required>

                <label for="mdp_confirm">Confirmer le mot de passe</label>
                <input type="password" name="mdp_confirm" required>

                <button type="submit">S'inscrire</button>
            </form>

            <p>Déjà un compte ? <a href="/templates/login_form.php">Se connecter</a></p>
        </div>
    </div>
</body>
</html>
