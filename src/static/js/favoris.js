function toggleFavoris(event, button, idRestaurant) {
    event.stopPropagation();
    event.preventDefault();

    let img = button.querySelector(".coeur");
    console.log("toggleFavoris", idRestaurant);

    let url = `/index.php?action=toggle-favoris&id=${encodeURIComponent(idRestaurant)}`;
    
    fetch(url, { method: "POST" })
    .then(response => {
        console.log("Réponse brute du serveur :", response); // Affiche la réponse complète
        return response.text(); // Récupère la réponse en texte brut
    })
    .then(text => {
        console.log("Réponse brute du serveur texte :", text); // Affiche la réponse texte avant de parser
        return JSON.parse(text); // Tente de convertir la réponse en JSON
    })
    .then(data => {
        if (data.status === "success") {
            if (img) {
                img.setAttribute("src", data.favoris ? "../static/img/coeur.svg" : "../static/img/coeur_vide.svg");
            } else {
                console.error("Image element not found");
            }
        } else {
            console.error("Erreur :", data.error);
        }
    })
    .catch(error => console.error("Erreur de la requête:", error));

}
