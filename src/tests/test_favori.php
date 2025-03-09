<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use PHPUnit\Framework\TestCase;
use classes\model\FavoriModel;
use classes\model\RestaurantModel;
use classes\model\UserModel;

class Test_Favori extends TestCase
{
    private FavoriModel $favoriModel;
    private RestaurantModel $restaurantModel;
    private UserModel $userModel;
    private int $id_u;
    private int $id_u2;
    private int $id_res_1;
    private int $id_res_2;

    protected function setUp(): void
    {
        // Création d'un restaurant de test
        $this->restaurantModel = new RestaurantModel();
        $this->id_res_1 = $this->restaurantModel->addRestaurant(
                                        nom: "Le Gourmet",
                                        type_res:"restaurant",
                                        commune:"Olivet",
                                        departement:"Loiret",
                                        region:"Centre-Val de Loire",
                                        coordonnees: "48.8566, 2.3522",
                                        lien_site: "http://legourmet.fr",
                                        horaires: "08:00-22:00",
                                        telephone: "0245454545",
                                        );

        // Création d'un deuxième restaurant de test
        $this->id_res_2 = $this->restaurantModel->addRestaurant(
                                            nom: "Le Mangeur",
                                            type_res:"restaurant",
                                            commune:"Le Mans",
                                            departement:"Sarthe",
                                            region:"Pays de la Loire",
                                            coordonnees: "58.8566, 16.3522",
                                            lien_site: "http://lemangeur.fr",
                                            horaires: "08:00-22:00",
                                            telephone: "0272727272",
                                            );

        // Création d'un utilisateur test
        $this->userModel = new UserModel();
        $testEmail = "testeurfavori@waw.com";
        if (!$this->userModel->checkEmailExists($testEmail)) {
            $this->userModel->addUser(
                                nom:"Testeur",
                                prenom:"Favori",
                                email:$testEmail,
                                mdp:"MiamMiam45",
                                role:"utilisateur",
                                );
        }
        $this->id_u = $this->userModel->getUserIdByEmail($testEmail);

        // Création d'un deuxième utilisateur test
        $testEmail2 = "testeurfavori2@waw.com";
        if (!$this->userModel->checkEmailExists($testEmail2)) {
            $this->userModel->addUser(
                                nom:"Testeur2",
                                prenom:"Favori2",
                                email:$testEmail2,
                                mdp:"MiamMiam46",
                                role:"utilisateur",
                                );
        }
        $this->id_u2 = $this->userModel->getUserIdByEmail($testEmail2);

        // Initialisation du modèle des favoris
        $this->favoriModel = new FavoriModel();
    }

    public function testAddFavori(): void
    {
        // Ajout d'un favori
        $result = $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($result);
        
        // Vérification que le favori a bien été ajouté
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($isFavori);
    }

    public function testDeleteFavori(): void
    {
        // Ajout d'un favori pour le test
        $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
        
        // Vérification que le favori existe
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($isFavori);
        
        // Suppression du favori
        $result = $this->favoriModel->deleteFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($result);
        
        // Vérification que le favori a bien été supprimé
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertFalse($isFavori);
    }

    public function testGetFavorisByUser(): void
{
    // Comptage des favoris initiaux
    $initialCount = count($this->favoriModel->getFavorisByUser($this->id_u));
    
    // Ajout de plusieurs favoris
    $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
    $this->favoriModel->addFavori($this->id_res_2, $this->id_u);
    
    // Récupération des favoris de l'utilisateur
    $favoris = $this->favoriModel->getFavorisByUser($this->id_u);
    
    // Vérification que le nombre a augmenté correctement
    $this->assertCount($initialCount + 2, $favoris);
    
    // Vérification que les restaurants ajoutés sont présents
    $ids_resto = array_column($favoris, 'id_res');
    $this->assertContains($this->id_res_1, $ids_resto);
    $this->assertContains($this->id_res_2, $ids_resto);
}

    public function testIsRestaurantFavori(): void
    {
        // Ajout d'un favori
        $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
        
        // Vérification pour un restaurant qui est favori
        $isFavori1 = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($isFavori1);
        
        // Vérification pour un restaurant qui n'est pas favori
        $isFavori2 = $this->favoriModel->isRestaurantFavori($this->id_res_2, $this->id_u);
        $this->assertFalse($isFavori2);
    }

    public function testCountUserFavoris(): void
{
    // Comptage des favoris initiaux
    $initialCount = $this->favoriModel->countUserFavoris($this->id_u);
    
    // Ajout de plusieurs favoris
    $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
    $this->favoriModel->addFavori($this->id_res_2, $this->id_u);
    
    // Comptage des favoris
    $count = $this->favoriModel->countUserFavoris($this->id_u);
    $this->assertEquals($initialCount + 2, $count);
    
    // Vérification pour un utilisateur sans favoris ou avec un compte différent
    $initialCount2 = $this->favoriModel->countUserFavoris($this->id_u2);
    $count2 = $this->favoriModel->countUserFavoris($this->id_u2);
    $this->assertEquals($initialCount2, $count2);
}

    public function testCountRestaurantFavoris(): void
    {
        // Ajout de plusieurs favoris pour le même restaurant
        $this->favoriModel->addFavori($this->id_res_1, $this->id_u);
        $this->favoriModel->addFavori($this->id_res_1, $this->id_u2);
        
        // Comptage des favoris pour un restaurant
        $count = $this->favoriModel->countRestaurantFavoris($this->id_res_1);
        $this->assertEquals(2, $count);
        
        // Vérification pour un restaurant sans favoris
        $count2 = $this->favoriModel->countRestaurantFavoris($this->id_res_2);
        $this->assertEquals(0, $count2);
    }

    public function testToggleFavori(): void
    {
        // Vérification initiale
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertFalse($isFavori);
        
        // Premier toggle (ajout)
        $result1 = $this->favoriModel->toggleFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($result1);
        
        // Vérification que le favori a été ajouté
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($isFavori);
        
        // Deuxième toggle (suppression)
        $result2 = $this->favoriModel->toggleFavori($this->id_res_1, $this->id_u);
        $this->assertTrue($result2);
        
        // Vérification que le favori a été supprimé
        $isFavori = $this->favoriModel->isRestaurantFavori($this->id_res_1, $this->id_u);
        $this->assertFalse($isFavori);
    }
}