<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use PHPUnit\Framework\TestCase;
use classes\model\TypeCuisineModel;
use classes\model\RestaurantModel;

class Test_Type_Cuisine extends TestCase
{
    private TypeCuisineModel $typeCuisineModel;
    private RestaurantModel $restaurantModel;
    private int $id_type_1;
    private int $id_type_2;
    private int $id_res;

    protected function setUp(): void
    {
        // Initialisation des modèles
        $this->typeCuisineModel = new TypeCuisineModel();
        $this->restaurantModel = new RestaurantModel();
        
        // Création d'un restaurant de test
        $this->id_res = $this->restaurantModel->addRestaurant(
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
        
        // Création des types de cuisine de test
        $this->id_type_1 = $this->typeCuisineModel->getOrCreateTypeCuisine("Italien");
        $this->id_type_2 = $this->typeCuisineModel->getOrCreateTypeCuisine("Japonais");
        
        // Association du restaurant aux types de cuisine
        $this->typeCuisineModel->addRestaurantTypeCuisine($this->id_res, $this->id_type_1);
    }

    public function testGetOrCreateTypeCuisine(): void
    {
        // Test de création d'un nouveau type de cuisine
        $id_type_new = $this->typeCuisineModel->getOrCreateTypeCuisine("Chinois");
        $this->assertIsNumeric($id_type_new);
        
        // Test de récupération d'un type de cuisine existant
        $id_type_existing = $this->typeCuisineModel->getOrCreateTypeCuisine("italien"); // Test casse différente
        $this->assertSame($this->id_type_1, $id_type_existing);
    }

    public function testGetAllTypesCuisine(): void
    {
        $types = $this->typeCuisineModel->getAllTypesCuisine();
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);
        
        // Vérification que nos types créés sont dans la liste
        $found_type_1 = false;
        $found_type_2 = false;
        
        foreach ($types as $type) {
            if ($type['id_type'] == $this->id_type_1) {
                $found_type_1 = true;
            }
            if ($type['id_type'] == $this->id_type_2) {
                $found_type_2 = true;
            }
        }
        
        $this->assertTrue($found_type_1, "Type de cuisine 1 non trouvé dans la liste");
        $this->assertTrue($found_type_2, "Type de cuisine 2 non trouvé dans la liste");
    }

    public function testGetTypeCuisineById(): void
    {
        $type = $this->typeCuisineModel->getTypeCuisineById($this->id_type_1);
        $this->assertIsArray($type);
        $this->assertNotEmpty($type);
        $this->assertSame($this->id_type_1, $type['id_type']);
        $this->assertSame("Italien", $type['nom_type']);
        
        $type2 = $this->typeCuisineModel->getTypeCuisineById($this->id_type_2);
        $this->assertIsArray($type2);
        $this->assertNotEmpty($type2);
        $this->assertSame($this->id_type_2, $type2['id_type']);
        $this->assertSame("Japonais", $type2['nom_type']);
    }

    public function testUpdateTypeCuisine(): void
    {
        // Mise à jour du type de cuisine
        $result = $this->typeCuisineModel->updateTypeCuisine(
            id_type: $this->id_type_1,
            nom: "Cuisine Italienne"
        );
        
        $this->assertTrue($result);
        
        // Vérification de la mise à jour
        $type_updated = $this->typeCuisineModel->getTypeCuisineById($this->id_type_1);
        $this->assertIsArray($type_updated);
        $this->assertNotEmpty($type_updated);
        $this->assertSame($this->id_type_1, $type_updated['id_type']);
        $this->assertSame("Cuisine Italienne", $type_updated['nom_type']);
    }

    public function testAddRestaurantTypeCuisine(): void
    {
        // Association d'un nouveau type de cuisine au restaurant
        $result = $this->typeCuisineModel->addRestaurantTypeCuisine($this->id_res, $this->id_type_2);
        $this->assertTrue($result);
        
        // Vérification de l'association
        $types = $this->typeCuisineModel->getTypesCuisineByRestaurant($this->id_res);
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);
        
        $found_type_1 = false;
        $found_type_2 = false;
        
        foreach ($types as $type) {
            if ($type['id_type'] == $this->id_type_1) {
                $found_type_1 = true;
            }
            if ($type['id_type'] == $this->id_type_2) {
                $found_type_2 = true;
            }
        }
        
        $this->assertTrue($found_type_1, "Type de cuisine 1 non trouvé pour le restaurant");
        $this->assertTrue($found_type_2, "Type de cuisine 2 non trouvé pour le restaurant");
    }

    public function testGetTypesCuisineByRestaurant(): void
    {
        $types = $this->typeCuisineModel->getTypesCuisineByRestaurant($this->id_res);
        $this->assertIsArray($types);
        $this->assertNotEmpty($types);
        
        // Vérification que le type de cuisine 1 est associé au restaurant
        $found = false;
        foreach ($types as $type) {
            if ($type['id_type'] == $this->id_type_1) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, "Type de cuisine 1 non trouvé pour le restaurant");
    }

    public function testRemoveRestaurantTypeCuisine(): void
    {
        // Suppression de l'association entre le restaurant et le type de cuisine
        $result = $this->typeCuisineModel->removeRestaurantTypeCuisine($this->id_res, $this->id_type_1);
        $this->assertTrue($result);
        
        // Vérification de la suppression
        $types = $this->typeCuisineModel->getTypesCuisineByRestaurant($this->id_res);
        
        $found = false;
        if (!empty($types)) {
            foreach ($types as $type) {
                if ($type['id_type'] == $this->id_type_1) {
                    $found = true;
                    break;
                }
            }
        }
        
        $this->assertFalse($found, "L'association entre le restaurant et le type de cuisine 1 n'a pas été supprimée");
    }

    public function testDeleteTypeCuisine(): void
    {
        // Suppression du type de cuisine
        $result = $this->typeCuisineModel->deleteTypeCuisine($this->id_type_2);
        $this->assertTrue($result);
        
        // Vérification de la suppression
        $type = $this->typeCuisineModel->getTypeCuisineById($this->id_type_2);
        $this->assertFalse($type, "Le type de cuisine n'a pas été supprimé");
    }
}