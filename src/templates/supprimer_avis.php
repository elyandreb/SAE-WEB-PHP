<?php
session_start();

if (isset($_GET['index']) && isset($_SESSION['avis'][$_GET['index']])) {
    unset($_SESSION['avis'][$_GET['index']]); // Supprime l'avis
    $_SESSION['avis'] = array_values($_SESSION['avis']); // Réindexe le tableau
    echo "Avis supprimé avec succès !";
} else {
    echo "Erreur : avis introuvable.";
}
?>
