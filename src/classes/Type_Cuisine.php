<?php
namespace classes;

class Type_Cuisine{
    private int $id;
    private string $nom_type_cuisine;

    public function __construct(int $id, string $nom_type_cuisine){
        $this->id = $id;
        $this->nom_type_cuisine = $nom_type_cuisine;
    }

    public function getId(): int{
        return $this->id;
    }
    public function getNomTypeCuisine(): string{
        return $this->nom_type_cuisine;
    }
    
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setNomTypeCuisine(string $nom_type_cuisine): void{
        $this->nom_type_cuisine = $nom_type_cuisine;
    }
    public function __toString(): string{
        return "id: ".$this->id." nom_type_cuisine: ".$this->nom_type_cuisine;
    }
    
}