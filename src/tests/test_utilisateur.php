<?php
require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

use classes\Utilisateur;
use PHPUnit\Framework\TestCase;

class Test_Utilisateur extends TestCase{
    public function testCreationUtilisateur()
    {
        // Construction correcte de l'objet avec tous les paramètres
        $utilisateur = new Utilisateur(1, 1, 'John', 'Doe', 'password', 'john.doe@example.com');
        
        $this->assertEquals('John', $utilisateur->getNom());
        $this->assertEquals('Doe', $utilisateur->getPrenom());
        $this->assertEquals('password', $utilisateur->getMdp());
        $this->assertEquals('john.doe@example.com', $utilisateur->getEmail());
        $this->assertEquals(1, $utilisateur->getIdRole());
        $this->assertEquals(1, $utilisateur->getId());
    }

    public function testNomComplet()
    {
        // Construction correcte de l'objet
        $utilisateur = new Utilisateur(1, 1, 'John', 'Doe', 'password', 'john.doe@example.com');
        
        // Test avec le nom complet
        $this->assertEquals('John Doe', $utilisateur->getNom() . ' ' . $utilisateur->getPrenom());
    }

    public function testModificationNom()
    {
        $utilisateur = new Utilisateur(1, 1, 'John', 'Doe', 'password', 'john.doe@example.com');
        $utilisateur->setNom('Jane');
        $this->assertEquals('Jane', $utilisateur->getNom());
    }

    public function testModificationPrenom()
    {
        $utilisateur = new Utilisateur(1, 1, 'John', 'Doe', 'password', 'john.doe@example.com');
        $utilisateur->setPrenom('Smith');
        $this->assertEquals('Smith', $utilisateur->getPrenom());
    }
}
