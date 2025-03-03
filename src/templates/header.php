<?php
$isLoggedIn = isset($_SESSION['user_id']);
?>
<head>
    <link rel="stylesheet" href="../static/css/header.css">
</head>
<header>
    <div class="logo">
        <img src="../static/img/logo.svg" alt="IUTables'O">
    </div>
    
    <nav>
        <?php if ($isLoggedIn): ?>
            <a href="restos_preferes.php">Mes restos préférés</a>
            <a href="mes_reviews.php">Mes reviews</a>
            <a href="profil.php" class="profile-icon">
                <img src="../static/img/user.svg" alt="Profil">
            </a>
        <?php else: ?>
            <a href="register.php" class="btn">S'inscrire</a>
            <a href="login_form.php" class="btn">Se connecter</a>
        <?php endif; ?>
    </nav>
</header>

