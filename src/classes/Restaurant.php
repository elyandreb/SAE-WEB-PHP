<?php
namespace classes;

class Restaurant{
    private int $siret;
    private string $nom_res;
    private string $coordonnees;
    private string $adresse;
    private string $lien_site;
    private string $jourOuverture;
    private string $horaire;

    public function __construct(int $siret, string $nom_res, string $coordonnees, string $adresse, string $lien_site, string $horaires, string $jourOuverture){
        $this->siret = $siret;
        $this->nom_res = $nom_res;
        $this->coordonnees = $coordonnees;
        $this->adresse = $adresse;
        $this->lien_site = $lien_site;
        $this->horaire = $horaires;
        $this->jourOuverture = $jourOuverture;
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
    public function getHoraires(): string{
        return $this->horaire;
    }
    public function getCoordonnees(): string{
        return $this->coordonnees;
    }
    public function getAdresse(): string{
        return $this->adresse;
    }
    public function getLienSite(): string{
        return $this->lien_site;
    }
    public function setAdresse(string $adresse): void{
        $this->adresse = $adresse;
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
    public function setLienSite(string $lien_site): void{
        $this->lien_site = $lien_site;
    }
}
