import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

document.addEventListener("DOMContentLoaded", function () {
    var latitude = 50.52061107649332;
    var longitude = 2.6783712245611895;

    var map = L.map("map").setView([latitude, longitude], 15);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup("88 Rue Sadi Carnot, Beuvry")
        .openPopup();
});
