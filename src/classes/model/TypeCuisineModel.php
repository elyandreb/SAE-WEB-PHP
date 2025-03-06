<?php
namespace classes\model;

use \PDO;
use \PDOException;

class TypeCuisineModel {
    private $db;

    public function __construct() {
        $this->db = Model_bd::getInstance()->getConnection();
    }

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
            error_log("Erreur lors de la récupération/création du type de cuisine: " . $e->getMessage());
            // En cas d'erreur, on crée quand même un nouveau type
            $this->addTypeCuisine($nom);
            return $this->db->lastInsertId();
        }
    }

    public function addTypeCuisine($nom) {
        $query = "INSERT INTO TYPE_CUISINE (nom_type) VALUES (:nom)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du type de cuisine: " . $e->getMessage());
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
            error_log("Erreur lors de la mise à jour du type de cuisine: " . $e->getMessage());
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
            error_log("Erreur lors de la suppression du type de cuisine: " . $e->getMessage());
            return false;
        }
    }

    public function getAllTypesCuisine() {
        $query = "SELECT * FROM TYPE_CUISINE ORDER BY nom_type";
        
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des types de cuisine: " . $e->getMessage());
            return [];
        }
    }

    public function getTypeCuisineById($id_type) {
        $query = "SELECT * FROM TYPE_CUISINE WHERE id_type = :id_type";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du type de cuisine: " . $e->getMessage());
            return false;
        }
    }

    public function addRestaurantTypeCuisine($id_res, $id_type) {
        $query = "INSERT INTO ETRE (id_res, id_type) VALUES (:id_res, :id_type)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'association restaurant-type cuisine: " . $e->getMessage());
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
            error_log("Erreur lors de la suppression de l'association restaurant-type cuisine: " . $e->getMessage());
            return false;
        }
    }

    public function getTypesCuisineByRestaurant($id_res) {
        $query = "SELECT t.* FROM TYPE_CUISINE t 
                  JOIN ETRE e ON t.id_type = e.id_type 
                  WHERE e.id_res = :id_res
                  ORDER BY t.nom_type";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_res', $id_res, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des types de cuisine pour un restaurant: " . $e->getMessage());
            return [];
        }
    }
}
?>