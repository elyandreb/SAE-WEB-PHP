<?php

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload


use classes\Type_Cuisine;
use PHPUnit\Framework\TestCase;
class Test_Type_Cuisine extends TestCase{
    public function testCuisine(): void
    {
        $typeCuisine = new Type_Cuisine(id: 1, nom_type_cuisine: "Italien");
        $typeCuisine->setId(id: 1);
        $typeCuisine->setNomTypeCuisine(nom_type_cuisine: "Chinoise");
        $this->assertEquals(expected: 1, actual: $typeCuisine->getId());

    }
}