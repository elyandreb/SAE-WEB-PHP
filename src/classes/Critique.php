<?php
namespace classes;
class Critique {
    private int $id;
    private int $id_res;
    private int $id_u;
    private string $commentaire;
    private int $note;
    private string $date_critique;

    public function __construct(int $id, int $id_res, int $id_u, string $commentaire, int $note, string $date_critique){
        $this->id = $id;
        $this->id_res = $id_res;  // Correction : id_restaurant => id_res
        $this->id_u = $id_u;
        $this->commentaire = $commentaire;
        $this->note = $note;
        $this->date_critique = $date_critique;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdRestaurant(): int {
        return $this->id_res;
    }

    public function getIdUtilisateur(): int {
        return $this->id_u;
    }

    public function getCommentaire(): string {
        return $this->commentaire;
    }

    public function getNote(): int {
        return $this->note;
    }

    public function getDateCritique(): string {
        return $this->date_critique;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIdRestaurant(int $id_restaurant): void {
        $this->id_res = $id_restaurant; // Correction ici pour id_restaurant
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_u = $id_utilisateur;  // Correction du setter
    }

    public function setCommentaire(string $commentaire): void {
        $this->commentaire = $commentaire;
    }

    public function setNote(int $note): void {
        $this->note = $note;
    }

    public function setDateCritique(string $date_critique): void {
        $this->date_critique = $date_critique;
    }

    public function __toString(): string {
        return "id: ".$this->id." id_restaurant: ".$this->id_res." id_utilisateur: ".$this->id_u." commentaire: ".$this->commentaire." note: ".$this->note." date_critique: ".$this->date_critique;
    }
}
