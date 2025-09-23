import "leaflet"; // On importe la librairie Leaflet pour gérer les cartes

// On attend que le contenu HTML soit entièrement chargé
document.addEventListener("DOMContentLoaded", function () {
    // Coordonnées de l'emplacement à afficher
    const latitude = 50.52061107649332;
    const longitude = 2.6783712245611895;

    // Création de la carte centrée sur les coordonnées avec un zoom de 15
    const map = L.map("map").setView([latitude, longitude], 15);

    // Ajout des tuiles OpenStreetMap à la carte avec l'attribution
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // Ajout d'un marqueur à l'emplacement et affichage d'une popup
    L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup("88 Rue Sadi Carnot, Beuvry")
        .openPopup();

    // Optionnel : corrige un bug d'affichage de la carte au premier rendu
    // setTimeout(() => {
    //     map.invalidateSize();
    // }, 200);
});
