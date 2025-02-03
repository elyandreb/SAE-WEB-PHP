<?php

use PHPUnit\Framework\TestCase;
use classes\Critique;

require_once __DIR__ . '/../classes/autoloader/autoload.php'; // Charge l'autoload

final class Test_critique extends TestCase
{   private Critique $critique;
    protected function setUp(): void
    {
        $this->critique = new Critique(1, 1, 1, 'Bon restaurant!', 5, '2023-10-01');
    }

    public function testGetId(): void
    {
        $this->assertSame(1, $this->critique->getId());
    }

    public function testGetIdRestaurant(): void
    {
        $this->assertSame(1, $this->critique->getIdRestaurant());
    }

    public function testGetIdUtilisateur(): void
    {
        $this->assertSame(1, $this->critique->getIdUtilisateur());
    }

    public function testGetCommentaire(): void
    {
        $this->assertSame('Bon restaurant!', $this->critique->getCommentaire());
    }

    public function testGetNote(): void
    {
        $this->assertSame(5, $this->critique->getNote());
    }

    public function testGetDateCritique(): void
    {
        $this->assertSame('2023-10-01', $this->critique->getDateCritique());
    }

    public function testSetId(): void
    {
        $this->critique->setId(2);
        $this->assertSame(2, $this->critique->getId());
    }

    public function testSetIdRestaurant(): void
    {
        $this->critique->setIdRestaurant(2);
        $this->assertSame(2, $this->critique->getIdRestaurant());
    }

    public function testSetIdUtilisateur(): void
    {
        $this->critique->setIdUtilisateur(2);
        $this->assertSame(2, $this->critique->getIdUtilisateur());
    }

    public function testSetCommentaire(): void
    {
        $this->critique->setCommentaire('Pas incroyable');
        $this->assertSame('Pas incroyable', $this->critique->getCommentaire());
    }

    public function testSetNote(): void
    {
        $this->critique->setNote(3);
        $this->assertSame(3, $this->critique->getNote());
    }

    public function testSetDateCritique(): void
    {
        $this->critique->setDateCritique('2023-10-02');
        $this->assertSame('2023-10-02', $this->critique->getDateCritique());
    }

    public function testToString(): void
    {
        $expectedString = 'id: 1 id_restaurant: 1 id_utilisateur: 1 commentaire: Bon restaurant! note: 5 date_critique: 2023-10-01';
        $this->assertSame($expectedString, (string) $this->critique);
    }
}
