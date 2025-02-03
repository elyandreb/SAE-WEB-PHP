<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use classes\Restaurant;
use PHPUnit\Framework\TestCase;

class Test_Restaurant extends TestCase
{
    public function testGetters(): void
    {
        $restaurant = new Restaurant(
            siret: 123456789,
            nom_res: "Le Gourmet",
            coordonnees: "48.8566, 2.3522",
            adresse: "123 Rue de Paris",
            lien_site: "http://legourmet.fr",
            horaires: "08:00-22:00",
            jourOuverture: "Lundi"
        );

        $this->assertEquals(123456789, $restaurant->getSiret());
        $this->assertEquals("Le Gourmet", $restaurant->getNomRes());
        $this->assertEquals("48.8566, 2.3522", $restaurant->getCoordonnees());
        $this->assertEquals("123 Rue de Paris", $restaurant->getAdresse());
        $this->assertEquals("http://legourmet.fr", $restaurant->getLienSite());
        $this->assertEquals("08:00-22:00", $restaurant->getHoraires());
        $this->assertEquals("Lundi", $restaurant->getJourOuverture());
    }

    public function testSetters(): void
    {
        $restaurant = new Restaurant(
            siret: 123456789,
            nom_res: "Le Gourmet",
            coordonnees: "48.8566, 2.3522",
            adresse: "123 Rue de Paris",
            lien_site: "http://legourmet.fr",
            horaires: "08:00-22:00",
            jourOuverture: "Lundi"
        );

        $restaurant->setSiret(987654321);
        $restaurant->setNomRes("Le Bistro");
        $restaurant->setCoordonnees("40.7128, -74.0060");
        $restaurant->setAdresse("456 Rue de New York");
        $restaurant->setLienSite("http://lebistro.fr");

        $this->assertEquals(987654321, $restaurant->getSiret());
        $this->assertEquals("Le Bistro", $restaurant->getNomRes());
        $this->assertEquals("40.7128, -74.0060", $restaurant->getCoordonnees());
        $this->assertEquals("456 Rue de New York", $restaurant->getAdresse());
        $this->assertEquals("http://lebistro.fr", $restaurant->getLienSite());
    }
}