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
}
?>