<?php
namespace classes;

class Restaurant{
    private int $siret;
    private string $nom_res;
    private string $coordonnees;
    private string $departement;
    private string $ville;
    private string $region;
    private string $lien_site;
    private string $heureDebut;

    private string $heureFin;

    private string $jourOuverture;

    public function __construct(int $siret, string $nom_res, string $coordonnees, string $adresse, string $lien_site, string $horaires, string $jourOuverture){
        $this->siret = $siret;
        $this->nom_res = $nom_res;
        $this->coordonnees = $coordonnees;
        $this->adresse = $adresse;
        $this->lien_site = $lien_site;
        $this->horaire = $horaires;
        $this->jourOuverture = $jourOuverture;
    }
    public function getHeureDebut(): string{
        return $this->heureDebut;
    }
    public function getHeureFin(): string{
        return $this->heureFin;
    }
    public function getJourOuverture(): string{
        return $this->jourOuverture;
    }
    public function getSiret(): int{
        return $this->siret;
    }
    public function getNomRes(): string{
        return $this->nom_res;
    }
    public function getCoordonnees(): string{
        return $this->coordonnees;
    }
    public function getDepartement(): string{
        return $this->departement;
    }
    public function getVille(): string{
        return $this->ville;
    }
    public function getRegion(): string{
        return $this->region;
    }
    public function getLienSite(): string{
        return $this->lien_site;
    }
    public function setSiret(int $siret): void{
        $this->siret = $siret;
    }
    public function setNomRes(string $nom_res): void{
        $this->nom_res = $nom_res;
    }
    public function setCoordonnees(string $coordonnees): void{
        $this->coordonnees = $coordonnees;
    }
    public function setDepartement(string $departement): void{
        $this->departement = $departement;
    }
    public function setVille(string $ville): void{
        $this->ville = $ville;
    }
    public function setRegion(string $region): void{
        $this->region = $region;
    }
    public function setLienSite(string $lien_site): void{
        $this->lien_site = $lien_site;
    }
}
