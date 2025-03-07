<?php
namespace classes\model;

use \PDO;
use \PDOException;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Model_bd::getInstance()->getConnection();
    }

    public function addUser($nom, $prenom, $email, $mdp, $role) {
        $query = "INSERT INTO UTILISATEUR (nom_u, prenom_u, email_u, mdp_u, le_role) 
                  VALUES (:nom, :prenom, :email, :mdp, :role)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $hashed_password = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt->bindParam(':mdp', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de l'utilisateur: " . $e->getMessage());
            return false;
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
            error_log("Erreur lors de la connexion: " . $e->getMessage());
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

    public function getUserById($id) {
        $query = "SELECT * FROM UTILISATEUR WHERE id_u = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserPreferences($id) {
        $query = "SELECT tc.nom_type FROM UTILISATEUR_PREFERENCES up 
                  JOIN TYPE_CUISINE tc ON up.id_type = tc.id_type 
                  WHERE up.id_u = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPreferencesId($id) {
        $query = "SELECT id_type FROM UTILISATEUR_PREFERENCES WHERE id_u = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Retourne un tableau contenant uniquement les ID des types préférés
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_type');
    }
    

    public function updateUser($id, $nom, $prenom, $email) {
        $query = "UPDATE UTILISATEUR SET nom_u = :nom, prenom_u = :prenom, email_u = :email WHERE id_u = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateUserPassword($id, $oldPassword, $newPassword) {
        $user = $this->getUserById($id);
        if (password_verify($oldPassword, $user['mdp_u'])) {
            $query = "UPDATE UTILISATEUR SET mdp_u = :mdp WHERE id_u = :id";
            $stmt = $this->db->prepare($query);
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':mdp', $hashedPassword, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    public function deleteUser($id_u) {
        try {
            $this->db->beginTransaction();
            
            // Suppression des enregistrements associés dans d'autres tables
            $this->db->exec("DELETE FROM FAVORIS WHERE id_u = $id_u");
            $this->db->exec("DELETE FROM UTILISATEUR_PREFERENCES WHERE id_u = $id_u");
            
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
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    public function saveUserPreferences($userId, $preferences) {
        $query = "INSERT INTO UTILISATEUR_PREFERENCES (id_u, id_type) VALUES (:id_u, :id_type)";
        $stmt = $this->db->prepare($query);
    
        try {
            $this->db->beginTransaction();
            
            // Supprimer les préférences existantes
            $deleteQuery = "DELETE FROM UTILISATEUR_PREFERENCES WHERE id_u = :id_u";
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id_u', $userId, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Ajouter les nouvelles préférences
            foreach ($preferences as $type) {
                $stmt->bindParam(':id_u', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':id_type', $type, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de l'enregistrement des préférences: " . $e->getMessage());
            return false;
        }
    }
    public function isAdmin($userId) {
        $query = "SELECT le_role FROM UTILISATEUR WHERE id_u = :id_u";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_u', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $role = $stmt->fetchColumn();
            return $role === 'admin'; // Retourne true si l'utilisateur est admin, sinon false
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification du rôle admin: " . $e->getMessage());
            return false;
        }
    }
    
}