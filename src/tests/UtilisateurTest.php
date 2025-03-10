<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use PHPUnit\Framework\TestCase;
use classes\model\UserModel;
use classes\model\TypeCuisineModel;

class UtilisateurTest extends TestCase
{
    private UserModel $userModel;
    private TypeCuisineModel $typeCuisineModel;
    private string $testEmail;
    private int $userId;
    
    protected function setUp(): void
    {
        // Initialisation des modèles
        $this->userModel = new UserModel();
        $this->typeCuisineModel = new TypeCuisineModel();
        
        // Création d'un email de test unique
        $this->testEmail = "testuser".uniqid()."@example.com";
        
        // Création d'un utilisateur test
        $result = $this->userModel->addUser(
            nom: "Dupont",
            prenom: "Jean",
            email: $this->testEmail,
            mdp: "MotDePasse123",
            role: "utilisateur"
        );
        
        $this->assertTrue($result);
        $this->userId = $this->userModel->getUserIdByEmail($this->testEmail);
    }
    
    public function testAddUser(): void
    {
        // Création d'un nouvel utilisateur avec un email différent
        $newEmail = "newuser".uniqid()."@example.com";
        $result = $this->userModel->addUser(
            nom: "Martin",
            prenom: "Sophie",
            email: $newEmail,
            mdp: "Test456!",
            role: "utilisateur"
        );
        
        $this->assertTrue($result);
        
        // Vérification que l'utilisateur a bien été créé
        $newUserId = $this->userModel->getUserIdByEmail($newEmail);
        $this->assertIsNumeric($newUserId);
        $this->assertGreaterThan(0, $newUserId);

    }
    
    public function testLoginUser(): void
    {
        // Test de connexion avec des identifiants corrects
        $user = $this->userModel->loginUser($this->testEmail, "MotDePasse123");
        $this->assertIsArray($user);
        $this->assertNotEmpty($user);
        $this->assertEquals("Dupont", $user['nom_u']);
        $this->assertEquals("Jean", $user['prenom_u']);
        $this->assertEquals($this->testEmail, $user['email_u']);
        
        // Test de connexion avec un mot de passe incorrect
        $userFailedLogin = $this->userModel->loginUser($this->testEmail, "MauvaisMotDePasse");
        $this->assertFalse($userFailedLogin);
        
        // Test de connexion avec un email inexistant
        $nonExistentUser = $this->userModel->loginUser("inexistant@example.com", "MotDePasse123");
        $this->assertFalse($nonExistentUser);
    }
    
    public function testCheckEmailExists(): void
    {
        // Vérification qu'un email existant est détecté
        $exists = $this->userModel->checkEmailExists($this->testEmail);
        $this->assertTrue($exists);
        
        // Vérification qu'un email inexistant n'est pas détecté
        $notExists = $this->userModel->checkEmailExists("inexistant".uniqid()."@example.com");
        $this->assertFalse($notExists);
    }
    
    public function testGetUserById(): void
    {
        // Récupération de l'utilisateur par son ID
        $user = $this->userModel->getUserById($this->userId);
        $this->assertIsArray($user);
        $this->assertNotEmpty($user);
        $this->assertEquals($this->userId, $user['id_u']);
        $this->assertEquals("Dupont", $user['nom_u']);
        $this->assertEquals("Jean", $user['prenom_u']);
        $this->assertEquals($this->testEmail, $user['email_u']);
        $this->assertEquals("utilisateur", $user['le_role']);
    }
    
    public function testUpdateUser(): void
    {
        // Mise à jour des informations de l'utilisateur
        $newNom = "Dubois";
        $newPrenom = "Pierre";
        $newEmail = "updated".uniqid()."@example.com";
        
        $result = $this->userModel->updateUser(
            id: $this->userId,
            nom: $newNom,
            prenom: $newPrenom,
            email: $newEmail
        );
        
        $this->assertTrue($result);
        
        // Vérification que les informations ont été mises à jour
        $updatedUser = $this->userModel->getUserById($this->userId);
        $this->assertEquals($newNom, $updatedUser['nom_u']);
        $this->assertEquals($newPrenom, $updatedUser['prenom_u']);
        $this->assertEquals($newEmail, $updatedUser['email_u']);
        
        // Mise à jour de l'email de test pour les tests suivants
        $this->testEmail = $newEmail;
    }
    
    public function testUpdateUserPassword(): void
    {
        // Mise à jour du mot de passe
        $oldPassword = "MotDePasse123";
        $newPassword = "NouveauMotDePasse456";
        
        $result = $this->userModel->updateUserPassword(
            id: $this->userId,
            oldPassword: $oldPassword,
            newPassword: $newPassword
        );
        
        $this->assertTrue($result);
        
        // Vérification que le nouveau mot de passe fonctionne pour la connexion
        $user = $this->userModel->loginUser($this->testEmail, $newPassword);
        $this->assertIsArray($user);
        $this->assertNotEmpty($user);
        
        // Vérification que l'ancien mot de passe ne fonctionne plus
        $userFailedLogin = $this->userModel->loginUser($this->testEmail, $oldPassword);
        $this->assertFalse($userFailedLogin);
    }
    
