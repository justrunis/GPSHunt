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
var mymap = L.map('mapid');

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(mymap);

var marker = null;

if (navigator.geolocation) {
    navigator.geolocation.watchPosition(function(position) {
        var latlng = L.latLng(position.coords.latitude, position.coords.longitude);
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        if (marker) {
            mymap.removeLayer(marker);
        }
        marker = L.marker(latlng).addTo(mymap);
        mymap.setView(latlng, 18);
        console.log("Latitude: " + latitude + ", Longitude: " + longitude);

        // set the clicked coordinates to the table cells
        //document.getElementById('userLatitude').textContent = latitude;
        //document.getElementById('userLongitude').textContent = longitude;
        //console.log("Latitude2: " + latitude + ", Longitude2: " + longitude);

        // send the clicked coordinates to php
        document.getElementById('userLatitudeCoords').value = latitude;
        document.getElementById('userLongitudeCoords').value = longitude;
        console.log("Latitude2: " + latitude + ", Longitude2: " + longitude);
    });
} else {
    // fallback if geolocation is not available
    mymap.setView([0, 0], 2);
}


