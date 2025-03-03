<?php
session_start();
echo "<pre>";
print_r($_SESSION['avis']);
print_r($_GET['index']);
echo "</pre>";
if (isset($_GET['index']) && isset($_SESSION['avis'][$_GET['index']])) {
    unset($_SESSION['avis'][$_GET['index']]); // Supprime l'avis
    $_SESSION['avis'] = array_values($_SESSION['avis']); // Réindexe le tableau
    echo "Avis supprimé avec succès !";
} else {
    echo "Erreur : avis introuvable.";
}
?>
