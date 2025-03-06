<?php
namespace classes\model;

use \PDO;
use \PDOException;

class Model_bd {
    private $db;

    public function __construct($dbPath = '../restaurant.db') {
        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

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
                siret VARCHAR NOT NULL,
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

    public function addRestaurant($siret, $nom, $commune, $departement, $region, $coordonnees, $lien_site, $horaires, $telephone = null) {
        $query = "INSERT INTO RESTAURANT (siret, nom_res, commune, departement, region, coordonnees, lien_site, horaires_ouvert, telephone) 
                  VALUES (:siret, :nom, :commune, :departement, :region, :coordonnees, :lien_site, :horaires, :telephone)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_STR);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':commune', $commune, PDO::PARAM_STR);
            $stmt->bindParam(':departement', $departement, PDO::PARAM_STR);
            $stmt->bindParam(':region', $region, PDO::PARAM_STR);
            $stmt->bindParam(':coordonnees', $coordonnees, PDO::PARAM_STR);
            $stmt->bindParam(':lien_site', $lien_site, PDO::PARAM_STR);
            $stmt->bindParam(':horaires', $horaires, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getRestaurants() {
        $query = "SELECT * FROM RESTAURANT";
        
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getRestaurantById($id_res) {
        $query = "SELECT * FROM RESTAURANT WHERE id_res = :id_res";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getRestaurantBySiret($siret) {
        $query = "SELECT * FROM RESTAURANT WHERE siret = :siret";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCritiquesByUser($id_u) {
        $query = "SELECT CRITIQUE.*, RESTAURANT.nom_res 
                  FROM CRITIQUE 
                  JOIN RESTAURANT ON CRITIQUE.id_res = RESTAURANT.id_res 
                  WHERE CRITIQUE.id_u = :id_u 
                  ORDER BY CRITIQUE.date_creation DESC";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des critiques : " . $e->getMessage());
            return [];
        }
    }

    public function getCritiquesByUserResto($id_u, $id_res) {
        $query = "SELECT CRITIQUE.*, RESTAURANT.nom_res 
                  FROM CRITIQUE 
                  JOIN RESTAURANT ON CRITIQUE.id_res = RESTAURANT.id_res 
                  WHERE CRITIQUE.id_u = :id_u AND CRITIQUE.id_res = :id_res
                  ORDER BY CRITIQUE.date_creation DESC";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des critiques : " . $e->getMessage());
            return [];
        }
    }

