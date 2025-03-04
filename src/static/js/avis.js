document.getElementById("avisForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche l'envoi classique du formulaire

    // Récupère les valeurs des champs du formulaire
    let formData = new FormData();
    formData.append("note_reception", document.getElementById("note_reception").value);
    formData.append("note_plats", document.getElementById("note_plats").value);
    formData.append("note_service", document.getElementById("note_service").value);
    formData.append("commentaire", document.getElementById("commentaire").value);

    // Envoie la requête POST avec fetch
    fetch("add_avis.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) // Remplacez response.json() par response.text()
    .then(data => {
        console.log(data); // Affiche la réponse brute dans la console
        chargerAvis(); // Recharge les avis après envoi
    })
    .catch(error => {
        console.error("Erreur lors de l'envoi du formulaire:", error);
    });
    
});

document.addEventListener("DOMContentLoaded", function() {
    chargerAvis(); // Charge les avis au démarrage
});

// Fonction pour charger les avis
function chargerAvis() {
    fetch("les_avis.php")
    .then(response => response.text())
    .then(data => {
        document.getElementById("listeAvis").innerHTML = data;

        // Ajout des événements pour les boutons de suppression
        document.querySelectorAll(".supprimerAvis").forEach(button => {
            button.addEventListener("click", function() {
                let index = this.getAttribute("data-index");
                supprimerAvis(index);
            });
        });
    });
}

// Fonction pour supprimer un avis
function supprimerAvis(index) {
    if (confirm("Voulez-vous vraiment supprimer cet avis ?")) {
        fetch("supprimer_avis.php?index=" + index)
        .then(response => response.text())
        .then(data => {
            alert(data);
            chargerAvis(); // Recharge la liste après suppression
        });
    }
}
