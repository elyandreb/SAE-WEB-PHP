document.getElementById("avisForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("nom", document.getElementById("nom").value);
    formData.append("note_reception", document.getElementById("note_reception").value);
    formData.append("note_plats", document.getElementById("note_plats").value);
    formData.append("note_service", document.getElementById("note_service").value);
    formData.append("commentaire", document.getElementById("commentaire").value);

    fetch("FormAvis.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        chargerAvis(); // Recharge les avis après envoi
    });
});
document.addEventListener("DOMContentLoaded", function() {
    chargerAvis();
});

function chargerAvis() {
    fetch("afficher_avis.php")
    .then(response => response.text())
    .then(data => {
        document.getElementById("listeAvis").innerHTML = data;

        // Ajout des événements sur les boutons de suppression
        document.querySelectorAll(".supprimerAvis").forEach(button => {
            button.addEventListener("click", function() {
                let index = this.getAttribute("data-index");
                supprimerAvis(index);
            });
        });
    });
}

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





// Charger les avis au chargement de la page
window.onload = chargerAvis;