    // Méthode pour obtenir ou créer un type de cuisine
    public function getOrCreateTypeCuisine($nom) {
        // Vérifier si ce type de cuisine existe déjà
        $query = "SELECT id_type FROM TYPE_CUISINE WHERE LOWER(nom_type) = LOWER(:nom)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Le type de cuisine existe déjà
                return $result['id_type'];
            } else {
                // Créer un nouveau type de cuisine
                $this->addTypeCuisine($nom);
                return $this->db->lastInsertId();
            }
        } catch (PDOException $e) {
            // En cas d'erreur, on crée quand même un nouveau type
            $this->addTypeCuisine($nom);
            return $this->db->lastInsertId();
        }
    }

    // Méthodes pour gérer les utilisateurs
    public function addUser($nom, $prenom, $email, $mdp, $role) {
        $query = "INSERT INTO UTILISATEUR (nom_u, prenom_u, email_u, mdp_u, le_role) 
                  VALUES (:nom, :prenom, :email, :mdp, :role)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes pour gérer les critiques
    public function addCritique($note_r, $commentaire, $id_res, $id_u, $note_p = null, $note_s = null) {
        $query = "INSERT INTO CRITIQUE (note_r, commentaire, id_res, id_u, note_p, note_s) 
                  VALUES (:note_r, :commentaire, :id_res, :id_u, :note_p, :note_s)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':note_r', $note_r, PDO::PARAM_INT);
            $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->bindParam(':note_p', $note_p, PDO::PARAM_INT);
            $stmt->bindParam(':note_s', $note_s, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes pour gérer les types de cuisine
    public function addTypeCuisine($nom) {
        $query = "INSERT INTO TYPE_CUISINE (nom_type) VALUES (:nom)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes pour gérer les associations
    public function addRestaurantTypeCuisine($id_res, $id_type) {
        $query = "INSERT INTO ETRE (id_res, id_type) VALUES (:id_res, :id_type)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addFavoris($id_res, $id_u) {
        $query = "INSERT INTO FAVORIS (id_res, id_u) VALUES (:id_res, :id_u)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function likeReview($id_c, $id_u) {
        $query = "INSERT INTO AIMER (id_c, id_u) VALUES (:id_c, :id_u)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes UPDATE
    public function updateRestaurant($id_res, $siret, $nom, $commune, $departement, $region, $coordonnees, $lien_site, $horaires, $telephone = null) {
        $query = "UPDATE RESTAURANT SET 
                siret = :siret,
                nom_res = :nom, 
                commune = :commune, 
                departement = :departement, 
                region = :region, 
                coordonnees = :coordonnees, 
                lien_site = :lien_site, 
                horaires_ouvert = :horaires, 
                telephone = :telephone 
                WHERE id_res = :id_res";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_STR);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':commune', $commune, PDO::PARAM_STR);
            $stmt->bindParam(':departement', $departement, PDO::PARAM_STR);
            $stmt->bindParam(':region', $region, PDO::PARAM_STR);
            $stmt->bindParam(':coordonnees', $coordonnees, PDO::PARAM_STR);
            $stmt->bindParam(':lien_site', $lien_site, PDO::PARAM_STR);
            $stmt->bindParam(':horaires', $horaires, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateRestaurantPartial($id_res, $fields = []) {
        if (empty($fields)) {
            return false;
        }
        
        $setClause = [];
        foreach ($fields as $field => $value) {
            $setClause[] = "$field = :$field";
        }
        
        $query = "UPDATE RESTAURANT SET " . implode(', ', $setClause) . " WHERE id_res = :id_res";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            
            foreach ($fields as $field => $value) {
                $stmt->bindParam(":$field", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateUser($id_u, $nom, $prenom, $email, $role) {
        $query = "UPDATE UTILISATEUR SET 
                nom_u = :nom, 
                prenom_u = :prenom, 
                email_u = :email, 
                le_role = :role 
                WHERE id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateUserPassword($id_u, $mdp) {
        $query = "UPDATE UTILISATEUR SET mdp_u = :mdp WHERE id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->bindParam(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateCritique($id_c, $note_r, $commentaire, $note_p = null, $note_s = null) {
        $query = "UPDATE CRITIQUE SET 
                note_r = :note_r, 
                commentaire = :commentaire, 
                note_p = :note_p, 
                note_s = :note_s 
                WHERE id_c = :id_c";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $stmt->bindParam(':note_r', $note_r, PDO::PARAM_INT);
            $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmt->bindParam(':note_p', $note_p, PDO::PARAM_INT);
            $stmt->bindParam(':note_s', $note_s, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateTypeCuisine($id_type, $nom) {
        $query = "UPDATE TYPE_CUISINE SET nom_type = :nom WHERE id_type = :id_type";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Méthodes DELETE
    public function deleteRestaurant($id_res) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des enregistrements associés dans d'autres tables
            $this->db->exec("DELETE FROM FAVORIS WHERE id_res = $id_res");
            $this->db->exec("DELETE FROM ETRE WHERE id_res = $id_res");
            
            // Récupération des ID des critiques liées au restaurant
            $stmt = $this->db->prepare("SELECT id_c FROM CRITIQUE WHERE id_res = :id_res");
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->execute();
            $critiques = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Suppression des likes associés aux critiques
            foreach ($critiques as $id_c) {
                $this->db->exec("DELETE FROM AIMER WHERE id_c = $id_c");
            }
            
            // Suppression des critiques
            $this->db->exec("DELETE FROM CRITIQUE WHERE id_res = $id_res");
            
            // Suppression du restaurant
            $stmt = $this->db->prepare("DELETE FROM RESTAURANT WHERE id_res = :id_res");
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteUser($id_u) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des enregistrements associés dans d'autres tables
            $this->db->exec("DELETE FROM FAVORIS WHERE id_u = $id_u");
            
            // Récupération des ID des critiques liées à l'utilisateur
            $stmt = $this->db->prepare("SELECT id_c FROM CRITIQUE WHERE id_u = :id_u");
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            $critiques = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Suppression des likes associés aux critiques
            foreach ($critiques as $id_c) {
                $this->db->exec("DELETE FROM AIMER WHERE id_c = $id_c");
            }
            
            // Suppression des critiques de l'utilisateur
            $this->db->exec("DELETE FROM CRITIQUE WHERE id_u = $id_u");
            
            // Suppression des likes faits par l'utilisateur
            $this->db->exec("DELETE FROM AIMER WHERE id_u = $id_u");
            
            // Suppression de l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM UTILISATEUR WHERE id_u = :id_u");
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteCritique($id_c) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des likes associés à cette critique
            $this->db->exec("DELETE FROM AIMER WHERE id_c = $id_c");
            
            // Suppression da critique
            $stmt = $this->db->prepare("DELETE FROM CRITIQUE WHERE id_c = :id_c");
            $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteTypeCuisine($id_type) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des associations avec les restaurants
            $this->db->exec("DELETE FROM ETRE WHERE id_type = $id_type");
            
            // Suppression de type de cuisine
            $stmt = $this->db->prepare("DELETE FROM TYPE_CUISINE WHERE id_type = :id_type");
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteFavori($id_res, $id_u) {
        $query = "DELETE FROM FAVORIS WHERE id_res = :id_res AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unlikeReview($id_c, $id_u) {
        $query = "DELETE FROM AIMER WHERE id_c = :id_c AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeRestaurantTypeCuisine($id_res, $id_type) {
        $query = "DELETE FROM ETRE WHERE id_res = :id_res AND id_type = :id_type";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function init_resto_json() {
        $data = json_decode(file_get_contents(__DIR__ . '/../../data/restaurants_orleans.json'), true);
        foreach ($data as $item) {
            $coords = isset($item['geo_point_2d']['lon'], $item['geo_point_2d']['lat']) 
                ? "{$item['geo_point_2d']['lon']},{$item['geo_point_2d']['lat']}" 
                : null;
            
            // Ajouter le restaurant
            $id_res = $this->addRestaurant(
                $item['siret'],
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
                        $id_type = $this->getOrCreateTypeCuisine($cuisine);
                        $this->addRestaurantTypeCuisine($id_res, $id_type);
                    }
                }
            }
        }
    }

    public function loginUser($email, $mdp) {
        $query = "SELECT * FROM UTILISATEUR WHERE email_u = :email";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($mdp, $user['mdp_u'])) {
                return $user; // Retourne les infos de l'utilisateur si l'authentification est correcte
            }
            
            return false; // Mauvais identifiants
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkEmailExists($email) {
        $query = "SELECT COUNT(*) FROM UTILISATEUR WHERE email_u = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function registerUser($nom, $prenom, $email, $mdp, $role) {
        $query = "INSERT INTO UTILISATEUR (nom_u, prenom_u, email_u, mdp_u, le_role) 
                  VALUES (:nom, :prenom, :email, :mdp, :role)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'inscription: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserIdByEmail($email) {
        $query = "SELECT id_u FROM UTILISATEUR WHERE email_u = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function getFavorisByUser($id_u) {
        $query = "SELECT r.* FROM RESTAURANT r
                  JOIN FAVORIS f ON r.id_res = f.id_res
                  WHERE f.id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function isFavori($id_res, $id_u) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE id_res = :id_res AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>