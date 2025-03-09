<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use PHPUnit\Framework\TestCase;
use classes\model\RestaurantModel;
use classes\model\CritiqueModel;
use classes\model\UserModel;

class Test_Restaurant extends TestCase
{
    private CritiqueModel $critiqueModel;
    private RestaurantModel $restaurantModel;
    private UserModel $userModel;
    private int $id_u;
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

        // Création d'un utilisateur de test
        $this->userModel = new UserModel();
        $testEmail = "testeurgourmet@waw.com";
        if (!$this->userModel->checkEmailExists($testEmail)) {
            $this->userModel->addUser(
                                nom:"Testeur",
                                prenom:"Gourmet",
                                email:$testEmail,
                                mdp:"MiamMiam45",
                                role:"utilisateur",
                                );
        }
        $this->id_u = $this->userModel->getUserIdByEmail($testEmail);

        // Création d'une critique de test
        $this->critiqueModel = new CritiqueModel();
        $this->critiqueModel->addCritique(
                        commentaire: 'Bon restaurant!',
                        id_res: $this->id_res_1,
                        id_u: $this->id_u,
                        note_r: 3,
                        note_p: 5,
                        note_s: 3,
                    );

        // Création d'une deuxième critique de test
        $this->critiqueModel = new CritiqueModel();
        $this->critiqueModel->addCritique(
                        commentaire: 'Divin !!!',
                        id_res: $this->id_res_2,
                        id_u: $this->id_u,
                        note_r: 5,
                        note_p: 5,
                        note_s: 5,
                    );

    }

    public function testGetRestaurantById(): void
{
    $restaurant = $this->restaurantModel->getRestaurantById($this->id_res_1);
    $this->assertIsArray($restaurant);
    $this->assertNotEmpty($restaurant);
    $this->assertSame($this->id_res_1, $restaurant['id_res']);
    
    $restaurant2 = $this->restaurantModel->getRestaurantById($this->id_res_2);
    $this->assertIsArray($restaurant2);
    $this->assertNotEmpty($restaurant2);
    $this->assertSame($this->id_res_2, $restaurant2['id_res']);
}

