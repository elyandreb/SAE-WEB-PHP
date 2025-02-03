<?php
$host = 'db.mexkcuymbqnzbkixjugo.supabase.co'; // Hôte Supabase
$db = 'postgres'; // Nom de la base de données
$user = 'postgres'; // Utilisateur
$pass = 'LtdLLEeV,lg'; // Mot de passe
$port = '5432'; // Port

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connexion réussie!";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
