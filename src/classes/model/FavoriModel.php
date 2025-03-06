<?php
namespace classes\model;

use \PDO;
use \PDOException;

class FavoriModel {
    private $db;

    public function __construct() {
        $this->db = Model_bd::getInstance()->getConnection();
    }

    public function addFavori($siret, $id_u) {
        $query = "INSERT INTO FAVORIS (siret, id_u) VALUES (:siret, :id_u)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du favori: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFavori($siret, $id_u) {
        $query = "DELETE FROM FAVORIS WHERE siret = :siret AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du favori: " . $e->getMessage());
            return false;
        }
    }

    public function getFavorisByUser($id_u) {
        $query = "SELECT r.* FROM RESTAURANT r
                  JOIN FAVORIS f ON r.siret = f.siret
                  WHERE f.id_u = :id_u
                  ORDER BY r.nom_res";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des favoris: " . $e->getMessage());
            return [];
        }
    }

    public function isRestaurantFavori($siret, $id_u) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE siret = :siret AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification du favori: " . $e->getMessage());
            return false;
        }
    }

    public function countUserFavoris($id_u) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des favoris: " . $e->getMessage());
            return 0;
        }
    }

    public function countRestaurantFavoris($siret) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE siret = :siret";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':siret', $siret, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des favoris pour un restaurant: " . $e->getMessage());
            return 0;
        }
    }

    public function toggleFavori($siret, $id_u) {
        if ($this->isRestaurantFavori($siret, $id_u)) {
            return $this->deleteFavori($siret, $id_u);
        } else {
            return $this->addFavori($siret, $id_u);
        }
    }
}
?>