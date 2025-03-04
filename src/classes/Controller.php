<?php

namespace classes;
class Controller{
    private $restaurants;

    public function __construct($restaurants)
    {
        $this->restaurants = $restaurants;
    }
      // Affichage de la liste des restaurants avec le bouton favoris
    public function showRestaurants(): void {
        echo '<div class="restaurants">';
        foreach ($this->restaurants as $restaurant) {
            // Vérifier si le restaurant est favori
            $isFavorite = isset($_SESSION['favoris']) && in_array($restaurant['id'], $_SESSION['favoris']);
            $heartIcon = $isFavorite ? 'img/coeur.svg' : 'img/coeur_vide.svg';

            echo '<div class="restaurant" data-id="' . $restaurant['id'] . '">';
            echo '<span>' . $restaurant['name'] . '</span>';
            echo '<button onclick="toggleFavoris(event, this, ' . $restaurant['id'] . ')">';
            echo '<img src="' . $heartIcon . '" alt="Favori">';
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
    }

    // Méthode pour ajouter ou supprimer un restaurant des favoris
    public function toggleFavorite(int $idRestaurant): void {
        if (!isset($_SESSION['favoris'])) {
            $_SESSION['favoris'] = [];
        }
        if (in_array($idRestaurant, $_SESSION['favoris'])) {
            // Supprimer du favori
            $_SESSION['favoris'] = array_filter($_SESSION['favoris'], function($id) use ($idRestaurant) {
                return $id != $idRestaurant;
            });
            echo json_encode(['status' => 'success', 'favoris' => false]);
        } else {
            // Ajouter aux favoris
            $_SESSION['favoris'][] = $idRestaurant;
            echo json_encode(['status' => 'success', 'favoris' => true]);
        }
    }



}