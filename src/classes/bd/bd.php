<?php

namespace BD;

use \PDO;

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

            $stmt = $this->pdo->query("SELECT COUNT(*) FROM RESTAURANT");
            $count = $stmt->fetchColumn();

            if ($count == 0) {
        
                $data = json_decode(file_get_contents('../data/restaurants_orleans.json'), true);

                foreach ($data as $item) {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO RESTAURANT (
                            siret, nom_res, commune, departement, region, coordonnees, lien_site, num_tel, horaires_ouvert
                        ) VALUES (:siret, :nom_res, :commune, :departement, :region, :coordonnees, :lien_site, :num_tel, :horaires_ouvert)
                        ON CONFLICT (siret) DO NOTHING
                    ");
                    $stmt->bindParam(':siret', $item['siret']);
                    $stmt->bindParam(':nom_res', $item['name']);
                    $stmt->bindParam(':commune', $item['com_nom']);
                    $stmt->bindParam(':departement', $item['departement']);
                    $stmt->bindParam(':region', $item['region']);
                    $stmt->bindParam(':coordonnees', "{$item['coordinates'][0]},{$item['coordinates'][1]}");
                    $stmt->bindParam(':lien_site', $item['website']);
                    $stmt->bindParam(':num_tel', $item['phone']);
                    $stmt->bindParam(':horaires_ouvert', $item['opening_hours']);
                    $stmt->execute();

                    if (!empty($item['cuisine'])) {
                        foreach ($item['cuisine'] as $cuisine) {
                            $stmt = $this->pdo->prepare("
                                INSERT INTO TYPE_CUISINE (nom_type) VALUES (:cuisine)
                                ON CONFLICT (nom_type) DO NOTHING
                            ");
                            $stmt->bindParam(':cuisine', $cuisine);
                            $stmt->execute();

                            $stmt = $this->pdo->prepare("
                            INSERT INTO ETRE (id_res, id_type)
                            VALUES ((SELECT id_res FROM RESTAURANT WHERE siret = :siret), (SELECT id_type FROM TYPE_CUISINE WHERE nom_type = :cuisine))
                            ON CONFLICT DO NOTHING
                            ");
                            $stmt->bindParam(':siret', $item['siret']);
                            $stmt->bindParam(':cuisine', $cuisine);
                            $stmt->execute();
                        }
                    }
                }

                $this->pdo->commit();
            } 
        }
            catch (PDOException $e) {
                $this->pdo->rollback();
                echo "Erreur : " . $e->getMessage();
            }     
        }
        

    public function getBD():PDO {
        return $this->pdo;
    }
}

?>
