<?php
class model_bd {
    private $db;

    public function __construct($dbPath = 'restaurant.db') {
        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
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

            // Table RESTAURANT avec le champ téléphone ajouté
            "CREATE TABLE IF NOT EXISTS RESTAURANT (
                siret INTEGER PRIMARY KEY,
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
                siret INTEGER NOT NULL,
                id_u INTEGER NOT NULL,
                PRIMARY KEY (siret, id_u),
                FOREIGN KEY (siret) REFERENCES RESTAURANT(siret),
                FOREIGN KEY (id_u) REFERENCES UTILISATEUR(id_u)
            )",

            // Table ETRE
            "CREATE TABLE IF NOT EXISTS ETRE (
                siret INTEGER NOT NULL,
                id_type INTEGER NOT NULL,
                PRIMARY KEY (siret, id_type),
                FOREIGN KEY (siret) REFERENCES RESTAURANT(siret),
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
                siret INTEGER NOT NULL,
                id_u INTEGER NOT NULL,
                note_p INTEGER,
                note_s INTEGER,
                FOREIGN KEY (siret) REFERENCES RESTAURANT(siret),
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
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
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

    public function getRestaurants() {
        $query = "SELECT * FROM RESTAURANT";
        
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
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
    public function addCritique($note_r, $commentaire, $siret, $id_u, $note_p = null, $note_s = null) {
        $query = "INSERT INTO CRITIQUE (note_r, commentaire, siret, id_u, note_p, note_s) 
                  VALUES (:note_r, :commentaire, :siret, :id_u, :note_p, :note_s)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':note_r', $note_r, PDO::PARAM_INT);
            $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
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
    public function addRestaurantTypeCuisine($siret, $id_type) {
        $query = "INSERT INTO ETRE (siret, id_type) VALUES (:siret, :id_type)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addFavoris($siret, $id_u) {
        $query = "INSERT INTO FAVORIS (siret, id_u) VALUES (:siret, :id_u)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
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
}
?>