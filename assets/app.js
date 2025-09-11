import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

// Initialiser la carte et centrer sur Paris
var map = L.map("map").setView([50.52055821051865, 2.6783900000000003], 13);

// Ajouter le fond de carte (OpenStreetMap)
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
        '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributeurs',
}).addTo(map);

// Ajouter un marker simple
var marker = L.marker([48.8566, 2.3522]).addTo(map);
marker.bindPopup("<b>Paris</b><br>C'est ici !").openPopup();
