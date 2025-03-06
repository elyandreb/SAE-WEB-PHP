<?php

namespace classes\controller;
class Controller{
    private $restaurants;

    public function __construct($restaurants)
    {
        $this->restaurants = $restaurants;
    }

    public function showRestaurants(): void {
        echo '<div class="restaurants">';
        foreach ($this->restaurants as $restaurant) {
            // Utiliser osm_id comme identifiant unique
            $idRestaurant = $restaurant['siret'];
            $isFavorite = isset($_SESSION['favoris']) && in_array($idRestaurant, $_SESSION['favoris']);
            $heartIcon = $isFavorite ? '../static/img/coeur.svg' : '../static/img/coeur_vide.svg';
            
            echo '<div class="restaurant" data-id="' . $idRestaurant . '">';
            echo '<span>' . $restaurant['name'] . '</span>';
            echo '<p> '.$restaurant['opening_hours'].'</p>';
            echo '<button onclick="toggleFavoris(event, this, \'' . $idRestaurant . '\')">';
            echo '<img src="' . $heartIcon . '" alt="Favori">';
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
    }
    

    public function toggleFavorite(string $idRestaurant): void {
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
        if (in_array($idRestaurant, $_SESSION['favoris'])) {
            // Supprimer du favori
            $_SESSION['favoris'] = array_filter($_SESSION['favoris'], function($id) use ($idRestaurant) {
                return $id !== $idRestaurant;
            });
            echo json_encode(['status' => 'success', 'favoris' => false]);
        } else {
            // Ajouter aux favoris
            $_SESSION['favoris'][] = $idRestaurant;
            echo json_encode(['status' => 'success', 'favoris' => true]);
        }
    }
    
}