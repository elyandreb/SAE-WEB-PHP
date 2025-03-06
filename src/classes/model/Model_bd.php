<?php
namespace classes\model;

use \PDO;
use \PDOException;

class Model_bd {
    private static $instance = null;
    private $db;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     * @param string $dbPath Chemin vers la base de données
     */
    private function __construct($dbPath = '../restaurant.db') {
        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    /**
     * Méthode pour obtenir l'instance unique de Model_bd (pattern Singleton)
     * @param string $dbPath Chemin vers la base de données
     * @return Model_bd L'instance unique de Model_bd
     */
    public static function getInstance($dbPath = '../restaurant.db') {
        if (self::$instance === null) {
            self::$instance = new self($dbPath);
        }
        return self::$instance;
    }

    /**
     * Retourne la connexion PDO à la base de données
     * @return PDO Connexion à la base de données
     */
    public function getConnection() {
        return $this->db;
    }

    /**
     * Crée les tables nécessaires si elles n'existent pas
     */
    private function createTables() {
        // Création des tables
        $queries = [
            // Table TYPE_CUISINE
            "CREATE TABLE IF NOT EXISTS TYPE_CUISINE (
                id_type INTEGER PRIMARY KEY,
                nom_type VARCHAR NOT NULL
            )",

            // Table RESTAURANT
            "CREATE TABLE IF NOT EXISTS RESTAURANT (
                id_res INTEGER PRIMARY KEY AUTOINCREMENT,
                nom_res VARCHAR NOT NULL,
                commune VARCHAR NOT NULL,
                departement VARCHAR NOT NULL,
                region VARCHAR NOT NULL,
                coordonnees VARCHAR,
                lien_site VARCHAR,
                horaires_ouvert VARCHAR,
                telephone VARCHAR
            )",

            // Table UTILISATEUR
            "CREATE TABLE IF NOT EXISTS UTILISATEUR (
                id_u INTEGER PRIMARY KEY,
                nom_u VARCHAR NOT NULL,
                prenom_u VARCHAR NOT NULL,
                email_u VARCHAR NOT NULL UNIQUE,
                mdp_u VARCHAR NOT NULL,
                le_role VARCHAR NOT NULL
            )",

            // Table FAVORIS
            "CREATE TABLE IF NOT EXISTS FAVORIS (
                id_res INTEGER NOT NULL,
                id_u INTEGER NOT NULL,
                PRIMARY KEY (id_res, id_u),
                FOREIGN KEY (id_res) REFERENCES RESTAURANT(id_res),
                FOREIGN KEY (id_u) REFERENCES UTILISATEUR(id_u)
            )",

            // Table ETRE
            "CREATE TABLE IF NOT EXISTS ETRE (
                id_res INTEGER NOT NULL,
                id_type INTEGER NOT NULL,
                PRIMARY KEY (id_res, id_type),
                FOREIGN KEY (id_res) REFERENCES RESTAURANT(id_res),
                FOREIGN KEY (id_type) REFERENCES TYPE_CUISINE(id_type)
            )",

            // Table AIMER
            "CREATE TABLE IF NOT EXISTS AIMER (
                id_c INTEGER NOT NULL,
                id_u INTEGER NOT NULL,
                PRIMARY KEY (id_c, id_u),
                FOREIGN KEY (id_c) REFERENCES CRITIQUE(id_c),
                FOREIGN KEY (id_u) REFERENCES UTILISATEUR(id_u)
            )",

            // Table CRITIQUE
            "CREATE TABLE IF NOT EXISTS CRITIQUE (
                id_c INTEGER PRIMARY KEY,
                note_r INTEGER NOT NULL,
                commentaire VARCHAR,
                date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                id_res INTEGER NOT NULL,
                id_u INTEGER NOT NULL,
                note_p INTEGER,
                note_s INTEGER,
                FOREIGN KEY (id_res) REFERENCES RESTAURANT(id_res),
                FOREIGN KEY (id_u) REFERENCES UTILISATEUR(id_u)
            )",

            // Table UTILISATEUR_PREFERENCES
            "CREATE TABLE IF NOT EXISTS UTILISATEUR_PREFERENCES (
                id_u INTEGER NOT NULL,
                id_type INTEGER NOT NULL,
                PRIMARY KEY (id_u, id_type),
                FOREIGN KEY (id_u) REFERENCES UTILISATEUR(id_u),
                FOREIGN KEY (id_type) REFERENCES TYPE_CUISINE(id_type)
            )"
        ];

        try {
            $this->db->beginTransaction();
            
            foreach ($queries as $query) {
                $this->db->exec($query);
            }
            
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            die("Erreur lors de la création des tables: " . $e->getMessage());
        }
    }

    /**
     * Initialise la base de données avec les données du fichier JSON
     * Supprime d'abord les données existantes
     */
    public function init_resto_json() {
        try {
            $this->db->beginTransaction();
            
            // Suppression des données existantes
            $this->db->exec("DELETE FROM AIMER");
            $this->db->exec("DELETE FROM CRITIQUE");
            $this->db->exec("DELETE FROM FAVORIS");
            $this->db->exec("DELETE FROM ETRE");
            $this->db->exec("DELETE FROM RESTAURANT");
            $this->db->exec("DELETE FROM TYPE_CUISINE");
            
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression des données: " . $e->getMessage());
            throw new \Exception("Erreur lors de la suppression des données: " . $e->getMessage());
        }
        
        // Chargement du fichier JSON
        $data = json_decode(file_get_contents(__DIR__ . '/../../data/restaurants_orleans.json'), true);
        
        // Objet restaurant pour l'ajout
        $restaurantModel = new RestaurantModel();
        $typeCuisineModel = new TypeCuisineModel();
        
        foreach ($data as $item) {
            $coords = isset($item['geo_point_2d']['lon'], $item['geo_point_2d']['lat']) 
                ? "{$item['geo_point_2d']['lon']},{$item['geo_point_2d']['lat']}" 
                : null;
            
            // Ajouter le restaurant
            $id_res = $restaurantModel->addRestaurant(
                $item['name'],
                $item['com_nom'],
                $item['departement'],
                $item['region'],
                $coords,
                $item['website'] ?? null,
                $item['opening_hours'] ?? null,
                $item['phone'] ?? null
            );

            // Gérer les types de cuisine
            if (!empty($item['cuisine']) && $id_res) {
                $cuisines = is_array($item['cuisine']) ? $item['cuisine'] : [$item['cuisine']];
                foreach ($cuisines as $cuisine) {
                    if ($cuisine) {
                        $id_type = $typeCuisineModel->getOrCreateTypeCuisine($cuisine);
                        $typeCuisineModel->addRestaurantTypeCuisine($id_res, $id_type);
                    }
                }
            }
        }
    }
}
?>