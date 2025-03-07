<?php

namespace classes\model;

use \PDO;
use \PDOException;

class BD_Restaurant {
    private $db;
    
    public function __construct($db = null) {
        $this->db = $db;
    }

    public function addRestaurant($nom, $commune, $departement, $region, $coordonnees, $lien_site, $horaires, $telephone = null) {
        $query = "INSERT INTO RESTAURANT (nom_res, commune, departement, region, coordonnees, lien_site, horaires_ouvert, telephone) 
                  VALUES (:nom, :commune, :departement, :region, :coordonnees, :lien_site, :horaires, :telephone)";
        
        try {
            $stmt = $this->db->prepare($query);
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

    public function updateRestaurant($id_res, $nom, $commune, $departement, $region, $coordonnees, $lien_site, $horaires, $telephone = null) {
        $query = "UPDATE RESTAURANT SET
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

}
?>