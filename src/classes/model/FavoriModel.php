<?php
namespace classes\model;

use \PDO;
use \PDOException;

class FavoriModel {
    private $db;

    public function __construct() {
        $this->db = Model_bd::getInstance()->getConnection();
    }

    public function addFavori($id_res, $id_u) {
        $query = "INSERT INTO FAVORIS (id_res, id_u) VALUES (:id_res, :id_u)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_u', $id_u, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du favori: " . $e->getMessage());
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
            error_log("Erreur lors de la suppression du favori: " . $e->getMessage());
            return false;
        }
    }

    public function getFavorisByUser($id_u) {
        $query = "SELECT r.* FROM RESTAURANT r
                  JOIN FAVORIS f ON r.id_res = f.id_res
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

    public function isRestaurantFavori($id_res, $id_u) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE id_res = :id_res AND id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
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

    public function countRestaurantFavoris($id_res) {
        $query = "SELECT COUNT(*) FROM FAVORIS WHERE id_res = :id_res";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des favoris pour un restaurant: " . $e->getMessage());
            return 0;
        }
    }

    public function toggleFavori($id_res, $id_u) {
        if ($this->isRestaurantFavori($id_res, $id_u)) {
            return $this->deleteFavori($id_res, $id_u);
        } else {
            return $this->addFavori($id_res, $id_u);
        }
    }
}
?>