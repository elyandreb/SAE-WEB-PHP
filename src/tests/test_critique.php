<?php

use PHPUnit\Framework\TestCase;
use classes\model\CritiqueModel;
use classes\model\RestaurantModel;
use classes\model\UserModel;

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

final class Test_critique extends TestCase
{   
    private CritiqueModel $critiqueModel;
    private RestaurantModel $restaurantModel;
    private int $id_critique;
    private int $id_u;
    private int $id_res;
    private UserModel $userModel;
    
    protected function setUp(): void
    {
        // Créer un restaurant de test
        $this->restaurantModel = new RestaurantModel();
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

        // Créer un utilisateur de test
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

        // Créer une critique de test
        $this->critiqueModel = new CritiqueModel();
        $this->critiqueModel->addCritique(
                        note_r: 2,
                        commentaire: 'Bon restaurant!',
                        id_res: $this->id_res,
                        id_u: $this->id_u,
                        note_p: 5,
                        note_s: 2
                    );
        
        // Récupérer l'ID de la critique créée
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        if (!empty($critiques)) {
            $this->id_critique = $critiques[0]['id_c'];
        }
    }

    public function testGetCritiqueByUser(): void
    {
        $critiques = $this->critiqueModel->getCritiquesByUser($this->id_u);
        $this->assertIsArray($critiques);
        $this->assertNotEmpty($critiques);
        $this->assertSame($this->id_u, $critiques[0]['id_u']);
    }

    public function testGetMoyenneCritiquesByRestaurant(): void
    {
        $moyenne = $this->critiqueModel->getMoyenneCritiquesByRestaurant($this->id_res);
        $this->assertSame(3.0, $moyenne);
    }

    public function testGetCritiquesByRestaurant(): void
    {
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertIsArray($critiques);
        $this->assertNotEmpty($critiques);
        $this->assertSame($this->id_res, $critiques[0]['id_res']);
    }

    public function testGetNameUserCritique(): void
    {
        $nom = $this->critiqueModel->getNameUserCritique($this->id_u);
        $this->assertSame('Testeur', $nom);
    }

    public function testUpdateCritique(): void
    {
        // S'assurer que id_critique a été correctement récupéré
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        if (empty($critiques)) {
            $this->fail("Aucune critique trouvée pour tester la mise à jour");
        }
        
        $id_c = $critiques[0]['id_c'];
        
        // Mettre à jour la critique
        $result = $this->critiqueModel->updateCritique(
            id_c: $id_c,
            note_r: 5,
            commentaire: 'Waouh !', 
            note_p: 5, 
            note_s: 5
        );
        
        $this->assertTrue($result);
        
        // Vérifier que la mise à jour a été effectuée
        $critiques_updated = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertSame(5, $critiques_updated[0]['note_r']);
        $this->assertSame('Waouh !', $critiques_updated[0]['commentaire']);
    }

    public function testDeleteCritique(): void
    {
        // S'assurer que id_critique a été correctement récupéré
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        if (empty($critiques)) {
            $this->fail("Aucune critique trouvée pour tester la suppression");
        }
        
        $id_c = $critiques[0]['id_c'];
        
        // Supprimer la critique
        $result = $this->critiqueModel->deleteCritique(id_c: $id_c);
        $this->assertTrue($result);
        
        // Vérifier que la critique a été supprimée
        $critiques_after = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertEmpty($critiques_after);
    }
}