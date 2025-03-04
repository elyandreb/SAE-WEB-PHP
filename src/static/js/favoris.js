
function toggleFavoris(event, button, idOffre) {
    event.stopPropagation();
    event.preventDefault();
    let img = button.querySelector("img");
    let isFavoris = img.getAttribute("src").includes("coeur.svg");
    //!!let url = isFavoris 
    //!!    ? `/home/offre/delete-favoris/${idOffre}` 
    //!!    : `/home/offre/add-favoris/${idOffre}`;
    fetch(url, { method: "POST" })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                img.setAttribute("src", data.favoris 
                    ? "{{ url_for('static', filename='img/coeur.svg') }}" 
                    : "{{ url_for('static', filename='img/coeur_vide.svg') }}");
            } else {
                console.error("Erreur :", data.error);
            }
        })
        .catch(error => console.error("Erreur requête:", error));
}


