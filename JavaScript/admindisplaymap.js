// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * @module    mod_gpshunt/JavaScript
 * @package   mod_qrhunt
 * @copyright Justinas Runevičius <justinas.runevicius@distance.ktu.lt>
 * @author Justinas Runevičius <justinas.runevicius@distance.ktu.lt>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Create a map object and set its initial view
var marker;

var map = L.map('map').setView(
    Number(coords.latitude) ?  [coords.latitude, coords.longitude] : [54.89, 23.90],
    Number(coords.latitude) ? 18 : 7
);

if (Number(coords.latitude)){
    marker = L.marker([coords.latitude, coords.longitude]).addTo(map);
}

// Add a tile layer to the map
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18
}).addTo(map);

map.on('click', function(e) {
    const lat = e.latlng.lat.toFixed(7);
    const lng = e.latlng.lng.toFixed(7);

    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker([lat, lng]).addTo(map);
    console.log("You clicked the map at latitude: " + lat + " and longitude: " + lng);

    // set the clicked coordinates to the table cells
    document.getElementById('latitude').textContent = lat;
    document.getElementById('longitude').textContent = lng;

    // send the clicked coordinates to php
    document.getElementById('latitudeCoords').value = lat;
    document.getElementById('longitudeCoords').value = lng;
});
