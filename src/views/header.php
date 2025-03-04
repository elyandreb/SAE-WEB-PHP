<?php
$isLoggedIn = isset($_SESSION['user_id']);
?>
<head>
    <link rel="stylesheet" href="/static/css/header.css">
    <script defer src="/static/js/header.js"></script>
</head>
<header>
    <div class="logo">
        <img src="../static/img/logo.svg" alt="IUTables'O">
    </div>
    
    <nav>
        <?php if ($isLoggedIn): ?>
            <a href="restos_preferes.php" class="link">Mes restos préférés</a>
            <a href="mes_reviews.php" class="link">Mes reviews</a>

            <div class="profile-menu">
                <img src="../static/img/user.svg" alt="Profil" class="profile-icon" id="profileIcon">
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="profil.php">Profil</a>
                    <form action="/index.php?action=logout" method="POST">
                        <button class="logout" type="submit">Se déconnecter</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <a href="/views/register_form.php" class="btn">S'inscrire</a>
            <a href="/views/login_form.php" class="btn">Se connecter</a>
        <?php endif; ?>
    </nav>
</header>
