function toggleFavoris(event, button, idRestaurant) {
    event.stopPropagation();
    event.preventDefault();
    let img = button.querySelector("img");

    // encodeURIComponent au cas où l'id contiendrait des caractères spéciaux
    let url = `/index.php?action=toggle-favoris/${encodeURIComponent(idRestaurant)}`;

    fetch(url, { method: "POST" })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                img.setAttribute("src", data.favoris ? "../static/img/coeur.svg" : "../static/img/coeur_vide.svg");
            } else {
                console.error("Erreur :", data.error);
            }
        })
        .catch(error => console.error("Erreur requête:", error));
}
