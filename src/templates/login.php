<?php
// templates/login.php 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Quiz</title>
    <link rel="stylesheet" href="templates/styles/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form method="POST" action="index.php?action=login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Commencer">
        </form>
    </div>
</body>
</html>