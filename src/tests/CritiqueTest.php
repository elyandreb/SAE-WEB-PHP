<?php
use PHPUnit\Framework\TestCase;
use classes\model\CritiqueModel;
use classes\model\RestaurantModel;
use classes\model\UserModel;
require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

final class CritiqueTest extends TestCase
{ 
    private CritiqueModel $critiqueModel;
    private RestaurantModel $restaurantModel;
    private UserModel $userModel;
    private int $id_u;
    private int $id_res;
    
    protected function setUp(): void
    {
        // Création d'un restaurant de test
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
            note_r: 2,
            commentaire: 'Bon restaurant!',
            id_res: $this->id_res,
            id_u: $this->id_u,
            note_p: 5,
            note_s: 2
        );
    }

    /**
     * @covers CritiqueModel::getCritiquesByUser
     */
    public function testGetCritiqueByUser(): void
    {
        $critiques = $this->critiqueModel->getCritiquesByUser($this->id_u);
        $this->assertIsArray($critiques);
        $this->assertNotEmpty($critiques);
        $this->assertSame($this->id_u, $critiques[0]['id_u']);
    }

    /**
     * @covers CritiqueModel::getMoyenneCritiquesByRestaurant
     */
    public function testGetMoyenneCritiquesByRestaurant(): void
    {
        $moyenne = $this->critiqueModel->getMoyenneCritiquesByRestaurant($this->id_res);
        $this->assertSame(3.0, $moyenne);
    }

    /**
     * @covers CritiqueModel::getCritiquesByRestaurant
     */
    public function testGetCritiquesByRestaurant(): void
    {
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertIsArray($critiques);
        $this->assertNotEmpty($critiques);
        $this->assertSame($this->id_res, $critiques[0]['id_res']);
    }

    /**
     * @covers CritiqueModel::getNameUserCritique
     */
    public function testGetNameUserCritique(): void
    {
        $nom = $this->critiqueModel->getNameUserCritique($this->id_u);
        $this->assertSame('Testeur', $nom);
    }

    /**
     * @covers CritiqueModel::addCritique
     */
    public function testAddCritique(): void
    {
        // Vérification que les restaurants et utilisateurs de test existent
        $this->assertIsInt($this->id_res);
        $this->assertGreaterThan(0, $this->id_res);
        $this->assertIsInt($this->id_u);
        $this->assertGreaterThan(0, $this->id_u);
        
        // Données pour une nouvelle critique
        $note_r = 4;
        $commentaire = "Excellente expérience culinaire !";
        $note_p = 4;
        $note_s = 5;
        
        // Ajout de la critique
        $result = $this->critiqueModel->addCritique(
            note_r: $note_r,
            commentaire: $commentaire,
            id_res: $this->id_res,
            id_u: $this->id_u,
            note_p: $note_p,
            note_s: $note_s
        );
        
        // Vérification que l'ajout a réussi
        $this->assertTrue($result);
        
        // Récupération des critiques pour le restaurant
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        
        // Vérification que la critique a bien été ajoutée
        $this->assertIsArray($critiques);
        $this->assertNotEmpty($critiques);
        
        // Recherche de la critique nouvellement ajoutée
        $found = false;
        foreach ($critiques as $critique) {
            if ($critique['commentaire'] === $commentaire &&
                $critique['note_r'] == $note_r &&
                $critique['note_p'] == $note_p &&
                $critique['note_s'] == $note_s &&
                $critique['id_res'] == $this->id_res &&
                $critique['id_u'] == $this->id_u) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, "La critique ajoutée n'a pas été trouvée dans les critiques du restaurant");
    }

    /**
     * @covers CritiqueModel::updateCritique
     */
    public function testUpdateCritique(): void
    {
        // On s'assure que la critique a été correctement récupérée
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        if (empty($critiques)) {
            $this->fail("Aucune critique trouvée pour tester la mise à jour");
        }
        
        $id_c = $critiques[0]['id_c'];
        
        // Mise à jour de la critique
        $result = $this->critiqueModel->updateCritique(
            id_c: $id_c,
            note_r: 5,
            commentaire: 'Waouh !', 
            note_p: 5, 
            note_s: 5
        );
        
        $this->assertTrue($result);
        
        // Vérification de la mise à jour
        $critiques_updated = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertSame(5, $critiques_updated[0]['note_r']);
        $this->assertSame('Waouh !', $critiques_updated[0]['commentaire']);
    }

    /**
     * @covers CritiqueModel::deleteCritique
     */
    public function testDeleteCritique(): void
    {
        // On s'assure que la critique a été correctement récupérée
        $critiques = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        if (empty($critiques)) {
            $this->fail("Aucune critique trouvée pour tester la suppression");
        }
        
        $id_c = $critiques[0]['id_c'];
        
        // Suppression de la critique
        $result = $this->critiqueModel->deleteCritique(id_c: $id_c);
        $this->assertTrue($result);
        
        // Vérification de la suppression
        $critiques_after = $this->critiqueModel->getCritiquesByRestaurant($this->id_res);
        $this->assertEmpty($critiques_after);
    }
}