    public function testSaveUserPreferences(): void
    {
        // Création de quelques types de cuisine pour les tests
        $typeId1 = $this->typeCuisineModel->getOrCreateTypeCuisine("gastronomique");
        $typeId2 = $this->typeCuisineModel->getOrCreateTypeCuisine("french");
        
        // Enregistrement des préférences
        $preferences = [$typeId1, $typeId2];
        $result = $this->userModel->saveUserPreferences($this->userId, $preferences);
        $this->assertTrue($result);
        
        // Vérification que les préférences ont été enregistrées
        $userPreferences = $this->userModel->getUserPreferencesId($this->userId);
        $this->assertIsArray($userPreferences);
        $this->assertCount(2, $userPreferences);

        // Vérification que les IDs retournés correspondent bien aux IDs que nous avons sauvegardés
        foreach ($preferences as $prefId) {
            $this->assertContains((int)$prefId, array_map('intval', $userPreferences));
        }
        
        // Test de mise à jour des préférences
        $typeId3 = $this->typeCuisineModel->getOrCreateTypeCuisine("fast food");
        $newPreferences = [$typeId3];
        $result = $this->userModel->saveUserPreferences($this->userId, $newPreferences);
        $this->assertTrue($result);
        
        // Vérification que les préférences ont été mises à jour
        $updatedPreferences = $this->userModel->getUserPreferencesId($this->userId);
        $this->assertIsArray($updatedPreferences);
        $this->assertCount(1, $updatedPreferences);
        
        // Vérification que le nouvel ID est présent
        $this->assertContains((int)$typeId3, array_map('intval', $updatedPreferences));
        
        // Vérification que les anciens IDs ne sont plus présents
        $this->assertNotContains((int)$typeId1, array_map('intval', $updatedPreferences));
        $this->assertNotContains((int)$typeId2, array_map('intval', $updatedPreferences));
    }
    
    public function testIsAdmin(): void
    {
        // Vérification que l'utilisateur n'est pas admin
        $isAdmin = $this->userModel->isAdmin($this->userId);
        $this->assertFalse($isAdmin);
        
        // Création d'un utilisateur admin
        $adminEmail = "admin".uniqid()."@example.com";
        $this->userModel->addUser(
            nom: "Admin",
            prenom: "Super",
            email: $adminEmail,
            mdp: "AdminPass123",
            role: "admin"
        );
        
        $adminId = $this->userModel->getUserIdByEmail($adminEmail);
        
        // Vérification que l'utilisateur est bien admin
        $isAdmin = $this->userModel->isAdmin($adminId);
        $this->assertTrue($isAdmin);

    }
    
    public function testDeleteUser(): void
    {
        // Création d'un utilisateur à supprimer
        $deleteEmail = "todelete".uniqid()."@example.com";
        $this->userModel->addUser(
            nom: "ASupprimer",
            prenom: "Utilisateur",
            email: $deleteEmail,
            mdp: "DeleteMe123",
            role: "utilisateur"
        );
        
        $deleteId = $this->userModel->getUserIdByEmail($deleteEmail);
        
        // Suppression de l'utilisateur
        $result = $this->userModel->deleteUser($deleteId);
        $this->assertTrue($result);
        
        // Vérification que l'utilisateur a bien été supprimé
        $deletedUser = $this->userModel->getUserById($deleteId);
        $this->assertFalse($deletedUser);
        
        // Vérification que l'email n'existe plus
        $emailExists = $this->userModel->checkEmailExists($deleteEmail);
        $this->assertFalse($emailExists);
    }
    
    public function testGetUserPreferences(): void
    {
        // Création de types de cuisine pour les tests
        $typeId1 = $this->typeCuisineModel->getOrCreateTypeCuisine("restaurant");
        $typeId2 = $this->typeCuisineModel->getOrCreateTypeCuisine("fast food");
        
        // Enregistrement des préférences
        $preferences = [$typeId1, $typeId2];
        $this->userModel->saveUserPreferences($this->userId, $preferences);
        
        // Récupération des préférences avec getUserPreferences
        $userPreferences = $this->userModel->getUserPreferences($this->userId);
        $this->assertIsArray($userPreferences);
        $this->assertCount(2, $userPreferences);
        
        // Vérification que les noms des types de cuisine sont bien récupérés
        $cuisineNames = array_column($userPreferences, 'nom_type');
        $this->assertContains("restaurant", $cuisineNames);
        $this->assertContains("fast food", $cuisineNames);
    }
}