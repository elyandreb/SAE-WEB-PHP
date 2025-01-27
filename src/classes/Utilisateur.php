<?php
namespace classes;
class Utilisateur{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $mdp;
    private int $id_role;
    private string $email;

    public function __construct(int $id, int $id_role, string $nom, string $prenom, string $mdp, string $email){
        $this->nom = $nom;
        $this->id = $id;
        $this->prenom = $prenom;
        $this->mdp = $mdp;
        $this->id_role = $id_role;
        $this->email = $email;
        
    }
    public function getId(): int{
        return $this->id;
    }
    public function getNom(): string{
        return $this->nom;
    }
    public function getPrenom(): string{
        return $this->prenom;
    }
    public function getMdp(): string{
        return $this->mdp;
    }
    public function getIdRole(): int{
        return $this->id_role;
    }
    public function getEmail(): string{
        return $this->email;
    }
    public function setId(int $id): void{
        $this->id = $id;
    }
    public function setNom(string $nom): void{
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void{
        $this->prenom = $prenom;
    }
    public function setMdp(string $mdp): void{
        $this->mdp = $mdp;
    }
    public function setIdRole(int $id_role): void{
        $this->id_role = $id_role;
    }
    public function setEmail(string $email): void{
        $this->email = $email;
    }
    public function __toString(): string{
        return "id: ".$this->id." nom: ".$this->nom." prenom: ".$this->prenom." mdp: ".$this->mdp." id_role: ".$this->id_role." email: ".$this->email;
    }
    
}