public function testGetRestaurantsTriee(): void
    {
        $restaurants = $this->restaurantModel->getRestaurantsTriee();
        $this->assertIsArray($restaurants);
        $this->assertNotEmpty($restaurants);
        
        // On trouve les indices des restaurants de test dans le tableau trié
        $index_res_1 = null;
        $index_res_2 = null;
        
        foreach ($restaurants as $index => $restaurant) {
            if ($restaurant['id_res'] == $this->id_res_1) {
                $index_res_1 = $index;
            }
            if ($restaurant['id_res'] == $this->id_res_2) {
                $index_res_2 = $index;
            }
        }
        
        // On vérifie que les deux restaurants ont été trouvés
        $this->assertNotNull($index_res_1, "Restaurant 1 non trouvé dans les résultats triés");
        $this->assertNotNull($index_res_2, "Restaurant 2 non trouvé dans les résultats triés");
        
        // On vérifie que le restaurant 2 (avec note 5) est mieux classé que le restaurant 1 (avec note 3)
        $this->assertLessThan($index_res_1, $index_res_2, "Le restaurant avec les meilleures notes devrait être mieux classé");
    }

    public function testAddRestaurant(): void
    {
        // Données pour un nouveau restaurant
        $nom = "La Bonne Fourchette";
        $type_res = "gastronomique";
        $commune = "Tours";
        $departement = "Indre-et-Loire";
        $region = "Centre-Val de Loire";
        $coordonnees = "47.3941, 0.6848";
        $lien_site = "http://labonnefourchette.fr";
        $horaires = "12:00-14:30,19:00-22:30";
        $telephone = "0256565656";
        
        // Ajout du restaurant
        $id_res = $this->restaurantModel->addRestaurant(
            nom: $nom,
            type_res: $type_res,
            commune: $commune,
            departement: $departement,
            region: $region,
            coordonnees: $coordonnees,
            lien_site: $lien_site,
            horaires: $horaires,
            telephone: $telephone
        );
        
        // Vérification que l'ID retourné n'est pas false et peut être converti en entier
        $this->assertNotFalse($id_res);
        $this->assertIsNumeric($id_res);
        $id_res_int = (int)$id_res;
        $this->assertGreaterThan(0, $id_res_int);
        
        // Récupération du restaurant ajouté pour vérifier ses données
        $restaurant = $this->restaurantModel->getRestaurantById($id_res);
        
        // Vérifications des données
        $this->assertSame($nom, $restaurant['nom_res']);
        $this->assertSame($type_res, $restaurant['type_res']);
        $this->assertSame($commune, $restaurant['commune']);
        $this->assertSame($departement, $restaurant['departement']);
        $this->assertSame($region, $restaurant['region']);
        $this->assertSame($coordonnees, $restaurant['coordonnees']);
        $this->assertSame($lien_site, $restaurant['lien_site']);
        $this->assertSame($horaires, $restaurant['horaires_ouvert']);
        $this->assertSame($telephone, $restaurant['telephone']);
    }

    public function testUpdateRestaurant(): void
    {
        // On s'assure que le restaurant a été correctement récupéré
        $restaurant = $this->restaurantModel->getRestaurantById($this->id_res_1);
        if (empty($restaurant)) {
            $this->fail("Restaurant non trouvé pour tester la mise à jour");
        }
        
        // Mise à jour du restaurant
        $result = $this->restaurantModel->updateRestaurant(
                                            id_res:$this->id_res_1,
                                            nom: "Le Grandeur",
                                            type_res:"fast food",
                                            commune:"Marseille",
                                            departement:"Bouches-du-Rhônes",
                                            region:"Provence-Alpes-Côte d'Azur",
                                            coordonnees: "43.8566, 6.3522",
                                            lien_site: "http://legrandeur.fr",
                                            horaires: "09:00-23:00",
                                            telephone: "0245454550",
        );
        
        $this->assertTrue($result);
        
        // Vérification de la mise à jour
        $restaurant_updated = $this->restaurantModel->getRestaurantById($this->id_res_1);
        $this->assertIsArray($restaurant_updated);
        $this->assertNotEmpty($restaurant_updated);
        $this->assertSame($this->id_res_1, $restaurant_updated['id_res']);
        $this->assertSame("Le Grandeur", $restaurant_updated['nom_res']);
        $this->assertSame("fast food", $restaurant_updated['type_res']);
        $this->assertSame("Marseille", $restaurant_updated['commune']);
        $this->assertSame("Bouches-du-Rhônes", $restaurant_updated['departement']);
        $this->assertSame("Provence-Alpes-Côte d'Azur", $restaurant_updated['region']);
        $this->assertSame("43.8566, 6.3522", $restaurant_updated['coordonnees']);
        $this->assertSame("http://legrandeur.fr", $restaurant_updated['lien_site']);
        $this->assertSame("09:00-23:00", $restaurant_updated['horaires_ouvert']); // Note: corrigé horaires → horaires_ouvert
        $this->assertSame("0245454550", $restaurant_updated['telephone']);
    }
    public function testDeleteRestaurant(): void
    {
        // On s'assure que les restaurants ont été correctement récupérés
        $restaurant_1 = $this->restaurantModel->getRestaurantById($this->id_res_1);
        if (empty($restaurant_1)) {
            $this->fail("Aucun restaurant trouvé pour tester la suppression");
        }

        $restaurant_2 = $this->restaurantModel->getRestaurantById($this->id_res_2);
        if (empty($restaurant_2)) {
            $this->fail("Aucun restaurant trouvé pour tester la suppression");
        }
        
        // Suppression des restaurants 
        $result_1 = $this->restaurantModel->deleteRestaurant(id_res: $this->id_res_1);
        $this->assertTrue($result_1);

        $result_2 = $this->restaurantModel->deleteRestaurant(id_res: $this->id_res_2);
        $this->assertTrue($result_2);
        
        // Vérification de la suppression
        $restaurant_after_1 = $this->restaurantModel->getRestaurantById($this->id_res_1);
        $this->assertFalse($restaurant_after_1);

        $restaurant_after_2 = $this->restaurantModel->getRestaurantById($this->id_res_2);
        $this->assertFalse($restaurant_after_2);
    }
}