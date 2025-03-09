<?php
$isLoggedIn = isset($_SESSION['user_id']);
$name = $_SESSION['user_name'] ?? '';
$nom_role = $_SESSION['user_role'] ?? '';
?>
<head>
    <link rel="stylesheet" href="/static/css/header.css">
    <script defer src="/static/js/header.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<header>
    <div class="logo-container">
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
        <h1>IUTables’O</h1>
    </div>
    <nav class="navbar">
        <?php if ($isLoggedIn): ?>
            <?php if ($nom_role === 'utilisateur'): ?>
                <a href="?action=les-favoris" class="link"><img style="width:16px; height:16px;" src="../static/img/coeur.svg"> Mes restos préférés</a>
                <a href="?action=mes_reviews" class="link">Mes reviews</a>
            <?php elseif ($nom_role === 'admin'): ?>
                <a href="?action=admin-tableau-bord" class="link">Tableau de bord</a>
                <a href="?action=gerer-utilisateurs" class="link">Gérer les utilisateurs</a>
            <?php endif; ?>

            <p><?= htmlspecialchars($name) ?></p>
            <div class="profile-menu">
                <img src="../static/img/user.svg" alt="Profil" class="profile-icon" id="profileIcon">
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="/index.php?action=profil">Profil</a>
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
