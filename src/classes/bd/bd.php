<?php

class model_bd {
    private $pdo;

    public function __construct() {
        $host = 'db.mexkcuymbqnzbkixjugo.supabase.co'; // Hôte Supabase
        $db = 'postgres'; // Nom de la base de données
        $user = 'postgres'; // Utilisateur
        $pass = 'LtdLLEeV,lg'; // Mot de passe
        $port = '5432'; // Port
        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$db";
            $this->pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    public function getBD():PDO {
        return $this->pdo;
    }
}

?>
