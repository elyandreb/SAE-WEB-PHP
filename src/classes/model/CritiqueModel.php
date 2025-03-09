<?php
namespace classes\model;

use \PDO;
use \PDOException;

class CritiqueModel {
    private $db;

    public function __construct() {
        $this->db = Model_bd::getInstance()->getConnection();
    }

    public function getAvis() {
        $query = "SELECT * FROM CRITIQUE";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des avis: " . $e->getMessage());
            return [];
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

    public function getMoyenneCritiquesByRestaurant($id_res) {
        $query = "SELECT (AVG(note_r) + AVG(note_p) + AVG(note_s)) / 3 AS moyenne_generale 
                  FROM CRITIQUE 
                  WHERE id_res = :id_res";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCritiquesByRestaurant($id_res): array {
        $query = "SELECT c.*, u.nom_u AS user_name 
                  FROM CRITIQUE c 
                  JOIN UTILISATEUR u ON c.id_u = u.id_u 
                  WHERE c.id_res = :id_res 
                  ORDER BY c.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    

    public function getNameUserCritique($id_u) {
        $query = "SELECT nom_u FROM UTILISATEUR WHERE id_u = :id_u";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    

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
            error_log("Erreur lors de l'ajout de la critique: " . $e->getMessage());
            return false;
        }
    }

    public function updateCritique($id_c, $commentaire, $note_r = null, $note_p = null, $note_s = null) {
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
            error_log("Erreur lors de la mise à jour de la critique: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCritique($id_c) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des likes associés à cette critique
            $stmt_aimer = $this->db->prepare("DELETE FROM AIMER WHERE id_c = :id_c");
            $stmt_aimer->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $stmt_aimer->execute();
            
            // Suppression de la critique
            $stmt = $this->db->prepare("DELETE FROM CRITIQUE WHERE id_c = :id_c");
            $stmt->bindParam(':id_c', $id_c, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression de la critique: " . $e->getMessage());
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
            error_log("Erreur lors de l'ajout du like: ");
        }
    }
}