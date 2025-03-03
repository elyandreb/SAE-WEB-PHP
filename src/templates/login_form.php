<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - IUTables'O</title>
    <link rel="stylesheet" href="../static/css/connexion.css">
</head>
<body>
    <div class="login-container">
        <div class="left-side">
            <h1>IUTables’O</h1>
            <div class="image-container"></div>
        </div>
        <div class="right-side">
            <h2>Connexion</h2>
            <form action="classes/login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="mdp">Mot de passe</label>
                <input type="password" name="mdp" required>

                <button type="submit">Se connecter</button>
            </form>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


