<?php
$isLoggedIn = isset($_SESSION['user_id']);
$name = $_SESSION['user_name'] ?? '';
?>
<head>
    <link rel="stylesheet" href="/static/css/header.css">
    <script defer src="/static/js/header.js"></script>
</head>
<header>
    <?php if ($isLoggedIn): ?>
        <div class="logo">
            <button onclick="location.href='/index.php?action=home'">
                <img src="../static/img/logo.svg" alt="IUTables'O">
            </button>
        
        </div>
    <?php else: ?>
        <div class="logo">
            <button>
                <img src="../static/img/logo.svg" alt="IUTables'O">
            </button>
        </div>
    <?php endif; ?>
    
    <nav class="navbar">
        <?php if ($isLoggedIn): ?>
            <a href="?action=les-favoris" class="link"><img style="width:16px; height:16px;" src="../static/img/coeur.svg"> Mes restos préférés</a>
            <a href="?action=mes_reviews" class="link">Mes reviews</a>
            <p> <?= $name ?> </p>
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
